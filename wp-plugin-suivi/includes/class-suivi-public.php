<?php
/**
 * Affichage public : shortcode [suivi_tournoi] et adresse REST en lecture seule.
 *
 * Le shortcode pose un conteneur avec l'état courant en JSON (affichage
 * immédiat, sans attendre de requête) ; le script le rafraîchit ensuite
 * toutes les 2 minutes via GET /wp-json/suivi-tournoi/v1/etat.
 *
 * Ne sort que ce qu'un visiteur doit voir : tournois suivis, parties en cours,
 * fil des résultats. Ni réglages, ni erreurs, ni identifiants internes
 * (ID FIDE, ID FFE technique, historique Elo).
 *
 * @package Suivi_Tournoi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Suivi_Public {

	public static function init() {
		add_shortcode( 'suivi_tournoi', array( __CLASS__, 'shortcode' ) );
		add_action( 'rest_api_init', array( __CLASS__, 'routes' ) );
	}

	public static function routes() {
		register_rest_route(
			'suivi-tournoi/v1',
			'/etat',
			array(
				'methods'             => 'GET',
				'callback'            => function () {
					$r = rest_ensure_response( self::donnees() );
					$r->header( 'Cache-Control', 'public, max-age=60' );
					return $r;
				},
				'permission_callback' => '__return_true', // données publiques (déjà publiées par la FFE)
			)
		);
	}

	/** Engagement réduit à ce que l'affichage utilise. */
	private static function engagement( $e, $noms, $renommes = array() ) {
		$garder = array( 'source', 'statut', 'place', 'points', 'nbJoueurs', 'eloTournoi', 'rondeJouee', 'nbRondes', 'rondes', 'liens' );
		$out    = array_intersect_key( $e, array_flip( $garder ) );
		$t      = $e['tournoi'];
		$out['joueur']  = isset( $noms[ $e['joueurId'] ] ) ? $noms[ $e['joueurId'] ] : $e['nomPapi'];
		$out['tournoi'] = array(
			'ref'       => (string) $t['ref'],
			'nom'       => Suivi_Noms::affiche( $t['ref'], $t['nom'], $renommes ),
			'ville'     => ! empty( $t['ville'] ) ? $t['ville'] : '',
			'dateDebut' => isset( $t['dateDebut'] ) ? $t['dateDebut'] : null,
			'dateFin'   => isset( $t['dateFin'] ) ? $t['dateFin'] : null,
			'cadence'   => isset( $t['cadence'] ) ? $t['cadence'] : null,
		);
		if ( $out['tournoi']['nom'] !== (string) $t['nom'] ) {
			$out['tournoi']['nomOriginal'] = (string) $t['nom']; // en info-bulle
		}
		$p = $e['partieEnCours'];
		$out['partie'] = $p ? array_intersect_key( $p, array_flip( array( 'ronde', 'echiquier', 'couleur', 'adversaire', 'resultat', 'enCours', 'pasEncoreCommencee' ) ) ) : null;
		$out['perf']   = ! empty( $e['performance']['perf'] ) ? $e['performance']['perf'] : null;
		return $out;
	}

	public static function donnees() {
		$s    = new Suivi_Store_WP();
		$st   = $s->get( 'etat' );
		$jl   = $s->get( 'journal' );
		$noms = array();
		foreach ( $s->get( 'joueurs' )['joueurs'] as $j ) {
			$noms[ $j['id'] ] = $j['nomComplet'];
		}
		$renommes = $s->get( 'noms' )['liste'];
		$suivi    = array();
		foreach ( $st['suivi'] as $e ) {
			$suivi[] = self::engagement( $e, $noms, $renommes );
		}
		$fil = array();
		foreach ( array_slice( $jl['evenements'], 0, 40 ) as $ev ) {
			$x            = array_intersect_key( $ev, array_flip( array( 'a', 'joueur', 'tournoi', 'ville', 'ronde', 'resultat', 'couleur', 'adversaire', 'lien' ) ) );
			$x['tournoi'] = Suivi_Noms::affiche( isset( $ev['tournoiRef'] ) ? $ev['tournoiRef'] : '', $ev['tournoi'], $renommes );
			$fil[]        = $x;
		}
		return array(
			'misAJour' => $st['dernierRafraichissement'],
			'joueurs'  => count( $noms ),
			'suivi'    => $suivi,
			'fil'      => $fil,
		);
	}

	public static function shortcode() {
		$url = plugins_url( 'assets/', SUIVI_TOURNOI_DIR . '/suivi-tournoi.php' );
		// Version = date du fichier : une mise à jour du plugin n'est jamais masquée par le cache.
		$v = function ( $f ) {
			return (string) @filemtime( SUIVI_TOURNOI_DIR . '/assets/' . $f );
		};
		wp_enqueue_style( 'suivi-tournoi', $url . 'suivi-public.css', array(), $v( 'suivi-public.css' ) );
		wp_enqueue_script( 'suivi-tournoi', $url . 'suivi-public.js', array(), $v( 'suivi-public.js' ), true );
		$config = array(
			'api'     => esc_url_raw( rest_url( 'suivi-tournoi/v1/etat' ) ),
			'donnees' => self::donnees(),
		);
		return '<div class="suivi-direct" data-suivi>'
			. '<script type="application/json" class="suivi-config">' . wp_json_encode( $config, JSON_HEX_TAG | JSON_HEX_AMP ) . '</script>'
			. '<p class="sd-vide">Chargement du suivi…</p></div>';
	}
}
