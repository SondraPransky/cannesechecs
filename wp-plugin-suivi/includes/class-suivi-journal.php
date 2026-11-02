<?php
/**
 * Le fil des résultats et l'archive des parties — portage de src/journal.js
 * et src/archive.js.
 *
 * @package Suivi_Tournoi
 */

if ( ! defined( 'ABSPATH' ) && ! defined( 'SUIVI_CLI' ) ) {
	exit;
}

/** Le fil : ce qui est tombé depuis le dernier passage. */
class Suivi_Journal {

	const MAX_EVENEMENTS = 400;

	/** Lien le plus parlant d'un engagement, pour un événement du fil. */
	private static function lien( $liens ) {
		foreach ( array( 'ronde', 'grille', 'fiche' ) as $cle ) {
			foreach ( $liens as $l ) {
				if ( $l['cle'] === $cle ) {
					return $l['href'];
				}
			}
		}
		return null;
	}

	/**
	 * Note les résultats nouveaux. La première fois qu'un joueur est vu dans un
	 * tournoi, son parcours est enregistré sans rien annoncer : ces parties
	 * étaient déjà jouées quand le suivi les a découvertes.
	 *
	 * @return int Nombre de nouveaux résultats.
	 */
	public static function maj( &$jl, $suivi, $joueurs ) {
		$nom_de = array();
		foreach ( $joueurs as $j ) {
			$nom_de[ $j['id'] ] = $j['nomComplet'];
		}
		$maintenant = Suivi_Horloge::iso();
		$nouveaux   = array();

		foreach ( $suivi as $s ) {
			$cle         = $s['tournoi']['ref'] . '|' . $s['joueurId'];
			$deja_suivi  = array_key_exists( $cle, $jl['connus'] );
			$connus      = $deja_suivi ? (array) $jl['connus'][ $cle ] : array();

			foreach ( $s['rondes'] as $r ) {
				if ( empty( $r['numero'] ) || empty( $r['resultat'] ) ) {
					continue;
				}
				if ( isset( $connus[ $r['numero'] ] ) ) {
					continue;
				}
				$connus[ $r['numero'] ] = $r['resultat'];
				if ( ! $deja_suivi ) {
					continue;
				}
				$nouveaux[] = array(
					'a'          => $maintenant,
					'joueurId'   => $s['joueurId'],
					'joueur'     => isset( $nom_de[ $s['joueurId'] ] ) ? $nom_de[ $s['joueurId'] ] : $s['nomPapi'],
					'tournoiRef' => $s['tournoi']['ref'],
					'tournoi'    => $s['tournoi']['nom'],
					'ville'      => ! empty( $s['tournoi']['ville'] ) ? $s['tournoi']['ville'] : ( ! empty( $s['tournoi']['lieu'] ) ? $s['tournoi']['lieu'] : '' ),
					'ronde'      => $r['numero'],
					'resultat'   => $r['resultat'],
					'couleur'    => isset( $r['couleur'] ) ? $r['couleur'] : null,
					'adversaire' => ! empty( $r['adversaire'] ) ? $r['adversaire'] : null,
					'lien'       => self::lien( $s['liens'] ),
				);
			}
			$jl['connus'][ $cle ] = $connus;
		}

		// Les rondes d'un même relevé arrivent ensemble : la plus récente en tête.
		usort(
			$nouveaux,
			function ( $a, $b ) {
				return $b['ronde'] - $a['ronde'];
			}
		);
		$jl['evenements'] = array_slice( array_merge( $nouveaux, $jl['evenements'] ), 0, self::MAX_EVENEMENTS );
		return count( $nouveaux );
	}

	/** Oublie complètement un joueur : ses événements et sa mémoire de rondes. */
	public static function oublier( &$jl, $id ) {
		$jl['evenements'] = array_values(
			array_filter(
				$jl['evenements'],
				function ( $e ) use ( $id ) {
					return $e['joueurId'] !== $id;
				}
			)
		);
		foreach ( array_keys( $jl['connus'] ) as $cle ) {
			if ( substr( $cle, -strlen( "|$id" ) ) === "|$id" ) {
				unset( $jl['connus'][ $cle ] );
			}
		}
	}
}

