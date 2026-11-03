<?php
/**
 * Accès à chess-results.com — portage de src/chessresults.js.
 *
 * Complément de la FFE : le serveur autrichien couvre le monde entier, donc les
 * tournois étrangers où nos licenciés jouent sans homologation française.
 *
 * Deux points de passage suffisent :
 *   1. la recherche de joueur par FIDE-ID, qui rend la liste de ses tournois
 *      avec, pour chacun, le numéro du tournoi (tnr) et son numéro de départ
 *      dans ce tournoi (snr) ;
 *   2. la fiche personnelle `tnrXXXX.aspx?art=9&snr=N` : score, classement,
 *      performance et détail ronde par ronde.
 *
 * Le site répartit les requêtes sur des nœuds (S1, S2, S3…) par redirection :
 * il faut suivre la redirection À LA MAIN et garder les cookies, sinon le
 * __VIEWSTATE de la recherche est rejeté.
 *
 * Transport injecté (comme Suivi_FFE) : fn(string $url, array $opts) avec
 * $opts = method, body, headers, redirection (= 0 : ne pas suivre). Il rend
 * body, content_type et, pour les redirections, status + headers
 * (location, set-cookie : chaîne ou liste). Sans `status`, la réponse vaut 200.
 *
 * Écart volontaire avec l'original : une réponse HTTP >= 400 lève une
 * exception (l'original analysait la page d'erreur et concluait « aucun
 * tournoi », mis en cache six heures).
 *
 * @package Suivi_Tournoi
 */

if ( ! defined( 'ABSPATH' ) && ! defined( 'SUIVI_CLI' ) ) {
	exit;
}

class Suivi_ChessResults {

	const BASE      = 'https://chess-results.com/';
	const UA        = 'suivi-tournoi/1.0 (outil personnel de suivi de joueurs)';
	const MAX_SAUTS = 5;

	/** @var callable|null */
	private $http;
	/** Délai minimal entre deux requêtes (ms) : le site est tenu par une seule personne. */
	private $delai_ms;
	/** @var float Instant de la dernière requête (microtime). */
	private $dernier = 0.0;

	/** @var callable|null Transport groupé (voir Suivi_FFE::__construct). */
	private $http_multi;

	/** Requêtes simultanées au plus (comme l'app d'origine : le site est tenu par une seule personne). */
	const PARALLELE = 2;

	public function __construct( $transport = null, $delai_ms = 350, $transport_multi = null ) {
		$this->http       = $transport;
		$this->delai_ms   = (int) $delai_ms;
		$this->http_multi = $transport_multi;
	}

	// ---------------------------------------------------------------------
	// Réseau
	// ---------------------------------------------------------------------

	private function patienter() {
		if ( $this->delai_ms > 0 && $this->dernier ) {
			$reste = $this->dernier + $this->delai_ms / 1000 - microtime( true );
			if ( $reste > 0 ) {
				usleep( (int) ( $reste * 1e6 ) );
			}
		}
		$this->dernier = microtime( true );
	}

	/** Résout une cible de redirection relative à l'URL courante. */
	public static function resoudre( $location, $base ) {
		if ( preg_match( '#^[a-z][a-z0-9+.-]*:#i', $location ) ) {
			return $location;
		}
		$p      = parse_url( $base );
		$origin = $p['scheme'] . '://' . $p['host'] . ( isset( $p['port'] ) ? ':' . $p['port'] : '' );
		if ( 0 === strpos( $location, '//' ) ) {
			return $p['scheme'] . ':' . $location;
		}
		if ( 0 === strpos( $location, '/' ) ) {
			return $origin . $location;
		}
		$chemin = isset( $p['path'] ) ? $p['path'] : '/';
		return $origin . substr( $chemin, 0, strrpos( $chemin, '/' ) + 1 ) . $location;
	}

