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
	/** @var callable|null fn(array $requetes): array — voir fetch_pages(). */
	private $http_multi;

	/** Requêtes simultanées au plus vers la FFE (comme l'app d'origine). */
	const PARALLELE = 4;

	/**
	 * @param callable|null $http       fn(string $url, array $opts): array (une requête)
	 * @param callable|null $http_multi fn(array $requetes): array — $requetes = [clé => ['url', 'opts']],
	 *                                  rend [clé => réponse | Throwable] (requêtes envoyées ensemble).
	 *                                  Sans lui, les lectures groupées se font une par une.
	 */
	public function __construct( $http = null, $http_multi = null ) {
		$this->http       = $http;
		$this->http_multi = $http_multi;
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
	// Liste des tournois d'un mois
	// ---------------------------------------------------------------------

	private static $mois = array(
		'janv' => 1, 'janvier' => 1, 'fevr' => 2, 'fevrier' => 2, 'mars' => 3, 'avril' => 4,
		'avr' => 4, 'mai' => 5, 'juin' => 6, 'juil' => 7, 'juillet' => 7, 'aout' => 8,
		'sept' => 9, 'septembre' => 9, 'oct' => 10, 'octobre' => 10, 'nov' => 11,
		'novembre' => 11, 'dec' => 12, 'decembre' => 12,
	);

	/** "févr." -> 2, "octobre" -> 10, inconnu -> null. */
	public static function mois_to_num( $s ) {
		$k = trim( str_replace( '.', '', strtolower( self::norm_name( $s ) ) ) );
		if ( isset( self::$mois[ $k ] ) ) {
			return self::$mois[ $k ];
		}
		$k4 = substr( $k, 0, 4 );
		return isset( self::$mois[ $k4 ] ) ? self::$mois[ $k4 ] : null;
	}

	/**
	 * Extrait les tournois d'une page de liste (une page de pagination).
	 * Les en-têtes de rupture ("juillet 2026") donnent mois/année des lignes suivantes.
	 */
	public static function parse_liste_page( $html ) {
		$out        = array();
		$cur_month  = null;
		$cur_year   = null;
		preg_match_all( '#<tr[^>]*>(.*?)</tr>#is', (string) $html, $rows );
		foreach ( $rows[1] as $row ) {
			if ( preg_match( '/class="liste_titre"[^>]*>([^<]+)</', $row, $rup ) ) {
				$parts     = preg_split( '/\s+/', self::text( $rup[1] ) );
				$cur_month = self::mois_to_num( $parts[0] );
				$cur_year  = isset( $parts[1] ) ? ( (int) $parts[1] ?: null ) : null;
				continue;
			}
			if ( ! preg_match( '/FicheTournoi\.aspx\?Ref=(\d+)/', $row, $ref ) ) {
				continue;
			}
			$c = self::cells( $row );
			if ( count( $c ) < 6 ) {
				continue;
			}
			$day     = (int) $c[4];
			$mo_cell = self::mois_to_num( preg_replace( '/[\d\s]/', '', $c[4] ) );
			$mois    = $mo_cell ? $mo_cell : $cur_month;
			// Une ligne de décembre sous un en-tête de janvier appartient à l'année -1.
			$annee = $cur_year;
			if ( $mo_cell && $cur_month && $mo_cell !== $cur_month ) {
				if ( 1 === $cur_month && 12 === $mo_cell ) {
					$annee = $cur_year - 1;
				} elseif ( 12 === $cur_month && 1 === $mo_cell ) {
					$annee = $cur_year + 1;
				}
			}
			$out[] = array(
				'ref'          => $ref[1],
				'ville'        => $c[1],
				'dept'         => $c[2],
				'nom'          => $c[3],
				'dateDebut'    => ( $mois && $day && $annee ) ? sprintf( '%04d-%02d-%02d', $annee, $mois, $day ) : null,
				'homologation' => isset( $c[5] ) ? $c[5] : '',
			);
		}
		return $out;
	}

	// ---------------------------------------------------------------------
	// Fiche d'un tournoi
	// ---------------------------------------------------------------------

	private static function label( $html, $id ) {
		if ( preg_match( '#id="ctl00_ContentPlaceHolderMain_' . preg_quote( $id, '#' ) . '"[^>]*>(.*?)</span>#is', $html, $m ) ) {
			return self::text( $m[1] );
		}
		return '';
	}

	/** "samedi 12 octobre 2026" -> "2026-10-12". */
	public static function parse_date_fr( $s ) {
		$re = '/(?:lundi|mardi|mercredi|jeudi|vendredi|samedi|dimanche)?\s*(\d{1,2})\s+([a-zA-Z\x{00C0}-\x{00FF}.]+)\s+(\d{4})/iu';
		if ( ! preg_match( $re, (string) $s, $m ) ) {
			return null;
		}
		$mo = self::mois_to_num( $m[2] );
		return $mo ? sprintf( '%04d-%02d-%02d', $m[3], $mo, $m[1] ) : null;
	}

	/** Métadonnées d'un tournoi depuis le HTML de sa fiche. */
	public static function parse_fiche( $html, $ref ) {
		$parts = preg_split( '/\s+-\s+/', self::label( $html, 'LabelDates' ) );
		preg_match_all( '/Resultats\.aspx\?URL=[^"\'&]*&(?:amp;)?Action=(\w+)/', $html, $acts );
		return array(
			'ref'          => (string) $ref,
			'nom'          => self::label( $html, 'LabelNom' ),
			'lieu'         => self::label( $html, 'LabelLieu' ),
			'dateDebut'    => self::parse_date_fr( isset( $parts[0] ) ? $parts[0] : '' ),
			'dateFin'      => self::parse_date_fr( isset( $parts[1] ) ? $parts[1] : ( isset( $parts[0] ) ? $parts[0] : '' ) ),
			'nbRondes'     => self::num( self::label( $html, 'LabelNbrRondes' ) ),
			'cadence'      => self::label( $html, 'LabelCadence' ),
			'appariements' => self::label( $html, 'LabelAppariements' ),
			'arbitre'      => self::label( $html, 'LabelArbitre' ),
			'organisateur' => self::label( $html, 'LabelOrganisateur' ),
			// L'organisateur choisit ce qu'il publie : seules les vues disponibles.
			'actions'      => array_values( array_unique( $acts[1] ) ),
			'urlFiche'     => self::BASE . 'FicheTournoi.aspx?Ref=' . $ref,
		);
	}

	// ---------------------------------------------------------------------
	// Pages de résultats (fichiers Papi)
	// ---------------------------------------------------------------------

	public static function url_resultats( $ref, $action ) {
		return self::BASE . 'Resultats.aspx?URL=Tournois/Id/' . $ref . '/' . $ref . '&Action=' . $action;
	}

	/** Vrai si la page Papi signale un fichier absent. */
	public static function fichier_absent( $html ) {
		return 1 === preg_match( "/le fichier n'existe pas/i", (string) $html );
	}

	/**
	 * Index des colonnes d'un tableau Papi, lus dans sa ligne d'en-tête.
	 * Indispensable : des colonnes de départage (Tr., Cu., Perf) s'ajoutent selon
	 * le tournoi ; compter depuis la fin donnerait de faux résultats.
	 */
	private static function colonnes( $html, $classe ) {
		if ( ! preg_match( '#<tr class=' . preg_quote( $classe, '#' ) . '>(.*?)</tr>#is', $html, $m ) ) {
			return null;
		}
		$index = array();
		foreach ( self::cells( $m[1] ) as $i => $nom ) {
			$k = strtolower( preg_replace( '/\s+/', '', $nom ) );
			$k = preg_replace( '/\.$/', '', $k );
			if ( preg_match( '/^r\d+$/', $k ) ) {
				$index['rondes'][] = $i;
			} elseif ( '' !== $k ) {
				$index[ $k ] = $i;
			}
		}
		return $index;
	}

	/** Premier index de colonne existant parmi $keys, sinon $defaut. */
	private static function col( $col, $keys, $defaut ) {
		foreach ( (array) $keys as $k ) {
			if ( isset( $col[ $k ] ) ) {
				return $col[ $k ];
			}
		}
		return $defaut;
	}

	/** Valeur d'une cellule par index (null si absente). */
	private static function at( $c, $i ) {
		return ( null !== $i && isset( $c[ $i ] ) ) ? $c[ $i ] : null;
	}

	/** Titre de la page Papi + numéro de ronde éventuel ("... après la ronde 7"). */
	public static function parse_entete( $html ) {
		$titre = '';
		if ( preg_match( '#<p class=papi_titre>(.*?)</p>#is', $html, $m )
			|| preg_match( '#class=papi_titre>.*?<td[^>]*>(.*?)</td>#is', $html, $m ) ) {
			$titre = self::text( $m[1] );
		}
		$ronde = preg_match( '/ronde\s+(\d+)/i', $titre, $r ) ? (int) $r[1] : null;
		return array( 'titre' => $titre, 'ronde' => $ronde );
	}

	/** Liste des participants (Action=Ls), disponible dès la publication, avant la ronde 1. */
	public static function participants_depuis_html( $html ) {
		$joueurs = array();
		preg_match_all( '#<tr class=papi_liste_[fc]>(.*?)</tr>#is', $html, $rows );
		foreach ( $rows[1] as $row ) {
			$c = self::cells( $row );
			if ( count( $c ) < 8 ) {
				continue;
			}
			$joueurs[] = array(
				'nr'      => self::num( $c[0] ),
				'nom'     => $c[2],
				'elo'     => self::num( $c[3] ),
				'eloType' => preg_match( '/F/', $c[3] ) ? 'F' : ( preg_match( '/N/', $c[3] ) ? 'N' : 'E' ),
				'cat'     => $c[4],
				'ligue'   => $c[6],
				'club'    => $c[7],
			);
		}
		return self::parse_entete( $html ) + array( 'joueurs' => $joueurs );
	}

	/** Classement (Action=Cl) : place, points, club. */
	public static function classement_depuis_html( $html ) {
		$col     = self::colonnes( $html, 'papi_liste_t' );
		$col     = $col ? $col : array();
		$joueurs = array();
		preg_match_all( '#<tr class=papi_liste_[fc]>(.*?)</tr>#is', $html, $rows );
		foreach ( $rows[1] as $row ) {
			$c = self::cells( $row );
			if ( count( $c ) < 9 ) {
				continue;
			}
			$joueurs[] = array(
				'place'  => self::num( self::at( $c, self::col( $col, 'pl', 0 ) ) ),
				'nom'    => self::at( $c, self::col( $col, 'nom', 2 ) ),
				'elo'    => self::num( self::at( $c, self::col( $col, array( 'elo', 'rapide', 'blitz' ), 3 ) ) ),
				'cat'    => self::at( $c, self::col( $col, 'cat', 4 ) ),
				'ligue'  => self::at( $c, self::col( $col, 'ligue', 6 ) ),
				'club'   => self::at( $c, self::col( $col, 'club', 7 ) ),
				'points' => self::num( self::at( $c, self::col( $col, 'pts', 8 ) ) ),
			);
		}
		return self::parse_entete( $html ) + array( 'joueurs' => $joueurs );
	}

	/**
	 * Grille américaine (Action=Ga) : le détail ronde par ronde de chaque joueur.
	 * Chaque nom porte une sous-table dépliable qu'on retire avant de découper.
	 */
	public static function grille_depuis_html( $raw ) {
		$html = preg_replace( '#<div class=data.*?</table>\s*</div>#is', '', (string) $raw );
		$col  = self::colonnes( $html, 'papi_small_t' );
		if ( empty( $col['rondes'] ) ) {
			return self::parse_entete( $html ) + array( 'joueurs' => array() );
		}
		$joueurs = array();
		preg_match_all( '#<tr class=papi_small_[fc]>(.*?)</tr>#is', $html, $rows );
		foreach ( $rows[1] as $row ) {
			$c = self::cells( $row );
			if ( count( $c ) < 9 ) {
				continue;
			}
			$rondes = array();
			foreach ( $col['rondes'] as $i ) {
				$s = trim( isset( $c[ $i ] ) ? $c[ $i ] : '' );
				if ( '' === $s ) {
					$rondes[] = array( 'resultat' => null, 'adversaire' => null, 'couleur' => null );
					continue;
				}
				if ( ! preg_match( '/^([+\-=])\s*(\d+)?\s*([BN])?/', $s, $mm ) ) {
					$rondes[] = array( 'resultat' => $s, 'adversaire' => null, 'couleur' => null );
					continue;
				}
				$rondes[] = array(
					'resultat'   => '+' === $mm[1] ? 'gagne' : ( '-' === $mm[1] ? 'perdu' : 'nulle' ),
					'adversaire' => ( isset( $mm[2] ) && '' !== $mm[2] ) ? (int) $mm[2] : null,
					'couleur'    => isset( $mm[3] ) ? ( 'B' === $mm[3] ? 'blancs' : ( 'N' === $mm[3] ? 'noirs' : null ) ) : null,
				);
			}
			$joueurs[] = array(
				'place'     => self::num( self::at( $c, self::col( $col, 'pl', 0 ) ) ),
				'nom'       => self::at( $c, self::col( $col, 'nom', 2 ) ),
				'elo'       => self::num( self::at( $c, self::col( $col, array( 'elo', 'rapide', 'blitz' ), 3 ) ) ),
				'cat'       => self::at( $c, self::col( $col, 'cat', 4 ) ),
				'ligue'     => self::at( $c, self::col( $col, 'ligue', 6 ) ),
				'rondes'    => $rondes,
				'points'    => self::num( self::at( $c, self::col( $col, 'pts', null ) ) ),
				// Certaines grilles publient déjà le départage et la performance.
				'departage' => isset( $col['tr'] ) ? self::num( self::at( $c, $col['tr'] ) ) : null,
				'perfFFE'   => isset( $col['perf'] ) ? self::num( self::at( $c, $col['perf'] ) ) : null,
			);
		}
		return self::parse_entete( $html ) + array( 'joueurs' => $joueurs );
	}

	/**
	 * Grille Berger (Action=Berger) : table croisée des tournois toutes rondes.
	 * Particularités : noms écrits "Prénom NOM" (inverse du reste du site) et
	 * <tr> non fermés.
	 */
	public static function berger_depuis_html( $html ) {
		$lignes         = array_slice( preg_split( '/<tr[^>]*>/i', (string) $html ), 1 );
		$joueurs        = array();
		$nb_adversaires = 0;

		foreach ( $lignes as $ligne ) {
			preg_match_all( '#<td[^>]*>(.*?)</td>#is', $ligne, $bruts );
			$c = array_map( array( __CLASS__, 'text' ), $bruts[1] );
			$n = count( $c );
			if ( $n < 6 ) {
				continue;
			}
			// Ligne d'en-tête : numérote les colonnes et donne le nombre de joueurs.
			if ( preg_match( '/^Pts$/i', $c[ $n - 2 ] ) || preg_match( '/Moyenne/i', $c[1] ) ) {
				$nb_adversaires = $n - 6;
				continue;
			}
			$nr = self::num( $c[0] );
			if ( null === $nr || '' === $c[1] ) {
				continue;
			}
			list( $nom, $titre ) = self::nom_berger( $c[1] );

			$resultats = array();
			for ( $i = 0; $i < $nb_adversaires; $i++ ) {
				$brut = isset( $bruts[0][ 4 + $i ] ) ? $bruts[0][ 4 + $i ] : '';
				if ( preg_match( '/bgcolor=#000000/i', $brut ) ) {
					continue; // case diagonale
				}
				$v = isset( $c[ 4 + $i ] ) ? $c[ 4 + $i ] : '';
				if ( '' === $v ) {
					continue;
				}
				$resultats[] = array(
					'adversaireNr' => $i + 1,
					'resultat'     => '1' === $v ? 'gagne' : ( '0' === $v ? 'perdu' : 'nulle' ),
				);
			}

			$joueurs[] = array(
				'nr'        => $nr,
				'place'     => $nr,
				'nom'       => $nom,
				'titre'     => $titre,
				'elo'       => self::num( $c[3] ),
				'resultats' => $resultats,
				'points'    => self::num( $c[ $n - 2 ] ),
				'departage' => self::num( $c[ $n - 1 ] ),
			);
		}

		// Résout les numéros d'adversaire en noms une fois tout le monde connu.
		$par_nr = array();
		foreach ( $joueurs as $j ) {
			$par_nr[ (string) $j['nr'] ] = $j;
		}
		foreach ( $joueurs as &$j ) {
			foreach ( $j['resultats'] as &$r ) {
				$adv             = isset( $par_nr[ (string) (float) $r['adversaireNr'] ] ) ? $par_nr[ (string) (float) $r['adversaireNr'] ] : null;
				$r['adversaire'] = $adv ? array( 'nom' => $adv['nom'], 'elo' => $adv['elo'] ) : null;
			}
			unset( $r );
		}
		unset( $j );

		return self::parse_entete( $html ) + array( 'joueurs' => $joueurs );
	}

	/**
	 * Remet un nom de grille Berger au format du reste du site.
	 * "Kenzo HILDWEIN" -> "HILDWEIN Kenzo" ; "Jean-Marie MARTINE" -> "MARTINE Jean-Marie" ;
	 * "g Eric PRIE" -> "PRIE Eric", titre "g" (Papi préfixe les titrés : g, m, f…).
	 *
	 * Écart volontaire avec src/ffe.js, qui prenait le 1er mot pour le prénom et
	 * produisait "Eric PRIE g" pour un titré — nom alors introuvable par le suivi.
	 *
	 * @return array [ string $nom, string|null $titre ]
	 */
	public static function nom_berger( $brut ) {
		$mots  = preg_split( '/\s+/', trim( (string) $brut ) );
		$titre = null;
		if ( count( $mots ) > 1 && preg_match( '/^[a-z]{1,3}$/', $mots[0] ) ) {
			$titre = array_shift( $mots );
		}
		$nom    = array();
		$prenom = array();
		foreach ( $mots as $m ) {
			// Mot du nom de famille : des lettres, aucune minuscule (accents compris).
			if ( preg_match( '/\p{L}/u', $m ) && ! preg_match( '/\p{Ll}/u', $m ) ) {
				$nom[] = $m;
			} else {
				$prenom[] = $m;
			}
		}
		if ( ! $nom || ! $prenom ) {
			// Pas de NOM repérable : ancienne règle (1er mot = prénom).
			$out = count( $mots ) > 1 ? implode( ' ', array_slice( $mots, 1 ) ) . ' ' . $mots[0] : implode( ' ', $mots );
			return array( $out, $titre );
		}
		return array( implode( ' ', $nom ) . ' ' . implode( ' ', $prenom ), $titre );
	}

	/**
	 * Appariements / résultats d'une ronde (Action=01..NN). Titre "Appariements
	 * de la ronde N" tant que rien n'est saisi, puis "Résultats de la ronde N".
	 * Partie non terminée : case résultat vide ou "X - X".
	 */
	public static function ronde_depuis_html( $html, $n ) {
		$parties = array();
		preg_match_all( '#<tr class=papi_liste_[fc]>(.*?)</tr>#is', $html, $rows );
		foreach ( $rows[1] as $row ) {
			$c = self::cells( $row );
			if ( count( $c ) < 8 ) {
				continue;
			}
			$res       = trim( $c[4] );
			$parties[] = array(
				'echiquier' => self::num( $c[0] ),
				'blancs'    => array( 'nom' => $c[2], 'elo' => self::num( $c[3] ), 'pointsAvant' => self::num( $c[1] ) ),
				'noirs'     => array( 'nom' => $c[5], 'elo' => self::num( $c[6] ), 'pointsAvant' => self::num( $c[7] ) ),
				'resultat'  => '' !== $res ? $res : null,
				'enCours'   => '' === $res || 1 === preg_match( '/^X\s*-\s*X$/i', $res ),
			);
		}
		$entete = self::parse_entete( $html );
		return $entete + array(
			'numero'          => (int) $n,
			'appariementSeul' => 1 === preg_match( '/appariements/i', $entete['titre'] ),
			'parties'         => $parties,
		);
	}

	/** ID FIDE d'un licencié, lu sur sa fiche FFE (clé pour chess-results). */
	public static function fide_id_depuis_html( $html ) {
		return preg_match( '#ratings\.fide\.com/profile/(\d+)#', (string) $html, $m ) ? $m[1] : null;
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
	 * Plusieurs pages, par lots de PARALLELE envoyés ensemble.
	 * Rend [clé => html décodé | Exception] ; une clé ABSENTE n'a pas été lue
	 * (budget du passage atteint : l'appelant la lira au passage suivant).
	 */
	public function fetch_pages( $paths ) {
		$out = array();
		foreach ( array_chunk( $paths, self::PARALLELE, true ) as $lot ) {
			try {
				if ( $this->http_multi ) {
					$req = array();
					foreach ( $lot as $k => $path ) {
						$req[ $k ] = array( 'url' => ( 0 === strpos( $path, 'http' ) ) ? $path : self::BASE . ltrim( $path, '/' ), 'opts' => array() );
					}
					foreach ( call_user_func( $this->http_multi, $req ) as $k => $r ) {
						$out[ $k ] = $r instanceof Throwable ? $r : self::decode( $r['body'], isset( $r['content_type'] ) ? $r['content_type'] : '' );
					}
				} else {
					foreach ( $lot as $k => $path ) {
						try {
							$out[ $k ] = $this->fetch_page( $path );
						} catch ( Suivi_Budget_Epuise $e ) {
							throw $e;
						} catch ( Exception $e ) {
							$out[ $k ] = $e;
						}
					}
				}
			} catch ( Suivi_Budget_Epuise $e ) {
				break; // le reste au prochain passage
			}
		}
		return $out;
	}

	/** Fiches de plusieurs tournois : [ref => fiche | Exception] (refs absentes = non lues). */
	public function fiches_tournois( $refs ) {
		$paths = array();
		foreach ( $refs as $ref ) {
			$paths[ (string) $ref ] = 'FicheTournoi.aspx?Ref=' . rawurlencode( (string) $ref );
		}
		$out = array();
		foreach ( $this->fetch_pages( $paths ) as $ref => $html ) {
			$out[ $ref ] = $html instanceof Throwable ? $html : self::parse_fiche( $html, (string) $ref );
		}
		return $out;
	}

	/** Participants de plusieurs tournois : [ref => participants | null (pas de liste) | Exception]. */
	public function participants_multiples( $refs ) {
		$paths = array();
		foreach ( $refs as $ref ) {
			$paths[ (string) $ref ] = 'Resultats.aspx?URL=Tournois/Id/' . $ref . '/' . $ref . '&Action=Ls';
		}
		$out = array();
		foreach ( $this->fetch_pages( $paths ) as $ref => $html ) {
			$out[ $ref ] = $html instanceof Throwable ? $html : ( self::fichier_absent( $html ) ? null : self::participants_depuis_html( $html ) );
		}
		return $out;
	}

	/**
	 * Tous les licenciés d'un club (ClubRef visible dans l'URL de sa page).
	 * Suit la pagination __doPostBack jusqu'à la dernière page.
	 *
	 * @return array[] joueurs (voir parse_lignes_joueurs) + clubRef.
	 */
	public function joueurs_du_club( $club_ref, &$reprise = null ) {
		$url   = 'ListeJoueurs.aspx?Action=JOUEURCLUBREF&JrTri=Elo&ClubRef=' . rawurlencode( (string) $club_ref );
		$pages = function ( $html ) {
			preg_match_all( "/PagerHeader','(\\d+)'\\)/", $html, $m );
			return array_map( 'intval', $m[1] );
		};
		$ajouter = function ( $lignes ) use ( &$reprise, $club_ref ) {
			foreach ( $lignes as $j ) {
				$j['clubRef']                         = (string) $club_ref;
				$reprise['par_code'][ $j['codeFFE'] ] = $j;
			}
		};

		// Reprenable comme la liste des mois : ~10 pages pour un gros club, plus
		// qu'un passage quand la FFE est lente. $reprise garde les joueurs lus,
		// la dernière page et ses champs de pagination.
		if ( ! is_array( $reprise ) || empty( $reprise['champs'] ) ) {
			$html    = $this->fetch_page( $url );
			$reprise = array( 'par_code' => array(), 'page' => 1, 'champs' => self::champs_postback( $html ), 'dispo' => $pages( $html ) );
			$ajouter( self::parse_lignes_joueurs( $html ) );
		}
		// La pagination n'annonce que les pages voisines : on avance tant que la
		// page suivante figure parmi celles proposées par la dernière lue.
		for ( $p = $reprise['page'] + 1; in_array( $p, $reprise['dispo'], true ); $p++ ) {
			$html   = $this->postback_page( $url, $reprise['champs'], $p );
			$lignes = self::parse_lignes_joueurs( $html );
			if ( ! $lignes ) {
				break;
			}
			$ajouter( $lignes );
			$reprise['page']   = $p;
			$reprise['champs'] = self::champs_postback( $html );
			$reprise['dispo']  = $pages( $html );
		}

		return array_values( $reprise['par_code'] );
	}

	/** Page N d'une liste paginée ASP.NET (rejoue le __doPostBack du pager). */
	/** Champs cachés ASP.NET d'une page, nécessaires pour demander la suivante. */
	private static function champs_postback( $html ) {
		return array(
			'__VIEWSTATE'          => self::hidden( $html, '__VIEWSTATE' ),
			'__VIEWSTATEGENERATOR' => self::hidden( $html, '__VIEWSTATEGENERATOR' ),
			'__EVENTVALIDATION'    => self::hidden( $html, '__EVENTVALIDATION' ),
		);
	}

	private function postback_page( $url, $champs, $page ) {
		$body = http_build_query(
			array(
				'__EVENTTARGET'   => 'ctl00$ContentPlaceHolderMain$PagerHeader',
				'__EVENTARGUMENT' => (string) $page,
			) + $champs
		);
		return $this->fetch_page(
			$url,
			array(
				'method'  => 'POST',
				'headers' => array( 'Content-Type' => 'application/x-www-form-urlencoded' ),
				'body'    => $body,
			)
		);
	}

	/**
	 * Tous les tournois avec résultats publiés pour un mois donné.
	 * Suit la pagination jusqu'à la dernière page annoncée par la première.
	 *
	 * Reprenable : un mois chargé compte une vingtaine de pages (~25 s), plus
	 * qu'un passage du moteur sous WordPress. `$reprise` garde les tournois déjà
	 * lus, la dernière page lue et ses champs de pagination ; mise à jour après
	 * chaque page, elle survit à une interruption (exception) et permet au
	 * passage suivant de continuer au lieu de recommencer à la page 1.
	 */
	public function liste_tournois_mois( $mois, $annee, &$reprise = null ) {
		$url = 'ListeTournois.aspx?Action=RES&Mois=' . (int) $mois . '&Annee=' . (int) $annee;
		if ( ! is_array( $reprise ) || empty( $reprise['champs'] ) ) {
			$html = $this->fetch_page( $url );
			preg_match_all( "/PagerHeader','(\\d+)'\\)/", $html, $pages );
			$vus = array();
			foreach ( self::parse_liste_page( $html ) as $t ) {
				$vus[ $t['ref'] ] = $t;
			}
			$reprise = array(
				'vus'    => $vus,
				'page'   => 1,
				'max'    => $pages[1] ? max( array_map( 'intval', $pages[1] ) ) : 1,
				'champs' => self::champs_postback( $html ),
			);
		}
		for ( $p = $reprise['page'] + 1; $p <= $reprise['max']; $p++ ) {
			$html = $this->postback_page( $url, $reprise['champs'], $p );
			foreach ( self::parse_liste_page( $html ) as $t ) {
				$reprise['vus'][ $t['ref'] ] = $t;
			}
			$reprise['page']   = $p;
			$reprise['champs'] = self::champs_postback( $html );
		}
		return array_values( $reprise['vus'] );
	}

	/** Métadonnées d'un tournoi : dates, rondes, cadence, lieu, vues publiées. */
	public function fiche_tournoi( $ref ) {
		return self::parse_fiche( $this->fetch_page( 'FicheTournoi.aspx?Ref=' . rawurlencode( (string) $ref ) ), $ref );
	}

	/** HTML d'une page de résultats Papi, ou null si le fichier n'existe pas. */
	private function resultats( $ref, $action ) {
		$html = $this->fetch_page( 'Resultats.aspx?URL=Tournois/Id/' . $ref . '/' . $ref . '&Action=' . $action );
		return self::fichier_absent( $html ) ? null : $html;
	}

	public function participants( $ref ) {
		$h = $this->resultats( $ref, 'Ls' );
		return $h ? self::participants_depuis_html( $h ) : null;
	}

	public function classement( $ref ) {
		$h = $this->resultats( $ref, 'Cl' );
		return $h ? self::classement_depuis_html( $h ) : null;
	}

	public function grille( $ref ) {
		$h = $this->resultats( $ref, 'Ga' );
		return $h ? self::grille_depuis_html( $h ) : null;
	}

	public function grille_berger( $ref ) {
		$h = $this->resultats( $ref, 'Berger' );
		return $h ? self::berger_depuis_html( $h ) : null;
	}

	public function ronde( $ref, $n ) {
		$h = $this->resultats( $ref, sprintf( '%02d', (int) $n ) );
		return $h ? self::ronde_depuis_html( $h, $n ) : null;
	}

	/**
	 * Recherche un membre FFE par nom (et prénom). Un `*` final = recherche par
	 * préfixe ("DUPO*"). Le champ Nom accepte aussi le code fédéral.
	 */
	public function chercher_joueur( $nom, $prenom = '' ) {
		return self::parse_lignes_joueurs(
			$this->fetch_page( 'ListeJoueurs.aspx?Action=FFE&JrNom=' . rawurlencode( $nom ) . '&JrPrenom=' . rawurlencode( $prenom ) )
		);
	}

	public function fide_id_du_joueur( $ffe_id ) {
		if ( ! $ffe_id ) {
			return null;
		}
		return self::fide_id_depuis_html( $this->fetch_page( 'FicheJoueur.aspx?Id=' . rawurlencode( (string) $ffe_id ) ) );
	}

	/** Elo à jour d'un joueur déjà identifié (par code fédéral, sinon nom/prénom). */
	public function elo_joueur( $codeffe = '', $nom = '', $prenom = '' ) {
		$res = $this->chercher_joueur( $codeffe ? $codeffe : $nom, $codeffe ? '' : $prenom );
		if ( ! $res ) {
			return null;
		}
		if ( $codeffe ) {
			foreach ( $res as $r ) {
				if ( $r['codeFFE'] === $codeffe ) {
					return $r;
				}
			}
		}
		return $res[0];
	}
}
