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
	const CLES = array( 'config', 'joueurs', 'cache', 'etat', 'journal', 'archive', 'chessresults', 'masques' );

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
		delete_option( Suivi_WP::PRIORITE );
	}
}

class Suivi_WP {

	const HOOK_CRON  = 'suivi_tournoi_passage';
	const PLANNING   = 'suivi_tournoi_intervalle';
	const VERROU     = 'suivi_tournoi_verrou';
	/** Une action de l'admin attend le verrou : les passages lui cèdent la place. */
	const PRIORITE   = 'suivi_tournoi_priorite';
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
				'timeout'     => isset( $opts['timeout'] ) ? $opts['timeout'] : 30,
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

	/**
	 * Transport groupé : plusieurs requêtes envoyées ensemble (Requests, la
	 * bibliothèque HTTP de WordPress). Même contrat que transport(), par clé :
	 * [clé => ['url', 'opts']] -> [clé => réponse | Throwable].
	 * L'app d'origine envoyait 4 requêtes à la fois à la FFE (2 à chess-results) ;
	 * une par une, le premier scan prenait des heures.
	 */
	public static function transport_multiple() {
		return function ( $requetes ) {
			$classe = class_exists( '\WpOrg\Requests\Requests' ) ? '\WpOrg\Requests\Requests' : 'Requests';
			$ca     = ABSPATH . WPINC . '/certificates/ca-bundle.crt';
			$liste  = array();
			$suivre = array();
			foreach ( $requetes as $k => $r ) {
				$o            = isset( $r['opts'] ) ? $r['opts'] : array();
				$entetes      = isset( $o['headers'] ) ? $o['headers'] : array();
				$ua           = isset( $entetes['User-Agent'] ) ? $entetes['User-Agent'] : Suivi_FFE::UA;
				unset( $entetes['User-Agent'] );
				$suivre[ $k ] = ! ( isset( $o['redirection'] ) && 0 === $o['redirection'] );
				$liste[ $k ]  = array(
					'url'     => $r['url'],
					'type'    => isset( $o['method'] ) ? $o['method'] : 'GET',
					'headers' => $entetes,
					'data'    => isset( $o['body'] ) && null !== $o['body'] ? $o['body'] : array(),
					'options' => array(
						'timeout'          => isset( $o['timeout'] ) ? $o['timeout'] : 30,
						'connect_timeout'  => 10,
						'useragent'        => $ua,
						'follow_redirects' => $suivre[ $k ],
						'redirects'        => 5,
						'verify'           => is_file( $ca ) ? $ca : true,
					),
				);
			}
			$out = array();
			foreach ( $classe::request_multiple( $liste ) as $k => $rep ) {
				if ( $rep instanceof Throwable ) {
					$out[ $k ] = new RuntimeException( $rep->getMessage() . ' sur ' . $requetes[ $k ]['url'] );
					continue;
				}
				$statut = (int) $rep->status_code;
				if ( $suivre[ $k ] && $statut >= 400 ) {
					$out[ $k ] = new RuntimeException( "HTTP $statut sur " . $requetes[ $k ]['url'] );
					continue;
				}
				$cookies = method_exists( $rep->headers, 'getValues' ) ? (array) $rep->headers->getValues( 'set-cookie' ) : array();
				$out[ $k ] = array(
					'body'         => (string) $rep->body,
					'content_type' => (string) $rep->headers['content-type'],
					'status'       => $statut,
					'headers'      => array(
						'location'   => (string) $rep->headers['location'],
						'set-cookie' => $cookies,
					),
				);
			}
			return $out;
		};
	}

	public static function tracker() {
		return new Suivi_Tracker( new Suivi_Store_WP(), self::transport(), array( 'transportMultiple' => self::transport_multiple() ) );
	}

	// ---------------------------------------------------------------------
	// Verrou : deux passages ne doivent jamais tourner en même temps (WP-cron
	// peut se déclencher sur deux visites simultanées). add_option est
	// atomique : la base refuse un second INSERT sur le même nom.
	// ---------------------------------------------------------------------

	/**
	 * Lit une option en base, sans le cache de la requête. WordPress garde une
	 * option lue pendant toute la requête : une boucle qui attend le verrou ne
	 * verrait jamais un autre processus le libérer (bug trouvé en réel : une
	 * action de l'admin pendant un scan abandonnait au bout de 45 s).
	 */
	private static function option_fraiche( $nom ) {
		wp_cache_delete( $nom, 'options' );
		$absentes = wp_cache_get( 'notoptions', 'options' );
		if ( is_array( $absentes ) && isset( $absentes[ $nom ] ) ) {
			unset( $absentes[ $nom ] );
			wp_cache_set( 'notoptions', $absentes, 'options' );
		}
		return get_option( $nom, false );
	}

	public static function verrouiller() {
		$pose = self::option_fraiche( self::VERROU );
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
	 * Exécute $f sous le verrou des passages. Une modification depuis l'admin
	 * (ajout de joueur…) ne doit pas croiser un passage : celui-ci a chargé les
	 * documents au début et les réécrirait à la fin, effaçant la modification.
	 * Attend jusqu'à $attente_s qu'un passage en cours se termine.
	 */
	public static function sous_verrou( $f, $attente_s = 45 ) {
		$fin = time() + $attente_s;
		// Pendant un scan complet, les passages s'enchaînent presque sans pause :
		// sans cette priorité, l'action attendrait en vain.
		update_option( self::PRIORITE, time(), false );
		try {
			while ( ! self::verrouiller() ) {
				if ( time() >= $fin ) {
					throw new RuntimeException( 'Une mise à jour du suivi est en cours. Réessayez dans une minute.' );
				}
				sleep( 1 );
			}
		} finally {
			delete_option( self::PRIORITE );
		}
		try {
			return $f();
		} finally {
			self::deverrouiller();
		}
	}

	/**
	 * Un passage du moteur, sous verrou. Rend l'état, ou null si un autre
	 * passage était déjà en cours.
	 */
	public static function passage( $options = array() ) {
		$prio = self::option_fraiche( self::PRIORITE );
		if ( false !== $prio && time() - (int) $prio < 120 ) {
			return null; // une action de l'admin passe d'abord
		}
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
		// Passages plus longs pendant un scan complet (le cron garde 25 s) : moins
		// de temps perdu entre deux appels. Reste sous set_time_limit( 90 ).
		$st  = self::passage( array( 'budget_s' => 50 ) );
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
