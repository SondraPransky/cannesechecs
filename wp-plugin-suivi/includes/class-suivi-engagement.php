<?php
/**
 * Le modèle commun d'un engagement (un joueur dans un tournoi) — portage de
 * src/engagement.js.
 *
 * Chaque source ne produit qu'un brut (rondes, partie du moment, identité du
 * tournoi, liens). Tout ce qui s'en déduit est calculé ici, une seule fois :
 * points joués, Elo moyen adverse, performance, statut.
 *
 * @package Suivi_Tournoi
 */

if ( ! defined( 'ABSPATH' ) && ! defined( 'SUIVI_CLI' ) ) {
	exit;
}

class Suivi_Engagement {

	/** Table FIDE : écart dp selon le pourcentage de points (0 à 100). */
	private static $dp = array(
		-800, -677, -589, -538, -501, -470, -444, -422, -401, -383, -366, -351, -336,
		-322, -309, -296, -284, -273, -262, -251, -240, -230, -220, -211, -202, -193,
		-184, -175, -166, -158, -149, -141, -133, -125, -117, -110, -102, -95, -87,
		-80, -72, -65, -57, -50, -43, -36, -29, -21, -14, -7, 0, 7, 14, 21, 29, 36,
		43, 50, 57, 65, 72, 80, 87, 95, 102, 110, 117, 125, 133, 141, 149, 158, 166,
		175, 184, 193, 202, 211, 220, 230, 240, 251, 262, 273, 284, 296, 309, 322,
		336, 351, 366, 383, 401, 422, 444, 470, 501, 538, 589, 677, 800, 800,
	);

	private static $points = array( 'gagne' => 1, 'nulle' => 0.5, 'perdu' => 0 );

	/**
	 * Performance Elo sur une série de parties. Sous trois parties, ou sur un
	 * 100 % / 0 %, la table FIDE ne dit rien d'utile : perf à null.
	 */
	public static function performance( $elos_adverses, $points ) {
		$n = count( $elos_adverses );
		if ( ! $n ) {
			return null;
		}
		$moyenne = array_sum( $elos_adverses ) / $n;
		$pct     = (int) Suivi_Elo::js_round( ( $points / $n ) * 100 );
		return array(
			'moyenneAdversaires' => (int) Suivi_Elo::js_round( $moyenne ),
			'partiesJouees'      => $n,
			'perf'               => ( $n < 3 || 0 === $pct || 100 === $pct )
				? null
				: (int) Suivi_Elo::js_round( $moyenne + self::$dp[ max( 0, min( 100, $pct ) ) ] ),
		);
	}

	/**
	 * Statut déduit d'abord des dates : une case résultat vide sur un tournoi
	 * terminé signifie que l'arbitre ne l'a jamais saisie, pas qu'une partie
	 * est en cours.
	 */
	public static function statut_de( $tournoi, $rondes_jouees ) {
		$today = Suivi_Horloge::today();
		$debut = ! empty( $tournoi['dateDebut'] ) ? $tournoi['dateDebut'] : null;
		$fin   = ! empty( $tournoi['dateFin'] ) ? $tournoi['dateFin'] : $debut;
		if ( $debut && strcmp( $debut, $today ) > 0 ) {
			return 'a_venir';
		}
		if ( $fin && strcmp( $fin, $today ) < 0 ) {
			return 'termine';
		}
		if ( ! $debut && ! $fin ) {
			return 'en_cours';
		}
		if ( ! $debut && strcmp( $fin, $today ) >= 0 ) {
			return $rondes_jouees ? 'en_cours' : 'a_venir';
		}
		return 'en_cours';
	}

	/** `$a ?? $b` sur une clé de tableau éventuellement absente. */
	private static function v( $tab, $cle, $defaut = null ) {
		return ( is_array( $tab ) && isset( $tab[ $cle ] ) ) ? $tab[ $cle ] : $defaut;
	}

	/**
	 * Assemble un engagement complet à partir du brut d'une source.
	 *
	 * Brut : source, joueurId, nomPapi, tournoi, rondes (passées seulement),
	 * enAttente (partie appariée non jouée ou null), liens, place, points,
	 * nbJoueurs, eloTournoi, club, rondeJouee, perfSource, extra.
	 */
	public static function construire( $brut ) {
		$rondes = isset( $brut['rondes'] ) && is_array( $brut['rondes'] ) ? $brut['rondes'] : array();
		$ronde_jouee_brut = self::v( $brut, 'rondeJouee' );
		$statut = self::statut_de( $brut['tournoi'], $ronde_jouee_brut ? $ronde_jouee_brut : count( $rondes ) );

		$en_cours       = 'en_cours' === $statut;
		$partie_encours = null;
		$att            = self::v( $brut, 'enAttente' );
		if ( $att ) {
			$res            = self::v( $att, 'resultat' );
			$partie_encours = array(
				'ronde'              => $att['ronde'],
				'echiquier'          => self::v( $att, 'echiquier' ),
				'couleur'            => self::v( $att, 'couleur' ),
				'adversaire'         => self::v( $att, 'adversaire' ),
				'resultat'           => $res,
				'enCours'            => $en_cours && ! $res,
				// La FFE publie souvent les appariements avant le début de la ronde.
				'pasEncoreCommencee' => $en_cours && ! $res && ! empty( $att['appariementSeul'] ),
				'resultatManquant'   => 'termine' === $statut && ! $res,
			);
		}

		$elos   = array();
		$points = 0;
		foreach ( $rondes as $r ) {
			if ( ! empty( $r['adversaire']['elo'] ) && ! empty( $r['resultat'] ) ) {
				$elos[]  = $r['adversaire']['elo'];
				$points += isset( self::$points[ $r['resultat'] ] ) ? self::$points[ $r['resultat'] ] : 0;
			}
		}

		$calculee = self::performance( $elos, $points );
		$perf     = $calculee;
		// La source publie parfois sa propre performance : elle fait alors foi.
		if ( ! empty( $brut['perfSource'] ) ) {
			$perf = array_merge(
				$calculee ? $calculee : array( 'partiesJouees' => count( $elos ), 'moyenneAdversaires' => null ),
				array(
					'perf'   => $brut['perfSource'],
					'source' => 'ffe' === $brut['source'] ? 'FFE' : $brut['source'],
				)
			);
		}

		$eng = array(
			'source'             => $brut['source'],
			'joueurId'           => $brut['joueurId'],
			'nomPapi'            => $brut['nomPapi'],
			'tournoi'            => $brut['tournoi'],
			'liens'              => self::v( $brut, 'liens', array() ),
			'statut'             => $statut,
			'place'              => self::v( $brut, 'place' ),
			'points'             => self::v( $brut, 'points' ),
			'nbJoueurs'          => self::v( $brut, 'nbJoueurs' ),
			'eloTournoi'         => self::v( $brut, 'eloTournoi' ),
			'club'               => self::v( $brut, 'club' ),
			'rondeJouee'         => null !== $ronde_jouee_brut ? $ronde_jouee_brut : count( $rondes ),
			'nbRondes'           => self::v( $brut['tournoi'], 'nbRondes' ),
			'rondes'             => $rondes,
			'partieEnCours'      => $partie_encours,
			'performance'        => $perf,
			'moyenneAdversaires' => $elos ? (int) Suivi_Elo::js_round( array_sum( $elos ) / count( $elos ) ) : null,
		);
		return array_merge( $eng, self::v( $brut, 'extra', array() ) );
	}
}