/**
 * L'archive : la fenêtre de suivi est glissante, un tournoi qui en sort
 * emporterait ses parties. Celles des engagements terminés sont donc copiées
 * ici définitivement ; le bilan de saison se construit dessus.
 */
class Suivi_Archive {

	private static $points   = array( 'gagne' => 1, 'nulle' => 0.5, 'perdu' => 0 );
	private static $tranches = array(
		array( 'nom' => 'moins de 1800', 'min' => 0, 'max' => 1799 ),
		array( 'nom' => '1800 à 2099', 'min' => 1800, 'max' => 2099 ),
		array( 'nom' => '2100 et plus', 'min' => 2100, 'max' => 9999 ),
	);

	private static function lisibles( $rondes ) {
		return array_values(
			array_filter(
				$rondes,
				function ( $r ) {
					return ! empty( $r['resultat'] ) && ! empty( $r['adversaire'] );
				}
			)
		);
	}

	/**
	 * Archive les parties des engagements terminés. Une paire tournoi|joueur
	 * n'est figée que si son parcours n'a pas grossi depuis : un tournoi archivé
	 * avant la saisie complète de l'arbitre est remplacé quand il s'enrichit.
	 *
	 * @return int Parties ajoutées.
	 */
	public static function archiver( &$a, $suivi ) {
		$ajoutees = 0;
		foreach ( $suivi as $s ) {
			if ( 'termine' !== $s['statut'] ) {
				continue;
			}
			$cle      = $s['tournoi']['ref'] . '|' . $s['joueurId'];
			$lisibles = self::lisibles( $s['rondes'] );

			if ( ! empty( $a['cles'][ $cle ] ) ) {
				if ( count( $lisibles ) <= $a['cles'][ $cle ]['parties'] ) {
					continue;
				}
				$ref        = $s['tournoi']['ref'];
				$jid        = $s['joueurId'];
				$a['parties'] = array_values(
					array_filter(
						$a['parties'],
						function ( $p ) use ( $ref, $jid ) {
							return ! ( $p['tournoiRef'] === $ref && $p['joueurId'] === $jid );
						}
					)
				);
				$ajoutees -= $a['cles'][ $cle ]['parties'];
			}

			$parties = array();
			foreach ( $lisibles as $r ) {
				$parties[] = array(
					'joueurId'   => $s['joueurId'],
					'tournoiRef' => $s['tournoi']['ref'],
					'tournoi'    => $s['tournoi']['nom'],
					'date'       => ! empty( $s['tournoi']['dateFin'] ) ? $s['tournoi']['dateFin'] : ( ! empty( $s['tournoi']['dateDebut'] ) ? $s['tournoi']['dateDebut'] : null ),
					'source'     => $s['source'],
					'ronde'      => $r['numero'],
					'couleur'    => $r['couleur'],
					'resultat'   => $r['resultat'],
					'adversaire' => $r['adversaire']['nom'],
					'eloAdverse' => isset( $r['adversaire']['elo'] ) ? $r['adversaire']['elo'] : null,
					'monElo'     => isset( $s['eloTournoi'] ) ? $s['eloTournoi'] : null,
					'place'      => isset( $s['place'] ) ? $s['place'] : null,
					'nbJoueurs'  => isset( $s['nbJoueurs'] ) ? $s['nbJoueurs'] : null,
				);
			}
			if ( ! $parties ) {
				continue;
			}
			$a['parties']       = array_merge( $a['parties'], $parties );
			$a['cles'][ $cle ] = array( 'a' => Suivi_Horloge::iso(), 'parties' => count( $parties ) );
			$ajoutees          += count( $parties );
		}
		return $ajoutees;
	}

