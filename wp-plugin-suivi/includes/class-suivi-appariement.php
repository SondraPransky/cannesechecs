<?php
/**
 * Rapprochement de noms entre l'annuaire et les fichiers de tournoi — portage
 * de src/appariement.js.
 *
 * Les fichiers Papi n'exposent ni licence ni identifiant : la seule clé est le
 * nom, dont l'orthographe et l'ordre des mots varient d'une source à l'autre.
 *
 * @package Suivi_Tournoi
 */

if ( ! defined( 'ABSPATH' ) && ! defined( 'SUIVI_CLI' ) ) {
	exit;
}

class Suivi_Appariement {

	/** Mots triés : rapproche "NOM Prénom" et "Prénom NOM". */
	private static function mots_tries( $s ) {
		$mots = explode( ' ', Suivi_FFE::norm_name( $s ) );
		sort( $mots, SORT_STRING );
		return implode( ' ', $mots );
	}

	/**
	 * Retrouve un joueur suivi parmi les lignes d'un tableau de tournoi.
	 * Comparaison directe, puis sans tenir compte de l'ordre des mots ; les
	 * homonymes sont départagés par le club, puis par l'Elo le plus proche.
	 */
	public static function trouver_joueur( $joueur, $lignes ) {
		$lignes = is_array( $lignes ) ? $lignes : array();
		$cible  = Suivi_FFE::norm_name( $joueur['nomComplet'] );
		$cands  = array_values(
			array_filter(
				$lignes,
				function ( $l ) use ( $cible ) {
					return Suivi_FFE::norm_name( isset( $l['nom'] ) ? $l['nom'] : '' ) === $cible;
				}
			)
		);

		if ( ! $cands ) {
			$triee = self::mots_tries( $joueur['nomComplet'] );
			$cands = array_values(
				array_filter(
					$lignes,
					function ( $l ) use ( $triee ) {
						return self::mots_tries( isset( $l['nom'] ) ? $l['nom'] : '' ) === $triee;
					}
				)
			);
		}

		if ( count( $cands ) > 1 && ! empty( $joueur['club'] ) ) {
			$club     = Suivi_FFE::norm_name( $joueur['club'] );
			$par_club = array_values(
				array_filter(
					$cands,
					function ( $l ) use ( $club ) {
						return Suivi_FFE::norm_name( isset( $l['club'] ) ? $l['club'] : '' ) === $club;
					}
				)
			);
			if ( $par_club ) {
				$cands = $par_club;
			}
		}
		if ( count( $cands ) > 1 && ! empty( $joueur['elo'] ) ) {
			$best = $cands[0];
			for ( $i = 1; $i < count( $cands ); $i++ ) {
				$b = $cands[ $i ];
				if ( abs( ( isset( $best['elo'] ) ? (float) $best['elo'] : 0 ) - $joueur['elo'] )
					> abs( ( isset( $b['elo'] ) ? (float) $b['elo'] : 0 ) - $joueur['elo'] ) ) {
					$best = $b;
				}
			}
			$cands = array( $best );
		}
		return $cands ? $cands[0] : null;
	}

	/** Mots significatifs d'un nom de tournoi (4 lettres et plus, hors nombres). */
	private static function mots_cles( $nom ) {
		$out = array();
		foreach ( explode( ' ', Suivi_FFE::norm_name( $nom ) ) as $m ) {
			if ( strlen( $m ) >= 4 && ! preg_match( '/^\d+$/', $m ) ) {
				$out[ $m ] = true;
			}
		}
		return $out;
	}

	/**
	 * Un même tournoi français est publié par la FFE et par chess-results : on
	 * garde la version FFE. Mêmes dates de fin à deux jours près et au moins deux
	 * mots significatifs en commun valent doublon.
	 */
	public static function est_doublon( $tournoi_cr, $engagements_ffe ) {
		$mots_cr = self::mots_cles( isset( $tournoi_cr['nom'] ) ? $tournoi_cr['nom'] : '' );
		foreach ( $engagements_ffe as $e ) {
			$fin = ! empty( $e['tournoi']['dateFin'] ) ? $e['tournoi']['dateFin'] : ( isset( $e['tournoi']['dateDebut'] ) ? $e['tournoi']['dateDebut'] : null );
			if ( ! $fin || empty( $tournoi_cr['dateFin'] ) ) {
				continue;
			}
			if ( abs( Suivi_Horloge::parse( $fin ) - Suivi_Horloge::parse( $tournoi_cr['dateFin'] ) ) > 2 * 86400 ) {
				continue;
			}
			$communs = 0;
			foreach ( array_keys( self::mots_cles( isset( $e['tournoi']['nom'] ) ? $e['tournoi']['nom'] : '' ) ) as $m ) {
				if ( isset( $mots_cr[ $m ] ) ) {
					$communs++;
				}
			}
			if ( $communs >= 2 ) {
				return true;
			}
		}
		return false;
	}
}