	/**
	 * Requête suivant les redirections de nœud en conservant les cookies.
	 * Une redirection après POST se poursuit en GET.
	 *
	 * @return array url, statut, html, jar
	 */
	private function req( $url, $opts = array(), $jar = array() ) {
		if ( null === $this->http ) {
			throw new RuntimeException( 'Aucun transport HTTP injecté.' );
		}
		$cible = $url;
		$envoi = $opts;
		for ( $saut = 0; $saut <= self::MAX_SAUTS; $saut++ ) {
			$this->patienter();
			$entetes = array( 'User-Agent' => self::UA );
			if ( $jar ) {
				$paires = array();
				foreach ( $jar as $k => $v ) {
					$paires[] = "$k=$v";
				}
				$entetes['Cookie'] = implode( '; ', $paires );
			}
			$r = call_user_func(
				$this->http,
				$cible,
				array(
					'method'      => isset( $envoi['method'] ) ? $envoi['method'] : 'GET',
					'body'        => isset( $envoi['body'] ) ? $envoi['body'] : null,
					'headers'     => array_merge( $entetes, isset( $envoi['headers'] ) ? $envoi['headers'] : array() ),
					'redirection' => 0,
				)
			);
			$statut  = isset( $r['status'] ) ? (int) $r['status'] : 200;
			$headers = isset( $r['headers'] ) ? array_change_key_case( (array) $r['headers'], CASE_LOWER ) : array();
			foreach ( (array) ( isset( $headers['set-cookie'] ) ? $headers['set-cookie'] : array() ) as $c ) {
				$kv = explode( '=', explode( ';', $c )[0] );
				if ( '' !== trim( $kv[0] ) ) {
					$jar[ trim( $kv[0] ) ] = isset( $kv[1] ) ? $kv[1] : '';
				}
			}
			if ( $statut < 300 || $statut >= 400 ) {
				if ( $statut >= 400 ) {
					throw new RuntimeException( "HTTP $statut sur $cible" );
				}
				return array( 'url' => $cible, 'statut' => $statut, 'html' => (string) $r['body'], 'jar' => $jar );
			}
			if ( empty( $headers['location'] ) ) {
				throw new RuntimeException( "redirection sans destination sur $cible" );
			}
			$loc   = is_array( $headers['location'] ) ? $headers['location'][0] : $headers['location'];
			$cible = self::resoudre( $loc, $cible );
			$envoi = array();
		}
		throw new RuntimeException( 'trop de redirections' );
	}

	/**
	 * Plusieurs requêtes à la fois, chacune suivant ses redirections de nœud
	 * avec ses cookies. $items = [clé => ['url', 'opts', 'jar']].
	 * Rend [clé => ['url', 'statut', 'html', 'jar'] | Throwable].
	 * Le budget épuisé (exception du transport) remonte tel quel.
	 */
	private function req_multiple( $items ) {
		$out     = array();
		$encours = array();
		foreach ( $items as $k => $it ) {
			$encours[ $k ] = array(
				'url'  => $it['url'],
				'opts' => isset( $it['opts'] ) ? $it['opts'] : array(),
				'jar'  => isset( $it['jar'] ) ? $it['jar'] : array(),
			);
		}
		for ( $saut = 0; $encours && $saut <= self::MAX_SAUTS; $saut++ ) {
			$this->patienter();
			$req = array();
			foreach ( $encours as $k => $e ) {
				$entetes = array( 'User-Agent' => self::UA );
				if ( $e['jar'] ) {
					$paires = array();
					foreach ( $e['jar'] as $ck => $cv ) {
						$paires[] = "$ck=$cv";
					}
					$entetes['Cookie'] = implode( '; ', $paires );
				}
				$req[ $k ] = array(
					'url'  => $e['url'],
					'opts' => array(
						'method'      => isset( $e['opts']['method'] ) ? $e['opts']['method'] : 'GET',
						'body'        => isset( $e['opts']['body'] ) ? $e['opts']['body'] : null,
						'headers'     => array_merge( $entetes, isset( $e['opts']['headers'] ) ? $e['opts']['headers'] : array() ),
						'redirection' => 0,
					),
				);
			}
			if ( $this->http_multi ) {
				$reps = call_user_func( $this->http_multi, $req );
			} else {
				$reps = array();
				foreach ( $req as $k => $r ) {
					try {
						$reps[ $k ] = call_user_func( $this->http, $r['url'], $r['opts'] );
					} catch ( Suivi_Budget_Epuise $ex ) {
						throw $ex;
					} catch ( Exception $ex ) {
						$reps[ $k ] = $ex;
					}
				}
			}
			foreach ( $reps as $k => $r ) {
				if ( $r instanceof Throwable ) {
					$out[ $k ] = $r;
					unset( $encours[ $k ] );
					continue;
				}
				$statut  = isset( $r['status'] ) ? (int) $r['status'] : 200;
				$headers = isset( $r['headers'] ) ? array_change_key_case( (array) $r['headers'], CASE_LOWER ) : array();
				foreach ( (array) ( isset( $headers['set-cookie'] ) ? $headers['set-cookie'] : array() ) as $c ) {
					$kv = explode( '=', explode( ';', $c )[0] );
					if ( '' !== trim( $kv[0] ) ) {
						$encours[ $k ]['jar'][ trim( $kv[0] ) ] = isset( $kv[1] ) ? $kv[1] : '';
					}
				}
				if ( $statut < 300 || $statut >= 400 ) {
					$out[ $k ] = $statut >= 400
						? new RuntimeException( "HTTP $statut sur {$encours[ $k ]['url']}" )
						: array( 'url' => $encours[ $k ]['url'], 'statut' => $statut, 'html' => (string) $r['body'], 'jar' => $encours[ $k ]['jar'] );
					unset( $encours[ $k ] );
				} elseif ( empty( $headers['location'] ) ) {
					$out[ $k ] = new RuntimeException( "redirection sans destination sur {$encours[ $k ]['url']}" );
					unset( $encours[ $k ] );
				} else {
					$loc                   = is_array( $headers['location'] ) ? $headers['location'][0] : $headers['location'];
					$encours[ $k ]['url']  = self::resoudre( $loc, $encours[ $k ]['url'] );
					$encours[ $k ]['opts'] = array(); // une redirection après POST se poursuit en GET
				}
			}
		}
		foreach ( array_keys( $encours ) as $k ) {
			$out[ $k ] = new RuntimeException( 'trop de redirections' );
		}
		return $out;
	}