	/** Bilan d'un joueur : l'archive plus ses engagements du moment. */
	public static function bilan( $a, $joueur_id, $suivi_actuel = array() ) {
		$archivees = array_values(
			array_filter(
				$a['parties'],
				function ( $p ) use ( $joueur_id ) {
					return $p['joueurId'] === $joueur_id;
				}
			)
		);
		$deja = array();
		foreach ( $archivees as $p ) {
			$deja[ $p['tournoiRef'] . '|' . $p['ronde'] ] = true;
		}

		$vivantes = array();
		foreach ( $suivi_actuel as $s ) {
			if ( $s['joueurId'] !== $joueur_id ) {
				continue;
			}
			foreach ( self::lisibles( $s['rondes'] ) as $r ) {
				if ( isset( $deja[ $s['tournoi']['ref'] . '|' . $r['numero'] ] ) ) {
					continue;
				}
				$vivantes[] = array(
					'tournoiRef' => $s['tournoi']['ref'],
					'tournoi'    => $s['tournoi']['nom'],
					'date'       => ! empty( $s['tournoi']['dateFin'] ) ? $s['tournoi']['dateFin'] : ( ! empty( $s['tournoi']['dateDebut'] ) ? $s['tournoi']['dateDebut'] : null ),
					'ronde'      => $r['numero'],
					'couleur'    => $r['couleur'],
					'resultat'   => $r['resultat'],
					'adversaire' => $r['adversaire']['nom'],
					'eloAdverse' => isset( $r['adversaire']['elo'] ) ? $r['adversaire']['elo'] : null,
				);
			}
		}

		$parties = array_merge( $archivees, $vivantes );
		if ( ! $parties ) {
			return array( 'parties' => 0 );
		}

		$pts       = function ( $p ) {
			return isset( self::$points[ $p['resultat'] ] ) ? self::$points[ $p['resultat'] ] : 0;
		};
		$tournois  = array();
		$points    = 0;
		$meilleure = null;
		$avec_elo  = array();
		foreach ( $parties as $p ) {
			$tournois[ $p['tournoiRef'] ] = true;
			$points                      += $pts( $p );
			if ( ! empty( $p['eloAdverse'] ) ) {
				$avec_elo[] = $p['eloAdverse'];
				if ( 'gagne' === $p['resultat'] && ( ! $meilleure || $p['eloAdverse'] > $meilleure['eloAdverse'] ) ) {
					$meilleure = $p;
				}
			}
		}

		$tranches = array();
		foreach ( self::$tranches as $t ) {
			$jouees = 0;
			$tp     = 0;
			foreach ( $parties as $p ) {
				// Comme l'original (où `null >= 0` est vrai) : un adversaire sans Elo
				// compte dans la première tranche.
				$e = isset( $p['eloAdverse'] ) ? $p['eloAdverse'] : 0;
				if ( $e >= $t['min'] && $e <= $t['max'] ) {
					$jouees++;
					$tp += $pts( $p );
				}
			}
			if ( $jouees > 0 ) {
				$tranches[] = array( 'nom' => $t['nom'], 'jouees' => $jouees, 'points' => $tp );
			}
		}

		$dates = array_values( array_filter( array_column( $parties, 'date' ) ) );
		sort( $dates, SORT_STRING );

		return array(
			'parties'            => count( $parties ),
			'tournois'           => count( $tournois ),
			'points'             => $points,
			'moyenneAdversaires' => $avec_elo ? (int) Suivi_Elo::js_round( array_sum( $avec_elo ) / count( $avec_elo ) ) : null,
			'meilleureVictoire'  => $meilleure
				? array( 'adversaire' => $meilleure['adversaire'], 'elo' => $meilleure['eloAdverse'], 'tournoi' => $meilleure['tournoi'] )
				: null,
			'tranches'           => $tranches,
			'depuis'             => $dates ? $dates[0] : null,
		);
	}

	/** Oublie un joueur retiré du suivi. */
	public static function oublier( &$a, $id ) {
		$a['parties'] = array_values(
			array_filter(
				$a['parties'],
				function ( $p ) use ( $id ) {
					return $p['joueurId'] !== $id;
				}
			)
		);
		foreach ( array_keys( $a['cles'] ) as $cle ) {
			if ( substr( $cle, -strlen( "|$id" ) ) === "|$id" ) {
				unset( $a['cles'][ $cle ] );
			}
		}
	}
}
