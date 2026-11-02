<?php
/**
 * Classement en direct : l'Elo officiel plus ce que rapportent les parties déjà
 * jouées dans les tournois en cours — portage PHP de src/elo.js.
 *
 * C'est le seul chiffre que l'outil avance sans qu'aucune source ne le publie :
 * la FFE recalcule les Elo une fois par mois. Il est donc toujours présenté comme
 * une ESTIMATION, l'Elo officiel affiché à côté.
 *
 * @package Suivi_Tournoi
 */

if ( ! defined( 'ABSPATH' ) && ! defined( 'SUIVI_CLI' ) ) {
	exit;
}

class Suivi_Elo {

	private static $points = array( 'gagne' => 1, 'nulle' => 0.5, 'perdu' => 0 );

	/**
	 * Titres FIDE impliquant d'avoir atteint 2400 (seuil de MI) : K reste à 10 à
	 * vie. Égalité STRICTE : « MIF » (2200) ne doit pas passer pour « MI », ni
	 * « GMIF » pour « GMI ».
	 */
	private static $titres_2400 = array( 'GM', 'IM', 'GMI', 'MI' );

	/** Math.round de JavaScript (arrondi au demi supérieur), pour des résultats identiques. */
	public static function js_round( $x ) {
		return floor( $x + 0.5 );
	}

	public static function est_maitre( $titre ) {
		return in_array( strtoupper( trim( (string) $titre ) ), self::$titres_2400, true );
	}

	/** Catégories FFE de moins de 18 ans : poussin, pupille, benjamin, minime, cadet. */
	private static function est_jeune( $cat ) {
		return 1 === preg_match( '/^(pou|pup|ben|min|cad)/i', (string) $cat );
	}

	/**
	 * Coefficient de développement FIDE :
	 *  - K=10 dès qu'on a atteint 2400, une fois pour toutes ;
	 *  - K=40 pour un jeune tant qu'il reste sous 2300 ;
	 *  - K=20 sinon.
	 * (La branche « K=40 avant 30 parties » n'est pas appliquée : ce compte n'est
	 * publié nulle part.)
	 */
	public static function coefficient_k( $elo, $cat, $atteint_2400 = false ) {
		if ( $elo >= 2400 || $atteint_2400 ) {
			return 10;
		}
		if ( $elo < 2300 && self::est_jeune( $cat ) ) {
			return 40;
		}
		return 20;
	}

	/** Score attendu face à un adversaire, formule FIDE. */
	public static function score_attendu( $elo, $elo_adverse ) {
		return 1 / ( 1 + pow( 10, ( $elo_adverse - $elo ) / 400 ) );
	}

	/**
	 * Variation apportée par une série de parties, arrondie au centième.
	 *
	 * @param array[] $parties [ ['resultat' => 'gagne'|'nulle'|'perdu', 'eloAdverse' => int], ... ]
	 */
	public static function variation( $elo, $cat, $parties, $atteint_2400 = false ) {
		$k    = self::coefficient_k( $elo, $cat, $atteint_2400 );
		$brut = 0;
		foreach ( $parties as $p ) {
			$pts   = isset( self::$points[ $p['resultat'] ] ) ? self::$points[ $p['resultat'] ] : 0;
			$brut += $k * ( $pts - self::score_attendu( $elo, $p['eloAdverse'] ) );
		}
		return self::js_round( $brut * 100 ) / 100;
	}

	/**
	 * Durée nominale d'une partie en minutes (formule FIDE) : base + 60 × incrément.
	 * « 1h30/40 - 30' + [30''] » vaut 90 + 30 = 120. Seule la 1re période compte.
	 */
	public static function minutes_de( $cadence ) {
		$s = (string) $cadence;
		if ( '' === trim( $s ) ) {
			return null;
		}
		if ( preg_match( '/(\d+)\s*h\s*(\d*)/i', $s, $h ) ) {
			$base = (int) $h[1] * 60 + (int) ( '' !== $h[2] ? $h[2] : 0 );
		} elseif ( preg_match( "/(\\d+)\\s*['\x{2032}]/u", $s, $m ) ) {
			$base = (int) $m[1];
		} else {
			return null;
		}
		$incr = preg_match( "/\\[\\s*(\\d+)\\s*(?:''|\"|\x{2033})/u", $s, $i ) ? (int) $i[1] : 0;
		return $base + $incr;
	}