	/**
	 * Recherches de plusieurs joueurs, PARALLELE à la fois.
	 * $demandes = [clé => [fideId, du, au]] ; rend [clé => tournois | Throwable].
	 * Une clé absente n'a pas été traitée (budget du passage atteint).
	 */
	public function tournois_des_joueurs( $demandes ) {
		$out = array();
		foreach ( array_chunk( $demandes, self::PARALLELE, true ) as $lot ) {
			try {
				$pages = $this->req_multiple( array_map( function () { return array( 'url' => self::BASE . 'spielersuche.aspx?lan=1' ); }, $lot ) );
				$posts = array();
				foreach ( $pages as $k => $page ) {
					if ( $page instanceof Throwable ) {
						$out[ $k ] = $page;
						continue;
					}
					list( $fide, $du, $au ) = $lot[ $k ];
					$posts[ $k ] = array(
						'url'  => $page['url'],
						'jar'  => $page['jar'],
						'opts' => array(
							'method'  => 'POST',
							'headers' => array( 'Content-Type' => 'application/x-www-form-urlencoded' ),
							'body'    => self::corps_recherche( $page['html'], $fide, $du, $au ),
						),
					);
				}
				foreach ( $posts ? $this->req_multiple( $posts ) : array() as $k => $res ) {
					$out[ $k ] = $res instanceof Throwable ? $res : self::recherche_depuis_html( $res['html'] );
				}
			} catch ( Suivi_Budget_Epuise $e ) {
				break;
			}
		}
		return $out;
	}

	/** Fiches de plusieurs joueurs/tournois : $items = [clé => [tnr, snr]] ; rend [clé => fiche | null | Throwable]. */
	public function fiches_par_tournoi( $items ) {
		$out = array();
		foreach ( array_chunk( $items, self::PARALLELE, true ) as $lot ) {
			try {
				$req = array();
				foreach ( $lot as $k => $it ) {
					$req[ $k ] = array( 'url' => self::BASE . "tnr{$it[0]}.aspx?lan=1&art=9&snr={$it[1]}" );
				}
				foreach ( $this->req_multiple( $req ) as $k => $r ) {
					$out[ $k ] = $r instanceof Throwable ? $r : self::fiche_depuis_html( $r['html'], $lot[ $k ][0], $lot[ $k ][1] );
				}
			} catch ( Suivi_Budget_Epuise $e ) {
				break;
			}
		}
		return $out;
	}

