<?php
/**
 * Couche WordPress : stockage en base, transport HTTP, verrou des passages.
 *
 * Rien ici ne connaît la FFE ni chess-results : ce sont les deux pièces que le
 * moteur reçoit de l'extérieur (Suivi_Store_Interface et le transport).
 *
 * @package Suivi_Tournoi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Documents du moteur rangés dans la table des options, un document par
 * option `suivi_tournoi_<clé>`, sans autoload (le cache pèse plusieurs
 * centaines de Ko et ne doit pas être chargé à chaque page du site).
 */
class Suivi_Store_WP implements Suivi_Store_Interface {

	const PREFIXE = 'suivi_tournoi_';

	/** Toutes les clés que le moteur utilise (pour la désinstallation). */
	const CLES = array( 'config', 'joueurs', 'cache', 'etat', 'journal', 'archive', 'chessresults' );

	public function get( $cle ) {
		return Suivi_Store_Defauts::completer( $cle, get_option( self::PREFIXE . $cle, null ) );
	}

	public function set( $cle, $valeur ) {
		// update_option crée l'option si besoin ; autoload 'no' à la création.
		if ( false === get_option( self::PREFIXE . $cle, false ) ) {
			add_option( self::PREFIXE . $cle, $valeur, '', 'no' );
		} else {
			update_option( self::PREFIXE . $cle, $valeur, false );
		}
	}

	public static function tout_effacer() {
		foreach ( self::CLES as $cle ) {
			delete_option( self::PREFIXE . $cle );
		}
		delete_option( Suivi_WP::VERROU );
	}
}

class Suivi_WP {

	const HOOK_CRON  = 'suivi_tournoi_passage';
	const PLANNING   = 'suivi_tournoi_intervalle';
	const VERROU     = 'suivi_tournoi_verrou';
	/** Un verrou plus vieux que ça vient d'un passage mort (PHP coupé) : on le casse. */
	const VERROU_TTL = 300;

	/**
	 * Transport HTTP du moteur via l'API WordPress (même contrat que
	 * bin/transport-curl-cli.php) :
	 *   $opts : method, body, headers, redirection (0 = ne pas suivre) ;
	 *   rend  : body, content_type, status, headers (location, set-cookie[]).
	 * Lève une exception sur erreur réseau, et sur statut >= 400 quand les
	 * redirections sont suivies (FFE) ; sans suivi (chess-results), le statut
	 * est rendu tel quel.
	 */
	public static function transport() {
		return function ( $url, $opts = array() ) {
			$entetes = isset( $opts['headers'] ) ? $opts['headers'] : array();
			$ua      = isset( $entetes['User-Agent'] ) ? $entetes['User-Agent'] : Suivi_FFE::UA;
			unset( $entetes['User-Agent'] );
			$suivre = ! ( isset( $opts['redirection'] ) && 0 === $opts['redirection'] );
			$args   = array(
				'method'      => isset( $opts['method'] ) ? $opts['method'] : 'GET',
				// La FFE met parfois 10 à 20 s pour une page de liste (mesuré le
				// 2026-11-03) : 15 s faisait échouer des pages saines.
				'timeout'     => 30,
				'user-agent'  => $ua,
				'headers'     => $entetes,
				'redirection' => $suivre ? 5 : 0,
			);
			if ( isset( $opts['body'] ) && null !== $opts['body'] ) {
				$args['body'] = $opts['body'];
			}
			$r = wp_remote_request( $url, $args );
			if ( is_wp_error( $r ) ) {
				throw new RuntimeException( $r->get_error_message() . " sur $url" );
			}
			$statut = (int) wp_remote_retrieve_response_code( $r );
			if ( $suivre && $statut >= 400 ) {
				throw new RuntimeException( "HTTP $statut sur $url" );
			}
			$cookies = array();
			foreach ( wp_remote_retrieve_cookies( $r ) as $c ) {
				$cookies[] = $c->name . '=' . $c->value;
			}
			return array(
				'body'         => wp_remote_retrieve_body( $r ),
				'content_type' => (string) wp_remote_retrieve_header( $r, 'content-type' ),
				'status'       => $statut,
				'headers'      => array(
					'location'   => (string) wp_remote_retrieve_header( $r, 'location' ),
					'set-cookie' => $cookies,
				),
			);
		};
	}

	public static function tracker() {
		return new Suivi_Tracker( new Suivi_Store_WP(), self::transport() );
	}

	// ---------------------------------------------------------------------
	// Verrou : deux passages ne doivent jamais tourner en même temps (WP-cron
	// peut se déclencher sur deux visites simultanées). add_option est
	// atomique : la base refuse un second INSERT sur le même nom.
	// ---------------------------------------------------------------------

	public static function verrouiller() {
		$pose = get_option( self::VERROU, false );
		if ( false !== $pose ) {
			if ( time() - (int) $pose < self::VERROU_TTL ) {
				return false;
			}
			delete_option( self::VERROU ); // passage mort
		}
		return add_option( self::VERROU, time(), '', 'no' );
	}

	public static function deverrouiller() {
		delete_option( self::VERROU );
	}

	/**
	 * Un passage du moteur, sous verrou. Rend l'état, ou null si un autre
	 * passage était déjà en cours.
	 */
	public static function passage( $options = array() ) {
		if ( ! self::verrouiller() ) {
			return null;
		}
		try {
			if ( function_exists( 'set_time_limit' ) ) {
				@set_time_limit( 90 ); // budget du moteur (25 s) + marge
			}
			return self::tracker()->rafraichir( $options );
		} catch ( Throwable $e ) {
			error_log( '[suivi-tournoi] ' . $e->getMessage() );
			return null;
		} finally {
			self::deverrouiller();
		}
	}

	// ---------------------------------------------------------------------
	// Scan complet à la demande : la page d'admin enchaîne des appels AJAX,
	// un passage chacun, tant qu'elle reste ouverte (premier scan en ~1 h au
	// lieu d'une journée au rythme du cron).
	// ---------------------------------------------------------------------

	const AJAX_SCAN = 'suivi_tournoi_scan';

	public static function ajax_scan() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Accès refusé.' ), 403 );
		}
		check_ajax_referer( self::AJAX_SCAN );
		$st  = self::passage();
		$trk = self::tracker();
		wp_send_json_success(
			array(
				'occupe'      => null === $st, // un passage (cron) tournait déjà : réessayer
				'progression' => $trk->progression(),
				'engagements' => null === $st ? null : count( $st['suivi'] ),
				'erreurs'     => null === $st ? array() : array_slice( $st['erreurs'], 0, 5 ),
			)
		);
	}

	// ---------------------------------------------------------------------
	// WP-cron
	// ---------------------------------------------------------------------

	public static function intervalle_secondes() {
		$cfg = ( new Suivi_Store_WP() )->get( 'config' );
		return max( 5, (int) $cfg['intervalleMinutes'] ) * 60;
	}

	public static function plannings( $plannings ) {
		$plannings[ self::PLANNING ] = array(
			'interval' => self::intervalle_secondes(),
			'display'  => 'Suivi tournoi (intervalle du suivi)',
		);
		return $plannings;
	}

	public static function planifier() {
		if ( ! wp_next_scheduled( self::HOOK_CRON ) ) {
			wp_schedule_event( time() + 60, self::PLANNING, self::HOOK_CRON );
		}
	}

	public static function deplanifier() {
		wp_clear_scheduled_hook( self::HOOK_CRON );
	}
}