	/**
	 * Le classement qu'un tournoi fait bouger, et d'où on le tient : la cadence
	 * tranche quand elle est publiée (< 10 min blitz, < 60 rapide, sinon classique),
	 * sinon le nom, sinon classique par défaut. `dapres` dit d'où vient la réponse.
	 */
	public static function nature_tournoi( $tournoi ) {
		$m = self::minutes_de( isset( $tournoi['cadence'] ) ? $tournoi['cadence'] : '' );
		if ( null !== $m ) {
			return array(
				'nature' => $m < 10 ? 'blitz' : ( $m < 60 ? 'rapide' : 'classique' ),
				'dapres' => 'cadence',
			);
		}
		$nom = isset( $tournoi['nom'] ) ? (string) $tournoi['nom'] : '';
		if ( preg_match( '/\bblitz\b/i', $nom ) ) {
			return array( 'nature' => 'blitz', 'dapres' => 'nom' );
		}
		if ( preg_match( '/\brapid(?:e|es|o)?\b/i', $nom ) ) {
			return array( 'nature' => 'rapide', 'dapres' => 'nom' );
		}
		return array( 'nature' => 'classique', 'dapres' => 'defaut' );
	}

	/**
	 * Classement en direct d'un joueur, ou null s'il n'est engagé nulle part.
	 * Seuls les tournois classiques déplacent le chiffre ; les autres sont
	 * remontés pour que l'écran puisse dire pourquoi un joueur ne bouge pas.
	 *
	 * @param array   $joueur      ['elo' => int|null, 'cat' => string]
	 * @param array[] $engagements [ ['statut','titre','eloTournoi','tournoi'=>[ref,nom,cadence],
	 *                               'rondes'=>[ ['resultat','adversaire'=>['elo']], ... ]], ... ]
	 */
	public static function direct_joueur( $joueur, $engagements ) {
		if ( ! isset( $joueur['elo'] ) || null === $joueur['elo'] ) {
			return null;
		}
		$en_cours = array_values(
			array_filter(
				$engagements,
				function ( $s ) {
					return isset( $s['statut'] ) && 'en_cours' === $s['statut'];
				}
			)
		);
		if ( ! $en_cours ) {
			return null;
		}

		// « A atteint 2400 » est un fait de joueur : vrai pour tous ses tournois.
		$atteint = $joueur['elo'] >= 2400;
		foreach ( $engagements as $s ) {
			if ( ! empty( $s['titre'] ) && self::est_maitre( $s['titre'] ) ) {
				$atteint = true;
			}
		}
		$cat = isset( $joueur['cat'] ) ? $joueur['cat'] : '';

		$tournois = array();
		$delta    = 0;
		$parties  = 0;
		foreach ( $en_cours as $s ) {
			$nat    = self::nature_tournoi( $s['tournoi'] );
			$jouees = array();
			foreach ( isset( $s['rondes'] ) ? $s['rondes'] : array() as $r ) {
				if ( ! empty( $r['resultat'] ) && ! empty( $r['adversaire']['elo'] ) ) {
					$jouees[] = array( 'resultat' => $r['resultat'], 'eloAdverse' => $r['adversaire']['elo'] );
				}
			}
			// L'Elo inscrit au tournoi fait foi : K et delta partagent cette base.
			$base   = ( isset( $s['eloTournoi'] ) && null !== $s['eloTournoi'] ) ? $s['eloTournoi'] : $joueur['elo'];
			$compte = 'classique' === $nat['nature'];
			$k      = self::coefficient_k( $base, $cat, $atteint );
			$d      = ( $compte && $jouees ) ? self::variation( $base, $cat, $jouees, $atteint ) : 0;
			if ( $compte ) {
				$delta   += $d;
				$parties += count( $jouees );
			}
			$tournois[] = array(
				'ref'     => $s['tournoi']['ref'],
				'nom'     => $s['tournoi']['nom'],
				'nature'  => $nat['nature'],
				'dapres'  => $nat['dapres'],
				'parties' => count( $jouees ),
				'delta'   => $compte ? $d : null,
				'k'       => $compte ? $k : null,
			);
		}

		$delta = self::js_round( $delta * 100 ) / 100;
		return array(
			'officiel' => $joueur['elo'],
			'live'     => self::js_round( ( $joueur['elo'] + $delta ) * 10 ) / 10,
			'delta'    => $delta,
			'parties'  => $parties,
			'tournois' => $tournois,
		);
	}
}