	// ---------------------------------------------------------------------
	// Utilitaires de lecture (propres au site : nombres à virgule, entités)
	// ---------------------------------------------------------------------

	private static $entites = array(
		'nbsp'   => ' ',
		'amp'    => '&',
		'lt'     => '<',
		'gt'     => '>',
		'quot'   => '"',
		'apos'   => "'",
		'frac12' => '.5',
	);

	/** Les espaces au sens de JavaScript (\s, trim), espaces insécables compris. */
	const ESPACES = '[\t\n\x0B\f\r \x{A0}\x{1680}\x{2000}-\x{200A}\x{2028}\x{2029}\x{202F}\x{205F}\x{3000}\x{FEFF}]+';

	/** Caractère UTF-8 d'un point de code (sans mbstring). */
	private static function chr_utf8( $n ) {
		if ( $n < 0x80 ) {
			return chr( $n );
		}
		if ( $n < 0x800 ) {
			return chr( 0xC0 | ( $n >> 6 ) ) . chr( 0x80 | ( $n & 0x3F ) );
		}
		if ( $n < 0x10000 ) {
			return chr( 0xE0 | ( $n >> 12 ) ) . chr( 0x80 | ( ( $n >> 6 ) & 0x3F ) ) . chr( 0x80 | ( $n & 0x3F ) );
		}
		return chr( 0xF0 | ( $n >> 18 ) ) . chr( 0x80 | ( ( $n >> 12 ) & 0x3F ) ) . chr( 0x80 | ( ( $n >> 6 ) & 0x3F ) ) . chr( 0x80 | ( $n & 0x3F ) );
	}

	public static function texte( $html ) {
		$s = preg_replace( '/<[^>]*>/', ' ', (string) $html );
		$s = str_replace( '&frac12;', '.5', $s );
		$s = preg_replace_callback(
			'/&#(\d+);/',
			function ( $m ) {
				return self::chr_utf8( (int) $m[1] );
			},
			$s
		);
		$s = preg_replace_callback(
			'/&(\w+);/',
			function ( $m ) {
				return isset( self::$entites[ $m[1] ] ) ? self::$entites[ $m[1] ] : ' ';
			},
			$s
		);
		$t = preg_replace( '/' . self::ESPACES . '/u', ' ', $s );
		if ( null === $t ) {
			$t = preg_replace( '/\s+/', ' ', $s ); // UTF-8 invalide : repli ASCII
		}
		return trim( $t, ' ' );
	}

	/** Les nombres du site sont à virgule : "4,5" vaut 4.5. */
	public static function nombre( $s ) {
		if ( null === $s ) {
			return null;
		}
		$s = preg_replace( '/,/', '.', (string) $s, 1 );
		return preg_match( '/-?(?:\d+(?:\.\d+)?|\.\d+)/', $s, $m ) ? (float) $m[0] : null;
	}

	private static function cache_aspnet( $html, $nom ) {
		return preg_match( '/id="' . preg_quote( $nom, '/' ) . '"[^>]*value="([^"]*)"/', $html, $m ) ? $m[1] : '';
	}

	/** Longueur en caractères (comme String.length pour le texte courant). */
	private static function longueur( $s ) {
		$n = function_exists( 'iconv_strlen' ) ? @iconv_strlen( $s, 'UTF-8' ) : false;
		return false === $n ? strlen( $s ) : $n;
	}

	/** Cellules de premier niveau d'une ligne : ignore les tables imbriquées. */
	public static function cellules( $ligne ) {
		$out = array();
		$pos = 0;
		$len = strlen( $ligne );
		while ( preg_match( '/<t[dh][^>]*>/i', $ligne, $m, PREG_OFFSET_CAPTURE, $pos ) ) {
			$i          = $m[0][1] + strlen( $m[0][0] );
			$debut      = $i;
			$profondeur = 1;
			while ( $profondeur > 0 && $i < $len ) {
				if ( ! preg_match( '/<(\/?)t[dh][^>]*>/i', $ligne, $s, PREG_OFFSET_CAPTURE, $i ) ) {
					break;
				}
				$profondeur += '' !== $s[1][0] ? -1 : 1;
				$i           = $s[0][1] + strlen( $s[0][0] );
			}
			$out[] = preg_replace( '/<\/t[dh]>\s*$/i', '', substr( $ligne, $debut, $i - $debut ) );
			$pos   = $i;
		}
		return $out;
	}

