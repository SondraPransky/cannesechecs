<?php
/**
 * Couche d'accès au site de la FFE (echecs.asso.fr) — portage PHP de src/ffe.js.
 *
 * Le site est un ASP.NET WebForms. Les pages utiles sont en GET, sauf la
 * pagination des listes qui passe par un __doPostBack. Aucune authentification.
 *
 * Ce fichier sépare le PARSING (méthodes statiques pures, sans réseau, testables
 * contre des fixtures) de la RÉCUPÉRATION HTTP (méthode d'instance branchée sur
 * un transport injecté : wp_remote_get côté WordPress, fixtures côté CLI de test).
 *
 * @package Suivi_Tournoi
 */

if ( ! defined( 'ABSPATH' ) && ! defined( 'SUIVI_CLI' ) ) {
	// Hors WordPress et hors test CLI : refuser l'accès direct.
	exit;
}

class Suivi_FFE {

	const BASE = 'https://www.echecs.asso.fr/';
	const UA   = 'suivi-tournoi/1.0 (outil personnel de suivi de joueurs)';

	/** @var callable|null Transport HTTP : fn(string $url, array $opts): array{body:string,content_type:string} */
	private $http;

	/**
	 * @param callable|null $http Transport HTTP injecté. Null = pas de réseau
	 *                            (seules les méthodes de parsing sont utilisables).
	 */
	public function __construct( $http = null ) {
		$this->http = $http;
	}

	// ---------------------------------------------------------------------
	// Décodage
	// ---------------------------------------------------------------------

	/**
	 * Décode un corps brut selon le charset annoncé. Le site annonce utf-8,
	 * mais quelques vieux fichiers Papi sont en windows-1252. On suit l'en-tête
	 * et on retombe sur le 1252 si l'UTF-8 n'est pas valide.
	 */
	public static function decode( $raw, $content_type = '' ) {
		$charset = 'utf-8';
		if ( preg_match( '/charset=([\w-]+)/i', $content_type, $m ) ) {
			$charset = strtolower( $m[1] );
		}
		if ( ! preg_match( '/^utf-?8$/', $charset ) ) {
			$from = ( 'iso-8859-1' === $charset ) ? 'Windows-1252' : $charset;
			$out  = @iconv( $from, 'UTF-8//IGNORE', $raw );
			return false === $out ? $raw : $out;
		}
		// Charset utf-8 annoncé : valider, sinon retomber sur windows-1252.
		if ( preg_match( '//u', $raw ) ) {
			return $raw;
		}
		$out = @iconv( 'Windows-1252', 'UTF-8//IGNORE', $raw );
		return false === $out ? $raw : $out;
	}

	// ---------------------------------------------------------------------
	// Utilitaires de parsing HTML
	// ---------------------------------------------------------------------

