<?php
/**
 * Les joueurs suivis : création, mise à jour des Elo, projection publique —
 * portage de src/joueurs.js. Rien ici ne touche aux tournois.
 *
 * Les fonctions travaillent sur le tableau des joueurs passé par référence ;
 * c'est le moteur (Suivi_Tracker) qui charge et enregistre le document.
 *
 * @package Suivi_Tournoi
 */

if ( ! defined( 'ABSPATH' ) && ! defined( 'SUIVI_CLI' ) ) {
	exit;
}

class Suivi_Joueurs {

	/** Fiche interne d'un joueur à partir d'une ligne d'annuaire FFE. */
	public static function creer( $fiche, $secours = array() ) {
		$nom_complet = ! empty( $fiche['nomComplet'] )
			? $fiche['nomComplet']
			: trim( ( isset( $secours['nom'] ) ? $secours['nom'] : '' ) . ' ' . ( isset( $secours['prenom'] ) ? $secours['prenom'] : '' ) );
		$elo = isset( $fiche['elo'] ) ? $fiche['elo'] : null;
		return array(
			'id'            => ! empty( $fiche['codeFFE'] ) ? $fiche['codeFFE'] : str_replace( ' ', '_', Suivi_FFE::norm_name( $nom_complet ) ),
			'codeFFE'       => ! empty( $fiche['codeFFE'] ) ? $fiche['codeFFE'] : null,
			'ffeId'         => ! empty( $fiche['id'] ) ? $fiche['id'] : null,
			'clubRef'       => ! empty( $fiche['clubRef'] ) ? $fiche['clubRef'] : null,
			'nomComplet'    => $nom_complet,
			'club'          => ! empty( $fiche['club'] ) ? $fiche['club'] : ( ! empty( $secours['club'] ) ? $secours['club'] : null ),
			'elo'           => $elo,
			'rapide'        => isset( $fiche['rapide'] ) ? $fiche['rapide'] : null,
			'blitz'         => isset( $fiche['blitz'] ) ? $fiche['blitz'] : null,
			'cat'           => ! empty( $fiche['cat'] ) ? $fiche['cat'] : null,
			'eloEstime'     => isset( $fiche['eloEstime'] ) ? $fiche['eloEstime'] : null,
			'urlFiche'      => ! empty( $fiche['urlFiche'] ) ? $fiche['urlFiche'] : null,
			'historiqueElo' => null !== $elo
				? array( array( 'date' => Suivi_Horloge::today(), 'elo' => $elo, 'rapide' => $fiche['rapide'], 'blitz' => $fiche['blitz'] ) )
				: array(),
			'eloMajLe'      => $fiche ? Suivi_Horloge::iso() : null,
			'ajouteLe'      => Suivi_Horloge::iso(),
		);
	}

	/** Égalité numérique tolérante (un 2763 relu du JSON peut revenir en entier). */
	private static function egal( $a, $b ) {
		if ( null === $a || null === $b ) {
			return $a === $b;
		}
		return (float) $a === (float) $b;
	}

	/** Applique une fiche d'annuaire fraîche sur un joueur suivi. */
	public static function appliquer_elo( &$j, $r ) {
		$avant = isset( $j['elo'] ) ? $j['elo'] : null;
		foreach ( array( 'elo', 'rapide', 'blitz', 'cat', 'club', 'eloEstime', 'urlFiche' ) as $k ) {
			$j[ $k ] = isset( $r[ $k ] ) ? $r[ $k ] : null;
		}
		$j['eloMajLe'] = Suivi_Horloge::iso();
		if ( empty( $j['historiqueElo'] ) ) {
			$j['historiqueElo'] = array();
		}
		$dernier = end( $j['historiqueElo'] );
		if ( ! $dernier || ! self::egal( $dernier['elo'], $r['elo'] ) || ! self::egal( $dernier['rapide'], $r['rapide'] ) || ! self::egal( $dernier['blitz'], $r['blitz'] ) ) {
			$j['historiqueElo'][] = array( 'date' => Suivi_Horloge::today(), 'elo' => $r['elo'], 'rapide' => $r['rapide'], 'blitz' => $r['blitz'] );
			if ( count( $j['historiqueElo'] ) > 60 ) {
				array_shift( $j['historiqueElo'] );
			}
		}
		$j['variationElo'] = ( null !== $avant && null !== $r['elo'] ) ? $r['elo'] - $avant : 0;
	}