	// ---------------------------------------------------------------------
	// Recherche des tournois d'un joueur par FIDE-ID
	// ---------------------------------------------------------------------

	/**
	 * Tournois où le joueur apparaît, dont la date de fin tombe dans la fenêtre.
	 * `$du` et `$au` au format AAAA-MM-JJ. Trois à quatre requêtes.
	 */
	public function tournois_du_joueur( $fide_id, $du, $au ) {
		$page  = $this->req( self::BASE . 'spielersuche.aspx?lan=1' );
		$res   = $this->req(
			$page['url'],
			array(
				'method'  => 'POST',
				'headers' => array( 'Content-Type' => 'application/x-www-form-urlencoded' ),
				'body'    => self::corps_recherche( $page['html'], $fide_id, $du, $au ),
			),
			$page['jar']
		);
		return self::recherche_depuis_html( $res['html'] );
	}

	/** Corps du formulaire de recherche par FIDE-ID (champs cachés ASP.NET de la page du formulaire). */
	private static function corps_recherche( $html, $fide_id, $du, $au ) {
		$page  = array( 'html' => $html );
		return http_build_query(
			array(
				'__VIEWSTATE'                  => self::cache_aspnet( $page['html'], '__VIEWSTATE' ),
				'__VIEWSTATEGENERATOR'         => self::cache_aspnet( $page['html'], '__VIEWSTATEGENERATOR' ),
				'__EVENTVALIDATION'            => self::cache_aspnet( $page['html'], '__EVENTVALIDATION' ),
				'ctl00$P1$txt_nachname'        => '',
				'ctl00$P1$txt_vorname'         => '',
				'ctl00$P1$txt_verein'          => '',
				'ctl00$P1$txt_ident'           => '',
				'ctl00$P1$txt_fideID'          => (string) $fide_id,
				'ctl00$P1$txt_von_tag'         => $du,
				'ctl00$P1$txt_bis_tag'         => $au,
				'ctl00$P1$txt_GJahr'           => '',
				'ctl00$P1$txt_min_elo'         => '',
				'ctl00$P1$txt_FED'             => '',
				'ctl00$P1$txt_Fed_tur'         => '',
				// Le <select> attend l'indice de l'option, pas « 250 ».
				'ctl00$P1$combo_anzahl_zeilen' => '1',
				'ctl00$P1$cb_suchen'           => 'Search',
			)
		);
	}

	public static function recherche_depuis_html( $html ) {
		$out = array();
		preg_match_all( '/<tr class="CRg[^"]*">([\s\S]*?)<\/tr>/', (string) $html, $lignes );
		foreach ( $lignes[1] as $ligne ) {
			if ( ! preg_match( '/tnr(\d+)\.aspx\?lan=1&amp;art=9&amp;snr=(\d+)/', $ligne, $lien ) ) {
				continue;
			}
			$c     = array_map( array( __CLASS__, 'texte' ), self::cellules( $ligne ) );
			$cel   = function ( $i ) use ( $c ) {
				return isset( $c[ $i ] ) ? $c[ $i ] : '';
			};
			$out[] = array(
				'source'    => 'chess-results',
				'tnr'       => $lien[1],
				'snr'       => (int) $lien[2],
				'nom'       => $cel( 5 ),
				'dateFin'   => str_replace( '/', '-', $cel( 6 ) ),
				'rang'      => isset( $c[7] ) ? self::nombre( $c[7] ) : null,
				'nbRondes'  => isset( $c[8] ) ? self::nombre( $c[8] ) : null,
				'nbJoueurs' => isset( $c[9] ) ? self::nombre( $c[9] ) : null,
				'fed'       => $cel( 4 ),
				'url'       => self::BASE . "tnr{$lien[1]}.aspx?lan=1&art=9&snr={$lien[2]}",
			);
		}
		return $out;
	}

	// ---------------------------------------------------------------------
	// Fiche d'un joueur dans un tournoi
	// ---------------------------------------------------------------------