	/** Enlève les balises, décode les entités, normalise les espaces. */
	public static function text( $html ) {
		if ( null === $html ) {
			return '';
		}
		$s = preg_replace( '#<br\s*/?>#i', ' ', (string) $html );
		$s = preg_replace( '#<[^>]*>#', '', $s );
		// Les demi-points d'échecs : &frac12; vaut 0,5 (et non 5).
		$s = str_replace( '&frac12;', '.5', $s );
		$s = html_entity_decode( $s, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		$s = preg_replace( '/\s+/u', ' ', $s );
		return trim( $s );
	}

	/** Découpe une ligne <tr> en cellules texte. */
	public static function cells( $row_html ) {
		preg_match_all( '#<td[^>]*>(.*?)</td>#is', (string) $row_html, $m );
		return array_map( array( __CLASS__, 'text' ), $m[1] );
	}

	/** Cellules brutes (HTML conservé), utile pour lire un href. */
	public static function raw_cells( $row_html ) {
		preg_match_all( '#<td[^>]*>(.*?)</td>#is', (string) $row_html, $m );
		return $m[1];
	}

	/**
	 * Convertit "2.5", "3", ".5", "" en float ou null.
	 * Le point initial compte : "&frac12;" devient ".5" et vaut 0,5.
	 */
	public static function num( $s ) {
		if ( null === $s || '' === $s ) {
			return null;
		}
		$s = str_replace( ',', '.', (string) $s );
		if ( preg_match( '/-?(?:\d+(?:\.\d+)?|\.\d+)/', $s, $m ) ) {
			return (float) $m[0];
		}
		return null;
	}

	/**
	 * Normalise un nom pour la comparaison : majuscules, sans accents,
	 * sans ponctuation. "René O'Neill-Dupont" -> "RENE ONEILL DUPONT".
	 */
	public static function norm_name( $s ) {
		$s = (string) $s;
		if ( '' === $s ) {
			return '';
		}
		// Repli d'accents déterministe (iconv //TRANSLIT n'est pas fiable sur
		// Windows : il rend "é" en "'e"). Table explicite UTF-8 -> ASCII.
		$s = strtr( $s, self::$accents );
		// strtoupper est octet-safe ici : il ne reste que de l'ASCII.
		$s = strtoupper( $s );
		$s = preg_replace( '/[^A-Z0-9]+/', ' ', $s );
		return trim( $s );
	}

	/** Correspondance accents -> ASCII pour norm_name (minuscules et majuscules). */
	private static $accents = array(
		'à' => 'a', 'â' => 'a', 'ä' => 'a', 'á' => 'a', 'ã' => 'a', 'å' => 'a',
		'ç' => 'c', 'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
		'î' => 'i', 'ï' => 'i', 'í' => 'i', 'ì' => 'i',
		'ô' => 'o', 'ö' => 'o', 'ó' => 'o', 'ò' => 'o', 'õ' => 'o', 'ø' => 'o',
		'û' => 'u', 'ü' => 'u', 'ù' => 'u', 'ú' => 'u',
		'ÿ' => 'y', 'ý' => 'y', 'ñ' => 'n', 'æ' => 'ae', 'œ' => 'oe', 'ß' => 'ss',
		'À' => 'A', 'Â' => 'A', 'Ä' => 'A', 'Á' => 'A', 'Ã' => 'A', 'Å' => 'A',
		'Ç' => 'C', 'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E',
		'Î' => 'I', 'Ï' => 'I', 'Í' => 'I', 'Ì' => 'I',
		'Ô' => 'O', 'Ö' => 'O', 'Ó' => 'O', 'Ò' => 'O', 'Õ' => 'O', 'Ø' => 'O',
		'Û' => 'U', 'Ü' => 'U', 'Ù' => 'U', 'Ú' => 'U',
		'Ÿ' => 'Y', 'Ý' => 'Y', 'Ñ' => 'N', 'Æ' => 'AE', 'Œ' => 'OE',
	);

	/** Lit un champ caché ASP.NET (__VIEWSTATE, etc.). */
	public static function hidden( $html, $name ) {
		if ( preg_match( '/id="' . preg_quote( $name, '/' ) . '"[^>]*value="([^"]*)"/', $html, $m ) ) {
			return $m[1];
		}
		return '';
	}

	// ---------------------------------------------------------------------
	// Annuaire des joueurs
	// ---------------------------------------------------------------------

	/**
	 * Lit les lignes de joueurs d'une page d'annuaire (même format partout :
	 * recherche nominative et liste d'un club).
	 *
	 * @return array[] codeFFE, id, nomComplet, affiliation, elo, eloEstime,
	 *                 rapide, blitz, cat, club, urlFiche.
	 */
	public static function parse_lignes_joueurs( $html ) {
		$out = array();
		preg_match_all( '#<tr class=liste_(?:clair|fonce)>(.*?)</tr>#is', (string) $html, $rows );
		foreach ( $rows[1] as $row ) {
			$c  = self::cells( $row );
			$rc = self::raw_cells( $row );
			if ( count( $c ) < 10 ) {
				continue;
			}
			$id = null;
			if ( isset( $rc[3] ) && preg_match( '/FicheJoueur\.aspx\?Id=(\d+)/', $rc[3], $mm ) ) {
				$id = $mm[1];
			}
			$out[] = array(
				'codeFFE'     => $c[0],
				'id'          => $id,
				'nomComplet'  => $c[1],
				'affiliation' => $c[2],
				'elo'         => self::num( $c[4] ),
				'eloEstime'   => ( 0 === preg_match( '/F/', $c[4] ) ),
				'rapide'      => self::num( $c[5] ),
				'blitz'       => self::num( $c[6] ),
				'cat'         => $c[7],
				'club'        => $c[9],
				'urlFiche'    => $id ? self::BASE . 'FicheJoueur.aspx?Id=' . $id : null,
			);
		}
		return $out;
	}

	// ---------------------------------------------------------------------
	// Récupération HTTP (nécessite un transport injecté)
	// ---------------------------------------------------------------------

	/** Récupère une page du site et la décode selon son en-tête. */
	public function fetch_page( $path, $options = array() ) {
		if ( null === $this->http ) {
			throw new RuntimeException( 'Aucun transport HTTP injecté.' );
		}
		$url = ( 0 === strpos( $path, 'http' ) ) ? $path : self::BASE . ltrim( $path, '/' );
		$res = call_user_func( $this->http, $url, $options );
		return self::decode( $res['body'], isset( $res['content_type'] ) ? $res['content_type'] : '' );
	}

	/**
	 * Tous les licenciés d'un club (ClubRef visible dans l'URL de sa page).
	 * Suit la pagination __doPostBack jusqu'à la dernière page.
	 *
	 * @return array[] joueurs (voir parse_lignes_joueurs) + clubRef.
	 */
	public function joueurs_du_club( $club_ref ) {
		$url  = 'ListeJoueurs.aspx?Action=JOUEURCLUBREF&JrTri=Elo&ClubRef=' . rawurlencode( (string) $club_ref );
		$html = $this->fetch_page( $url );

		$par_code = array();
		foreach ( self::parse_lignes_joueurs( $html ) as $j ) {
			$j['clubRef']          = (string) $club_ref;
			$par_code[ $j['codeFFE'] ] = $j;
		}

		$vues = array( 1 => true );
		for ( $p = 2; ; $p++ ) {
			preg_match_all( "/PagerHeader','(\\d+)'\\)/", $html, $dispo );
			$dispo = array_map( 'intval', $dispo[1] );
			if ( ! in_array( $p, $dispo, true ) || isset( $vues[ $p ] ) ) {
				break;
			}
			$vues[ $p ] = true;
			$body       = http_build_query(
				array(
					'__EVENTTARGET'        => 'ctl00$ContentPlaceHolderMain$PagerHeader',
					'__EVENTARGUMENT'      => (string) $p,
					'__VIEWSTATE'          => self::hidden( $html, '__VIEWSTATE' ),
					'__VIEWSTATEGENERATOR' => self::hidden( $html, '__VIEWSTATEGENERATOR' ),
					'__EVENTVALIDATION'    => self::hidden( $html, '__EVENTVALIDATION' ),
				)
			);
			$html = $this->fetch_page(
				$url,
				array(
					'method'  => 'POST',
					'headers' => array( 'Content-Type' => 'application/x-www-form-urlencoded' ),
					'body'    => $body,
				)
			);
			$lignes = self::parse_lignes_joueurs( $html );
			if ( ! $lignes ) {
				break;
			}
			foreach ( $lignes as $j ) {
				$j['clubRef']          = (string) $club_ref;
				$par_code[ $j['codeFFE'] ] = $j;
			}
		}

		return array_values( $par_code );
	}
}