	/**
	 * Met à jour les Elo (une fois par jour suffit : la FFE publie mensuellement).
	 * Les joueurs venus d'un club se relisent en bloc, quelques pages d'annuaire
	 * au lieu d'une recherche par joueur.
	 *
	 * @param array     $joueurs Liste des joueurs, modifiée sur place.
	 * @param Suivi_FFE $ffe
	 */
	public static function maj_elo( &$joueurs, $ffe, $force, &$erreurs, &$reprises = null ) {
		if ( ! is_array( $reprises ) ) {
			$reprises = array();
		}
		$limite  = Suivi_Horloge::ts() - 20 * 3600;
		$a_faire = array();
		foreach ( $joueurs as $i => $j ) {
			$perime = empty( $j['eloMajLe'] ) || Suivi_Horloge::parse( $j['eloMajLe'] ) <= $limite;
			// Joueur introuvable à la FFE (a quitté le club, code changé…) : une
			// tentative par jour suffit. Sans cette mémoire, il gardait son Elo
			// « périmé » et toute la liste du club (10 pages) était relue à chaque
			// passage pour rien, au détriment du reste du suivi.
			$tente = ! empty( $j['eloEssaiLe'] ) && Suivi_Horloge::parse( $j['eloEssaiLe'] ) > $limite;
			if ( $force || ( $perime && ! $tente ) ) {
				$a_faire[ $i ] = true;
			}
		}
		if ( ! $a_faire ) {
			return;
		}

		$clubs = array();
		foreach ( array_keys( $a_faire ) as $i ) {
			// Absent de la liste de son club à la dernière lecture : recherche
			// individuelle directement, sans relire tout le club.
			$hors_club = ! empty( $joueurs[ $i ]['horsClubLe'] ) && Suivi_Horloge::parse( $joueurs[ $i ]['horsClubLe'] ) > $limite;
			if ( ! empty( $joueurs[ $i ]['clubRef'] ) && ! $hors_club ) {
				$clubs[ $joueurs[ $i ]['clubRef'] ] = true;
			}
		}
		foreach ( array_keys( $clubs ) as $club_ref ) {
			// Lecture du club commencée à un passage précédent (moins d'une heure) : on la continue.
			$reprise = ( isset( $reprises[ $club_ref ]['a'] ) && Suivi_Horloge::ts() - $reprises[ $club_ref ]['a'] < 3600 ) ? $reprises[ $club_ref ]['reprise'] : null;
			$depuis  = $reprise ? $reprises[ $club_ref ]['a'] : Suivi_Horloge::ts();
			try {
				$par_code = array();
				$liste    = $ffe->joueurs_du_club( $club_ref, $reprise );
				unset( $reprises[ $club_ref ] );
				foreach ( $liste as $r ) {
					$par_code[ $r['codeFFE'] ] = $r;
				}
				foreach ( array_keys( $a_faire ) as $i ) {
					$j = $joueurs[ $i ];
					if ( ( isset( $j['clubRef'] ) ? $j['clubRef'] : null ) != $club_ref ) {
						continue;
					}
					if ( ! empty( $j['codeFFE'] ) && isset( $par_code[ $j['codeFFE'] ] ) ) {
						self::appliquer_elo( $joueurs[ $i ], $par_code[ $j['codeFFE'] ] );
						unset( $a_faire[ $i ] );
					} else {
						$joueurs[ $i ]['horsClubLe'] = Suivi_Horloge::iso();
					}
				}
			} catch ( Suivi_Budget_Epuise $e ) {
				if ( $reprise ) {
					$reprises[ $club_ref ] = array( 'reprise' => $reprise, 'a' => $depuis );
				}
				throw $e;
			} catch ( Exception $e ) {
				if ( $reprise ) {
					$reprises[ $club_ref ] = array( 'reprise' => $reprise, 'a' => $depuis );
				}
				$erreurs[] = "Elo club $club_ref : " . $e->getMessage();
			}
		}

		foreach ( array_keys( $a_faire ) as $i ) {
			$j = $joueurs[ $i ];
			// Sans code fédéral, pas de recherche fiable : l'original cherchait alors
			// le nom littéral "undefined". On s'abstient plutôt que de risquer d'appliquer
			// l'Elo d'un autre joueur.
			if ( empty( $j['codeFFE'] ) ) {
				$joueurs[ $i ]['eloEssaiLe'] = Suivi_Horloge::iso();
				continue;
			}
			try {
				$r = $ffe->elo_joueur( $j['codeFFE'] );
				if ( $r ) {
					self::appliquer_elo( $joueurs[ $i ], $r );
				}
				$joueurs[ $i ]['eloEssaiLe'] = Suivi_Horloge::iso();
			} catch ( Suivi_Budget_Epuise $e ) {
				throw $e;
			} catch ( Exception $e ) {
				$erreurs[]                   = 'Elo ' . $j['nomComplet'] . ' : ' . $e->getMessage();
				$joueurs[ $i ]['eloEssaiLe'] = Suivi_Horloge::iso();
			}
		}
	}

	/** Complète les ID FIDE manquants depuis les fiches FFE (clé de chess-results). */
	public static function maj_fide_ids( &$joueurs, $ffe, &$erreurs ) {
		foreach ( $joueurs as $i => $j ) {
			// null = « vérifié, pas d'ID FIDE » : on ne redemande pas.
			if ( array_key_exists( 'fideId', $j ) || empty( $j['ffeId'] ) ) {
				continue;
			}
			try {
				$joueurs[ $i ]['fideId'] = $ffe->fide_id_du_joueur( $j['ffeId'] );
			} catch ( Suivi_Budget_Epuise $e ) {
				throw $e;
			} catch ( Exception $e ) {
				$erreurs[] = 'FIDE-ID ' . $j['nomComplet'] . ' : ' . $e->getMessage();
			}
		}
	}

	/**
	 * Ce que l'affichage reçoit d'un joueur : les clés de jointure interne
	 * (ID FIDE, ID technique FFE, ClubRef, historique) restent dans le moteur.
	 */
	public static function joueur_public( $j ) {
		$out = array();
		foreach ( array( 'id', 'codeFFE', 'nomComplet', 'club', 'cat', 'elo', 'rapide', 'blitz', 'eloEstime', 'variationElo', 'urlFiche' ) as $k ) {
			$out[ $k ] = isset( $j[ $k ] ) ? $j[ $k ] : null;
		}
		return $out;
	}
}