	/** Score, classement, performance et parcours ronde par ronde (deux requêtes). */
	public function fiche_par_tournoi( $tnr, $snr ) {
		$r = $this->req( self::BASE . "tnr{$tnr}.aspx?lan=1&art=9&snr={$snr}" );
		return self::fiche_depuis_html( $r['html'], $tnr, $snr );
	}

	private static function resultat( $n ) {
		if ( null === $n ) {
			return null;
		}
		if ( 1.0 === $n ) {
			return 'gagne';
		}
		if ( 0.0 === $n ) {
			return 'perdu';
		}
		return 0.5 === $n ? 'nulle' : null;
	}

	/**
	 * La disposition des colonnes change d'un tournoi à l'autre (Andorre :
	 * Rd. Bo. SNo Name RtgI FED Pts. Res. ; Aix y ajoute Rtg et Club/City ; les
	 * tournois fermés suppriment Bo. en gardant une colonne sans intitulé).
	 * On s'appuie sur trois repères stables : le numéro de ronde en tête, le
	 * résultat (pastille de couleur Farbew/Farbes) et l'adversaire reconnu à son
	 * lien vers sa propre fiche. Deux familles de classes de lignes : CRg*, CRng*.
	 */
	public static function fiche_depuis_html( $html, $tnr, $snr ) {
		$html = (string) $html;
		if ( ! preg_match( '/Player info|Fide-ID/', $html ) ) {
			return null;
		}

		// Bloc d'identité : suite de paires libellé / valeur.
		$info = array();
		preg_match_all( '/<tr[^>]*>\s*<td class="CR">([^<]{2,30})<\/td>\s*<td class="CR">([\s\S]*?)<\/td>\s*<\/tr>/', $html, $paires, PREG_SET_ORDER );
		foreach ( $paires as $p ) {
			$info[ self::texte( $p[1] ) ] = self::texte( $p[2] );
		}
		$i = function ( $cle ) use ( $info ) {
			return isset( $info[ $cle ] ) && '' !== $info[ $cle ] ? $info[ $cle ] : null;
		};

		// En-tête de la table des rondes : la ligne CR*g1b qui commence par « Rd. ».
		$colonnes = array();
		preg_match_all( '/<tr class="CRn?g1b"[^>]*>([\s\S]*?)<\/tr>/', $html, $tetes );
		foreach ( $tetes[1] as $t ) {
			$noms = array_map(
				function ( $x ) {
					return preg_replace( '/\.$/', '', strtolower( self::texte( $x ) ) );
				},
				self::cellules( $t )
			);
			if ( isset( $noms[0] ) && 'rd' === $noms[0] ) {
				$colonnes = $noms;
				break;
			}
		}
		$i_col = function ( $nom ) use ( $colonnes ) {
			$k = array_search( $nom, $colonnes, true );
			return false === $k ? -1 : $k;
		};

		$rondes = array();
		preg_match_all( '/<tr class="CRn?g[12]"[^>]*>([\s\S]*?)<\/tr>/', $html, $lignes );
		foreach ( $lignes[1] as $ligne ) {
			$c = self::cellules( $ligne );
			$n = count( $c );
			if ( $n < 5 ) {
				continue;
			}
			$numero = self::nombre( self::texte( $c[0] ) );
			if ( null === $numero ) {
				continue;
			}
			$cell = function ( $k ) use ( $c ) {
				return ( $k >= 0 && isset( $c[ $k ] ) ) ? $c[ $k ] : '';
			};

			// Le résultat porte la pastille de couleur : le repère le plus fiable.
			$i_res = -1;
			foreach ( $c as $k => $x ) {
				if ( preg_match( '/Farbe[ws]/', $x ) ) {
					$i_res = $k;
					break;
				}
			}
			if ( $i_res < 0 ) {
				$i_res = ( $i_col( 'res' ) >= 0 && $n > $i_col( 'res' ) ) ? $i_col( 'res' ) : $n - 1;
			}
			$brut_res = $cell( $i_res );
			$couleur  = preg_match( '/Farbew/', $brut_res ) ? 'blancs' : ( preg_match( '/Farbes/', $brut_res ) ? 'noirs' : null );

			// Le nom : le lien vers la fiche de l'adversaire, à défaut la colonne
			// « name » de l'en-tête, puis la première cellule alphabétique.
			$ressemble_a_un_nom = function ( $k ) use ( $i_res, $cell ) {
				if ( $k < 1 || $k >= $i_res ) {
					return false;
				}
				$v = self::texte( $cell( $k ) );
				// Écarte les titres (GM, WGM), les codes fédération (GER) et les nombres.
				return self::longueur( $v ) >= 4
					&& preg_match( '/[a-zA-Z\x{E0}-\x{FF}\x{391}-\x{3C9}\x{400}-\x{4FF}]/u', $v )
					&& ! preg_match( '/^[A-Z]{2,3}$/', $v )
					&& null === self::nombre( $v );
			};
			$i_nom = -1;
			foreach ( $c as $k => $x ) {
				if ( preg_match( '/art=9[^"\']*snr=/', $x ) ) {
					$i_nom = $k;
					break;
				}
			}
			if ( $i_nom < 1 && $ressemble_a_un_nom( $i_col( 'name' ) ) ) {
				$i_nom = $i_col( 'name' );
			}
			if ( $i_nom < 1 ) {
				$i_nom = -1;
				for ( $k = 0; $k < $n; $k++ ) {
					if ( $ressemble_a_un_nom( $k ) ) {
						$i_nom = $k;
						break;
					}
				}
			}
			if ( $i_nom < 1 ) {
				continue;
			}

			// Entre le nom et le résultat : un ou deux classements, celui de
			// l'en-tête « rtgi » en priorité, sinon le premier nombre plausible.
			$rtgi = $i_col( 'rtgi' );
			$elo  = ( $rtgi > 0 && $rtgi < $n ) ? self::nombre( self::texte( $c[ $rtgi ] ) ) : null;
			if ( ! $elo ) {
				for ( $k = $i_nom + 1; $k < $i_res; $k++ ) {
					$v = self::texte( $c[ $k ] );
					if ( preg_match( '/^[A-Z]{3}$/', $v ) ) {
						break;
					}
					$x = self::nombre( $v );
					if ( null !== $x && $x >= 500 ) {
						$elo = $x;
						break;
					}
				}
			}

			$titre    = self::texte( $cell( $i_nom - 1 ) );
			$rondes[] = array(
				'numero'     => $numero,
				'echiquier'  => $i_col( 'bo' ) > 0 ? self::nombre( self::texte( $cell( $i_col( 'bo' ) ) ) ) : null,
				'couleur'    => $couleur,
				'resultat'   => self::resultat( self::nombre( self::texte( $brut_res ) ) ),
				'adversaire' => array(
					'nom'   => self::texte( $c[ $i_nom ] ),
					'titre' => '' !== $titre ? $titre : null,
					'elo'   => $elo ? $elo : null,
				),
			);
		}

		$h2      = preg_match( '/<h2[^>]*>([\s\S]*?)<\/h2>/', $html, $m ) ? self::texte( $m[1] ) : '';
		$maj     = preg_match( '/Last update ([^,<]*)/', $html, $m ) ? self::texte( $m[1] ) : '';
		$elo_int = self::nombre( $i( 'Rating international' ) );
		$perf    = self::nombre( $i( 'Performance rating' ) );

		return array(
			'source'        => 'chess-results',
			'tnr'           => (string) $tnr,
			'snr'           => (int) $snr,
			'tournoi'       => '' !== $h2 ? $h2 : (string) $i( 'Name' ),
			'misAJour'      => '' !== $maj ? $maj : null,
			'nom'           => (string) $i( 'Name' ),
			'titre'         => $i( 'Title' ),
			'numeroDepart'  => self::nombre( $i( 'Starting rank' ) ),
			'elo'           => null !== $elo_int ? $elo_int : self::nombre( $i( 'Rating' ) ),
			'performance'   => $perf ? $perf : null,
			'variationFide' => self::nombre( $i( 'FIDE rtg +/-' ) ),
			'points'        => self::nombre( $i( 'Points' ) ),
			'place'         => self::nombre( $i( 'Rank' ) ),
			'club'          => $i( 'Club/City' ),
			'fideId'        => $i( 'Fide-ID' ),
			'rondes'        => $rondes,
			'url'           => self::BASE . "tnr{$tnr}.aspx?lan=1&art=9&snr={$snr}",
			'urlTournoi'    => self::BASE . "tnr{$tnr}.aspx?lan=1",
		);
	}
}
