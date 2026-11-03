<?php
/**
 * Moteur de suivi — portage de src/tracker.js (partie FFE).
 *
 * Croise les joueurs suivis avec les tournois dont les résultats sont en ligne
 * et construit l'état du tableau de bord.
 *
 * DIFFÉRENCE DE STRUCTURE AVEC L'ORIGINAL, et pourquoi.
 * L'app Node fait tout en un passage, quatre requêtes en parallèle (~30 s au
 * premier scan, un millier de requêtes). En PHP sous WordPress, un cron est
 * souvent coupé au bout de 30 à 60 s : un passage unique échouerait. Le moteur
 * est donc REPRENABLE :
 *   - chaque passage a un budget de temps (config `budgetSecondes`) ; au-delà,
 *     plus aucune requête n'est lancée et le passage se termine proprement ;
 *   - tout ce qui est lu est mis en cache (listes des mois, fiches,
 *     participants, détails), le passage suivant reprend où on s'est arrêté ;
 *   - les tournois EN COURS des joueurs suivis sont relus en premier (le direct
 *     est ce qui compte), la découverte de nouveaux tournois vient ensuite ;
 *   - l'état est reconstruit à chaque passage avec tout ce qui est en cache.
 * Sans limite de budget, le résultat est celui de l'original (vérifié par
 * parité sur fixtures).
 *
 * Complément chess-results (tournois hors homologation française) : après la
 * FFE, sur le même budget. La recherche par joueur est en cache (6 h par
 * défaut), les fiches sont relues à chaque passage tant que le tournoi n'est
 * pas fini, au plus une fois par jour ensuite.
 *
 * @package Suivi_Tournoi
 */

if ( ! defined( 'ABSPATH' ) && ! defined( 'SUIVI_CLI' ) ) {
	exit;
}

/** Levée quand le budget de temps d'un passage est consommé. */
class Suivi_Budget_Epuise extends RuntimeException {}

class Suivi_Tracker {

	/** Liste d'un mois considérée fraîche pendant… (s). */
	const TTL_MOIS = 3600;
	/** Fiche d'un tournoi non terminé relue au plus tous les… (s). */
	const TTL_FICHE = 6 * 3600;
	/** Détail d'un tournoi terminé relu au plus tous les… (s) : saisies tardives de l'arbitre. */
	const TTL_DETAIL_FINI = 24 * 3600;
	/**
	 * Une lecture en échec n'est retentée qu'après… (s). Sans ce cache négatif,
	 * une page cassée serait retentée à chaque passage et, sous budget serré,
	 * pourrait consommer tout le budget à chaque fois : le scan ne finirait jamais.
	 */
	const TTL_ECHEC = 3600;
	/**
	 * Part du budget d'un passage laissée à la FFE quand chess-results est
	 * actif. Sans cette réserve, la FFE (relecture du direct, découverte)
	 * consommait tout le budget et la recherche chess-results — trois requêtes
	 * indivisibles — n'aboutissait jamais (trouvé par les tests).
	 */
	const PART_FFE = 0.75;

	/** @var Suivi_Store_Interface */
	private $store;
	/** @var Suivi_FFE */
	private $ffe;
	/** @var Suivi_ChessResults */
	private $cr;
	/** @var float|null Échéance du passage en cours (microtime), null = sans limite. */
	private $echeance = null;
	/** @var bool Le budget a-t-il été atteint pendant ce passage ? */
	private $budget_atteint = false;
	/** @var array Refs dont le détail a été lu pendant ce passage. */
	private $lus = array();

	/**
	 * @param Suivi_Store_Interface $store
	 * @param callable              $transport fn(string $url, array $opts): ['body', 'content_type'
	 *                                         (+ 'status', 'headers' si $opts['redirection'] === 0)] ;
	 *                                         lève une exception sur erreur HTTP.
	 * @param array                 $options   delaiChessResultsMs (politesse, 350 par défaut).
	 */
	public function __construct( Suivi_Store_Interface $store, $transport, $options = array() ) {
		$this->store = $store;
		$budgete     = function ( $url, $opts ) use ( $transport ) {
			if ( null !== $this->echeance && microtime( true ) >= $this->echeance ) {
				$this->budget_atteint = true;
				throw new Suivi_Budget_Epuise( 'Budget de temps du passage atteint' );
			}
			return call_user_func( $transport, $url, $opts );
		};
		$this->ffe   = new Suivi_FFE( $budgete );
		$this->cr    = new Suivi_ChessResults(
			$budgete,
			isset( $options['delaiChessResultsMs'] ) ? $options['delaiChessResultsMs'] : 350
		);
	}

	public function ffe() {
		return $this->ffe;
	}

	// ---------------------------------------------------------------------
	// Fenêtre et tournoi effectif
	// ---------------------------------------------------------------------

	/** Les mois à interroger pour couvrir la fenêtre [avant, après]. */
	private static function mois_a_scanner( $avant, $apres ) {
		$out = array();
		foreach ( array( -$avant, 0, $apres ) as $d ) {
			$jour                                       = Suivi_Horloge::shift( Suivi_Horloge::today(), $d );
			list( $a, $m )                              = array( (int) substr( $jour, 0, 4 ), (int) substr( $jour, 5, 2 ) );
			$out[ "$a-$m" ] = array( 'mois' => $m, 'annee' => $a );
		}
		return array_values( $out );
	}

	/**
	 * Tournoi tel que le moteur le voit : données de la liste du mois, complétées
	 * et corrigées par la fiche (qui fait foi : dates exactes, nom complet).
	 */
	private static function tournoi( $cache, $ref ) {
		$t = isset( $cache['tournois'][ $ref ] ) ? $cache['tournois'][ $ref ] : array( 'ref' => (string) $ref );
		if ( isset( $cache['fiches'][ $ref ] ) ) {
			$f = $cache['fiches'][ $ref ];
			unset( $f['lueA'], $f['echecA'] );
			$t = array_merge( $t, $f );
		}
		return $t;
	}

	/** Une entrée de cache a-t-elle échoué récemment (à ne pas retenter) ? */
	private static function echec_recent( $entree ) {
		return is_array( $entree ) && isset( $entree['echecA'] ) && Suivi_Horloge::ts() - $entree['echecA'] < self::TTL_ECHEC;
	}

	private static function fin( $t ) {
		return ! empty( $t['dateFin'] ) ? $t['dateFin'] : ( ! empty( $t['dateDebut'] ) ? $t['dateDebut'] : null );
	}

	private static function fini( $t ) {
		$fin = self::fin( $t );
		return $fin && strcmp( $fin, Suivi_Horloge::today() ) < 0;
	}

	private static function dans_fenetre( $t, $debut, $fin_fenetre ) {
		$fin = self::fin( $t );
		return $fin && ! empty( $t['dateDebut'] )
			&& strcmp( $fin, $debut ) >= 0 && strcmp( $t['dateDebut'], $fin_fenetre ) <= 0;
	}

	private static function v( $tab, $cle, $defaut = null ) {
		return ( is_array( $tab ) && isset( $tab[ $cle ] ) ) ? $tab[ $cle ] : $defaut;
	}

	/** Première valeur non nulle (équivalent de a ?? b ?? c). */
	private static function premier( ...$vals ) {
		foreach ( $vals as $v ) {
			if ( null !== $v ) {
				return $v;
			}
		}
		return null;
	}

	// ---------------------------------------------------------------------
	// FFE, étape 1 : recenser les tournois de la fenêtre
	// ---------------------------------------------------------------------

	/** @return string[] Refs des tournois de la fenêtre. */
	private function recenser( $cfg, &$cache, &$erreurs ) {
		$today = Suivi_Horloge::today();
		$debut = Suivi_Horloge::shift( $today, -$cfg['joursAvant'] );
		$fin   = Suivi_Horloge::shift( $today, $cfg['joursApres'] );
		$now   = Suivi_Horloge::ts();

		foreach ( self::mois_a_scanner( $cfg['joursAvant'], $cfg['joursApres'] ) as $mm ) {
			$cle   = $mm['annee'] . '-' . $mm['mois'];
			$connu = self::v( $cache['mois'], $cle );
			if ( ( isset( $connu['a'] ) && $now - $connu['a'] < self::TTL_MOIS ) || self::echec_recent( $connu ) ) {
				continue;
			}
			try {
				foreach ( $this->ffe->liste_tournois_mois( $mm['mois'], $mm['annee'] ) as $t ) {
					$cache['tournois'][ $t['ref'] ] = array_merge( self::v( $cache['tournois'], $t['ref'], array() ), $t );
				}
				$cache['mois'][ $cle ] = array( 'a' => $now );
			} catch ( Suivi_Budget_Epuise $e ) {
				break;
			} catch ( Exception $e ) {
				$erreurs[]                       = "Liste {$mm['mois']}/{$mm['annee']} : " . $e->getMessage();
				$cache['mois'][ $cle ]['echecA'] = $now;
			}
		}

		// Présélection sur la date de début annoncée.
		$borne   = Suivi_Horloge::shift( $debut, -14 );
		$retenus = array();
		foreach ( array_keys( $cache['tournois'] ) as $ref ) {
			$t = self::tournoi( $cache, $ref );
			if ( empty( $t['dateDebut'] ) || strcmp( $t['dateDebut'], $borne ) < 0 || strcmp( $t['dateDebut'], $fin ) > 0 ) {
				continue;
			}
			// La fiche donne la date de fin et le nombre de rondes : relue si
			// inconnue, ou si le tournoi n'est pas fini (et pas relue récemment).
			$connu = ! empty( $t['dateFin'] ) && null !== self::v( $t, 'nbRondes' );
			$fini  = $connu && strcmp( $t['dateFin'], Suivi_Horloge::shift( $today, -2 ) ) < 0;
			$fiche   = self::v( $cache['fiches'], $ref );
			$recente = ( isset( $fiche['lueA'] ) && $now - $fiche['lueA'] < self::TTL_FICHE ) || self::echec_recent( $fiche );
			if ( ( ! $connu || ! $fini ) && ! $recente && ! $this->budget_atteint ) {
				try {
					$cache['fiches'][ $ref ] = $this->ffe->fiche_tournoi( $ref ) + array( 'lueA' => $now );
					$t                       = self::tournoi( $cache, $ref );
				} catch ( Suivi_Budget_Epuise $e ) {
					// On garde ce qu'on sait ; la fiche sera lue au prochain passage.
					unset( $e );
				} catch ( Exception $e ) {
					$erreurs[]                         = "Fiche $ref : " . $e->getMessage();
					$cache['fiches'][ $ref ]['echecA'] = $now; // données déjà lues conservées
				}
			}
			if ( self::dans_fenetre( $t, $debut, $fin ) ) {
				$retenus[] = (string) $ref;
			}
		}
		return $retenus;
	}

	// ---------------------------------------------------------------------
	// FFE, étape 2 : participants, croisement avec les joueurs suivis
	// ---------------------------------------------------------------------

	/** @return string[] Refs où au moins un joueur suivi est inscrit. */
	private function croiser( $refs, $joueurs, $cfg, &$cache, &$erreurs ) {
		$ttl  = $cfg['cacheParticipantsHeures'] * 3600;
		$now  = Suivi_Horloge::ts();
		$noms = array();
		foreach ( $joueurs as $j ) {
			$noms[ Suivi_FFE::norm_name( $j['nomComplet'] ) ] = true;
		}
		$concernes = array();
		foreach ( $refs as $ref ) {
			$t      = self::tournoi( $cache, $ref );
			$entree = self::v( $cache['participants'], $ref );
			$frais  = $entree && ( self::fini( $t ) || $now - $entree['fetchedAt'] < $ttl || self::echec_recent( $entree ) );
			$liste  = $entree ? $entree['joueurs'] : null;
			if ( ! $frais && ! $this->budget_atteint ) {
				try {
					$p                             = $this->ffe->participants( $ref );
					$liste                         = $p ? $p['joueurs'] : array();
					$cache['participants'][ $ref ] = array( 'joueurs' => $liste, 'fetchedAt' => $now );
				} catch ( Suivi_Budget_Epuise $e ) {
					$liste = $entree ? $entree['joueurs'] : array();
				} catch ( Exception $e ) {
					$erreurs[] = "Participants $ref : " . $e->getMessage();
					$liste     = $entree ? $entree['joueurs'] : array();
					// Liste déjà connue conservée ; échec mémorisé pour ne pas retenter en boucle.
					$cache['participants'][ $ref ] = array(
						'joueurs'   => $liste,
						'fetchedAt' => $entree ? $entree['fetchedAt'] : 0,
						'echecA'    => $now,
					);
				}
			}
			foreach ( (array) $liste as $l ) {
				if ( isset( $noms[ Suivi_FFE::norm_name( $l['nom'] ) ] ) ) {
					$concernes[] = (string) $ref;
					break;
				}
			}
		}
		return $concernes;
	}

	// ---------------------------------------------------------------------
	// FFE, étape 3 : détail d'un tournoi où un joueur suivi est engagé
	// ---------------------------------------------------------------------

	/** Appelle $f ; une erreur réseau donne null, le budget épuisé remonte. */
	private function ou_null( $f ) {
		try {
			return $f();
		} catch ( Suivi_Budget_Epuise $e ) {
			throw $e;
		} catch ( Exception $e ) {
			return null;
		}
	}

	/**
	 * Toutes les vues ne sont pas publiées par tous les organisateurs : on sonde,
	 * une page absente donnant null. Lève Suivi_Budget_Epuise si le budget
	 * tombe en cours de route (le détail incomplet n'est alors pas retenu).
	 */
	private function detail_tournoi( $t, &$erreurs ) {
		$ref = $t['ref'];
		$ffe = $this->ffe;
		$cl  = $this->ou_null( function () use ( $ffe, $ref ) { return $ffe->classement( $ref ); } );
		$gab = $this->ou_null( function () use ( $ffe, $ref ) { return $ffe->grille( $ref ); } );
		$ga  = ( $gab && count( $gab['joueurs'] ) ) ? $gab : null;
		// Les tournois fermés (toutes rondes) publient une grille Berger à la place.
		$berger = $ga ? null : $this->ou_null( function () use ( $ffe, $ref ) { return $ffe->grille_berger( $ref ); } );

		$rj = (int) self::premier( $ga ? $ga['ronde'] : null, $cl ? $cl['ronde'] : null, 0 );

		$lire = function ( $n ) use ( $ffe, $ref, &$erreurs ) {
			try {
				return $ffe->ronde( $ref, $n );
			} catch ( Suivi_Budget_Epuise $e ) {
				throw $e;
			} catch ( Exception $e ) {
				$erreurs[] = "Ronde $ref/$n : " . $e->getMessage();
				return null;
			}
		};

		// La ronde suivante, si elle est déjà appariée, est celle qui se joue.
		$nb   = ! empty( $t['nbRondes'] ) ? $t['nbRondes'] : INF;
		$live = $rj < $nb ? $lire( $rj + 1 ) : null;
		if ( ! ( $live && count( $live['parties'] ) ) && $rj > 0 ) {
			$live = $lire( $rj );
		}

		// Sans grille américaine, on rejoue les pages de ronde pour reconstituer
		// l'historique : la Berger donne les adversaires, pas l'ordre des rondes.
		$pages = null;
		if ( ! $ga && $rj > 0 ) {
			$pages = array();
			for ( $i = 1; $i <= $rj; $i++ ) {
				$p = ( $live && $live['numero'] === $i ) ? $live : $lire( $i );
				if ( $p && count( $p['parties'] ) ) {
					$pages[] = $p;
				}
			}
		}

		return array(
			'classement'   => $cl,
			'grille'       => $ga,
			'berger'       => $berger,
			'rondeJouee'   => $rj,
			'rondeLive'    => ( $live && count( $live['parties'] ) ) ? $live : null,
			'rondesDetail' => $pages,
		);
	}

	/**
	 * S'assure que le détail d'un tournoi concerné est à jour : relu à chaque
	 * passage s'il n'est pas fini, au plus une fois par jour sinon.
	 */
	private function assurer_detail( $ref, &$cache, &$erreurs ) {
		if ( isset( $this->lus[ $ref ] ) || $this->budget_atteint ) {
			return;
		}
		$t      = self::tournoi( $cache, $ref );
		$cached = self::v( $cache['details'], $ref );
		if ( $cached && self::fini( $t ) && Suivi_Horloge::ts() - $cached['luA'] < self::TTL_DETAIL_FINI ) {
			return;
		}
		try {
			$cache['details'][ $ref ] = $this->detail_tournoi( $t, $erreurs ) + array( 'luA' => Suivi_Horloge::ts() );
			$this->lus[ $ref ]       = true;
		} catch ( Suivi_Budget_Epuise $e ) {
			// Ancien détail conservé ; relu au prochain passage.
			unset( $e );
		}
	}

	// ---------------------------------------------------------------------
	// FFE : dérivation du parcours, une fonction par forme de publication
	// ---------------------------------------------------------------------

	/** Grille américaine : la forme la plus riche, adversaires résolus par place. */
	private static function rondes_depuis_grille( $detail, $en_grille ) {
		if ( ! $en_grille || empty( $en_grille['rondes'] ) ) {
			return null;
		}
		$out = array();
		foreach ( $en_grille['rondes'] as $i => $r ) {
			$adv = null;
			if ( null !== $r['adversaire'] ) {
				foreach ( $detail['grille']['joueurs'] as $j ) {
					if ( null !== $j['place'] && $j['place'] == $r['adversaire'] ) {
						$adv = $j;
						break;
					}
				}
			}
			$out[] = array(
				'numero'     => $i + 1,
				'resultat'   => $r['resultat'],
				'couleur'    => $r['couleur'],
				'adversaire' => $adv ? array( 'nom' => $adv['nom'], 'elo' => $adv['elo'] ) : null,
			);
		}
		return $out;
	}

	/** Pages de ronde individuelles : reconstitution quand la grille manque. */
	private static function rondes_depuis_pages( $detail, $cible ) {
		if ( empty( $detail['rondesDetail'] ) ) {
			return null;
		}
		$lignes = array();
		foreach ( $detail['rondesDetail'] as $page ) {
			$p = null;
			foreach ( $page['parties'] as $x ) {
				if ( Suivi_FFE::norm_name( $x['blancs']['nom'] ) === $cible || Suivi_FFE::norm_name( $x['noirs']['nom'] ) === $cible ) {
					$p = $x;
					break;
				}
			}
			if ( ! $p || $p['enCours'] ) {
				continue;
			}
			$blanc = Suivi_FFE::norm_name( $p['blancs']['nom'] ) === $cible;
			$parts = array_map( 'trim', explode( '-', (string) $p['resultat'] ) );
			$mien  = $blanc ? $parts[0] : ( isset( $parts[1] ) ? $parts[1] : '' );
			$lignes[] = array(
				'numero'     => $page['numero'],
				'resultat'   => '1' === $mien ? 'gagne' : ( preg_match( '/^(0|F)$/i', $mien ) ? 'perdu' : ( '' !== $mien ? 'nulle' : null ) ),
				'couleur'    => $blanc ? 'blancs' : 'noirs',
				'adversaire' => $blanc ? $p['noirs'] : $p['blancs'],
			);
		}
		return $lignes ? $lignes : null;
	}

	/** Grille Berger : adversaires et résultats, mais ni ordre ni couleurs. */
	private static function rondes_depuis_berger( $en_berger ) {
		$out = array();
		foreach ( $en_berger ? $en_berger['resultats'] : array() as $r ) {
			$out[] = array(
				'numero'     => null,
				'resultat'   => $r['resultat'],
				'couleur'    => null,
				'adversaire' => isset( $r['adversaire'] ) ? $r['adversaire'] : null,
			);
		}
		return $out;
	}

	// ---------------------------------------------------------------------
	// FFE : assemblage d'un engagement
	// ---------------------------------------------------------------------

	private static function liens_ffe( $t, $detail ) {
		$liens = array();
		if ( $detail['grille'] ) {
			$liens[] = array( 'cle' => 'grille', 'href' => Suivi_FFE::url_resultats( $t['ref'], 'Ga' ), 'type' => 'américaine' );
		} elseif ( $detail['berger'] ) {
			$liens[] = array( 'cle' => 'grille', 'href' => Suivi_FFE::url_resultats( $t['ref'], 'Berger' ), 'type' => 'Berger' );
		}
		if ( $detail['classement'] ) {
			$liens[] = array( 'cle' => 'classement', 'href' => Suivi_FFE::url_resultats( $t['ref'], 'Cl' ) );
		}
		if ( $detail['rondeLive'] ) {
			$n       = $detail['rondeLive']['numero'];
			$liens[] = array( 'cle' => 'ronde', 'href' => Suivi_FFE::url_resultats( $t['ref'], sprintf( '%02d', $n ) ), 'ronde' => $n );
		}
		$liens[] = array( 'cle' => 'participants', 'href' => Suivi_FFE::url_resultats( $t['ref'], 'Ls' ) );
		$liens[] = array( 'cle' => 'fiche', 'href' => 'https://www.echecs.asso.fr/FicheTournoi.aspx?Ref=' . $t['ref'] );
		return $liens;
	}

	/** État d'un joueur dans un tournoi FFE, ou null s'il n'y est pas. */
	private static function engagement_ffe( $joueur, $t, $detail, $liste ) {
		$en_liste  = Suivi_Appariement::trouver_joueur( $joueur, $liste );
		$en_cl     = $detail['classement'] ? Suivi_Appariement::trouver_joueur( $joueur, $detail['classement']['joueurs'] ) : null;
		$en_ga     = $detail['grille'] ? Suivi_Appariement::trouver_joueur( $joueur, $detail['grille']['joueurs'] ) : null;
		$en_berger = $detail['berger'] ? Suivi_Appariement::trouver_joueur( $joueur, $detail['berger']['joueurs'] ) : null;
		if ( ! $en_liste && ! $en_cl && ! $en_ga && ! $en_berger ) {
			return null;
		}

		$src      = $en_cl ? $en_cl : ( $en_ga ? $en_ga : ( $en_liste ? $en_liste : $en_berger ) );
		$nom_papi = $src['nom'];
		$cible    = Suivi_FFE::norm_name( $nom_papi );

		$rondes = self::rondes_depuis_grille( $detail, $en_ga );
		if ( null === $rondes ) {
			$rondes = self::rondes_depuis_pages( $detail, $cible );
		}
		if ( null === $rondes ) {
			$rondes = self::rondes_depuis_berger( $en_berger );
		}

		// Partie du moment : le joueur dans la ronde appariée la plus récente.
		$en_attente = null;
		$live       = $detail['rondeLive'];
		if ( $live ) {
			foreach ( $live['parties'] as $p ) {
				$blanc = Suivi_FFE::norm_name( $p['blancs']['nom'] ) === $cible;
				if ( $blanc || Suivi_FFE::norm_name( $p['noirs']['nom'] ) === $cible ) {
					$en_attente = array(
						'ronde'           => $live['numero'],
						'echiquier'       => $p['echiquier'],
						'couleur'         => $blanc ? 'blancs' : 'noirs',
						'adversaire'      => $blanc ? $p['noirs'] : $p['blancs'],
						'resultat'        => $p['enCours'] ? null : $p['resultat'],
						'appariementSeul' => $live['appariementSeul'],
					);
					break;
				}
			}
		}

		// Avant la ronde 1, la page de classement donne l'ordre initial par Elo :
		// ce n'est pas une position sportive, on ne l'expose pas.
		$commence = $detail['rondeJouee'] > 0;
		$p        = function ( $x, $k ) {
			return $x ? self::v( $x, $k ) : null;
		};

		return Suivi_Engagement::construire(
			array(
				'source'     => 'ffe',
				'joueurId'   => $joueur['id'],
				'nomPapi'    => $nom_papi,
				'tournoi'    => array(
					'ref'       => (string) $t['ref'],
					'nom'       => self::v( $t, 'nom' ),
					'ville'     => self::v( $t, 'ville' ),
					'dept'      => self::v( $t, 'dept' ),
					'lieu'      => self::v( $t, 'lieu' ),
					'dateDebut' => self::v( $t, 'dateDebut' ),
					'dateFin'   => self::v( $t, 'dateFin' ),
					'cadence'   => self::v( $t, 'cadence' ),
					'nbRondes'  => self::v( $t, 'nbRondes' ),
					'arbitre'   => self::v( $t, 'arbitre' ),
				),
				'liens'      => self::liens_ffe( $t, $detail ),
				'rondes'     => $rondes,
				'enAttente'  => $en_attente,
				'rondeJouee' => $detail['rondeJouee'],
				'place'      => $commence ? self::premier( $p( $en_cl, 'place' ), $p( $en_ga, 'place' ) ) : null,
				'points'     => $commence ? self::premier( $p( $en_cl, 'points' ), $p( $en_ga, 'points' ), $p( $en_berger, 'points' ) ) : null,
				'nbJoueurs'  => count( $detail['classement'] ? $detail['classement']['joueurs'] : (array) $liste ),
				'eloTournoi' => self::premier( $p( $en_liste, 'elo' ), $p( $en_cl, 'elo' ) ),
				'club'       => self::premier( $p( $en_liste, 'club' ), $p( $en_cl, 'club' ) ),
				'perfSource' => ! empty( $en_ga['perfFFE'] ) ? $en_ga['perfFFE'] : null,
			)
		);
	}

	// ---------------------------------------------------------------------
	// Rafraîchissement
	// ---------------------------------------------------------------------

	/**
	 * Un passage de suivi. Sans `budget_s`, prend le budget de la config
	 * (`budgetSecondes`) ; 0 = sans limite.
	 *
	 * @return array Document d'état enregistré.
	 */
	public function rafraichir( $options = array() ) {
		$debut_passage        = microtime( true );
		$cfg                  = $this->store->get( 'config' );
		$budget               = isset( $options['budget_s'] ) ? $options['budget_s'] : $cfg['budgetSecondes'];
		$this->echeance       = $budget ? $debut_passage + $budget : null;
		$this->budget_atteint = false;
		$cr_actif             = false !== self::v( $cfg, 'chessResultsActif', true );
		$echeance_passage     = $this->echeance;
		if ( $cr_actif && $this->echeance ) {
			$this->echeance = $debut_passage + self::PART_FFE * $budget;
		}
		$this->lus            = array();

		$db      = $this->store->get( 'joueurs' );
		$cache   = $this->store->get( 'cache' );
		$st      = $this->store->get( 'etat' );
		$jl      = $this->store->get( 'journal' );
		$archive = $this->store->get( 'archive' );
		$erreurs = array();

		if ( ! $db['joueurs'] ) {
			$st['suivi']                   = array();
			$st['dernierRafraichissement'] = Suivi_Horloge::iso();
			$st['erreurs']                 = array();
			$this->store->set( 'etat', $st );
			$this->echeance = null;
			return $st;
		}

		try {
			Suivi_Joueurs::maj_elo( $db['joueurs'], $this->ffe, ! empty( $options['forceElo'] ), $erreurs );
		} catch ( Suivi_Budget_Epuise $e ) {
			unset( $e );
		}

		$today       = Suivi_Horloge::today();
		$debut       = Suivi_Horloge::shift( $today, -$cfg['joursAvant'] );
		$fin_fenetre = Suivi_Horloge::shift( $today, $cfg['joursApres'] );

		// 1. Le direct d'abord : tournois en cours déjà connus comme concernés.
		// Plafonné à 60 % du budget, pour que chaque passage fasse aussi
		// avancer la découverte (sinon un budget serré l'affamerait).
		$fin_direct = $this->echeance ? $debut_passage + 0.6 * $budget : null;
		$precedents = array();
		foreach ( (array) $cache['concernes'] as $ref ) {
			$t = self::tournoi( $cache, $ref );
			if ( self::dans_fenetre( $t, $debut, $fin_fenetre ) ) {
				$precedents[] = (string) $ref;
				if ( ! self::fini( $t ) && ( null === $fin_direct || microtime( true ) < $fin_direct ) ) {
					$this->assurer_detail( $ref, $cache, $erreurs );
				}
			}
		}

		// 2. Découverte : listes des mois, fiches, participants.
		$refs      = $this->recenser( $cfg, $cache, $erreurs );
		$concernes = $this->croiser( $refs, $db['joueurs'], $cfg, $cache, $erreurs );
		if ( $this->budget_atteint ) {
			// Découverte incomplète : on ne perd pas ce qu'on savait déjà.
			$concernes = array_values( array_unique( array_merge( $precedents, $concernes ) ) );
		}
		$cache['concernes'] = $concernes;

		// 3. Détails des tournois concernés.
		foreach ( $concernes as $ref ) {
			$this->assurer_detail( $ref, $cache, $erreurs );
		}

		// 4. État : reconstruit avec tout ce qui est en cache, sans réseau.
		$suivi = array();
		foreach ( $concernes as $ref ) {
			if ( empty( $cache['details'][ $ref ] ) ) {
				continue;
			}
			$t     = self::tournoi( $cache, $ref );
			$liste = isset( $cache['participants'][ $ref ] ) ? $cache['participants'][ $ref ]['joueurs'] : array();
			foreach ( $db['joueurs'] as $joueur ) {
				$e = self::engagement_ffe( $joueur, $t, $cache['details'][ $ref ], $liste );
				if ( $e ) {
					$suivi[] = $e;
				}
			}
		}

		// 5. chess-results couvre les tournois hors homologation française.
		// Il reprend le reste du budget (au moins 1 - PART_FFE).
		$ffe_incomplet        = $this->budget_atteint;
		$this->echeance       = $echeance_passage;
		$this->budget_atteint = false;
		$memoire_cr           = null;
		if ( $cr_actif ) {
			try {
				Suivi_Joueurs::maj_fide_ids( $db['joueurs'], $this->ffe, $erreurs );
			} catch ( Suivi_Budget_Epuise $e ) {
				unset( $e );
			}
			$memoire_cr = $this->store->get( 'chessresults' );
			$suivi      = array_merge( $suivi, $this->completer_par_chess_results( $db['joueurs'], $suivi, $cfg, $memoire_cr, $erreurs ) );
		}

		// Le plus chaud d'abord : parties en cours, puis tournois récents.
		$rang = array( 'en_cours' => 0, 'a_venir' => 1, 'termine' => 2 );
		usort(
			$suivi,
			function ( $a, $b ) use ( $rang ) {
				$ea = ! empty( $a['partieEnCours']['enCours'] ) ? 1 : 0;
				$eb = ! empty( $b['partieEnCours']['enCours'] ) ? 1 : 0;
				if ( $eb !== $ea ) {
					return $eb - $ea;
				}
				if ( $rang[ $a['statut'] ] !== $rang[ $b['statut'] ] ) {
					return $rang[ $a['statut'] ] - $rang[ $b['statut'] ];
				}
				return strcmp( (string) self::v( $b['tournoi'], 'dateDebut', '' ), (string) self::v( $a['tournoi'], 'dateDebut', '' ) );
			}
		);

		$nouveaux = Suivi_Journal::maj( $jl, $suivi, $db['joueurs'] );
		Suivi_Archive::archiver( $archive, $suivi );

		$st['suivi']                   = $suivi;
		$st['dernierRafraichissement'] = Suivi_Horloge::iso();
		$st['dernierScan']             = array(
			'tournoisExamines'  => count( $refs ),
			'dureeMs'           => (int) round( ( microtime( true ) - $debut_passage ) * 1000 ),
			'nouveauxResultats' => $nouveaux,
		);
		$st['erreurs']     = array_slice( $erreurs, 0, 20 );
		$st['scanComplet'] = ! $ffe_incomplet && ! $this->budget_atteint;

		$this->store->set( 'joueurs', $db );
		$this->store->set( 'cache', $cache );
		$this->store->set( 'etat', $st );
		$this->store->set( 'journal', $jl );
		$this->store->set( 'archive', $archive );
		if ( null !== $memoire_cr ) {
			$this->store->set( 'chessresults', $memoire_cr );
		}
		$this->echeance = null;
		return $st;
	}

	// ---------------------------------------------------------------------
	// chess-results : les tournois hors homologation française
	// ---------------------------------------------------------------------

	/** Vues d'agrégation du site, qui ne sont pas des épreuves. */
	const VUE_AGREGEE = '/\b(full list|all participants|all groups)\b/i';

	/** Le site héberge des tournois de test et des vues d'agrégation. */
	private static function entree_ignoree( $entree ) {
		return preg_match( '/^TEST\b/i', $entree['nom'] ) || preg_match( self::VUE_AGREGEE, $entree['nom'] );
	}

	private static function engagement_chess_results( $joueur, $entree, $fiche ) {
		// La dernière ronde listée sans résultat est la partie du moment ; la
		// laisser aussi dans le parcours l'afficherait deux fois.
		$toutes   = $fiche['rondes'];
		$derniere = $toutes ? $toutes[ count( $toutes ) - 1 ] : null;
		$attente  = ( $derniere && ! $derniere['resultat'] ) ? $derniere : null;
		$passees  = $attente ? array_slice( $toutes, 0, -1 ) : $toutes;

		$rondes = array();
		foreach ( $passees as $r ) {
			$rondes[] = array(
				'numero'     => $r['numero'],
				'resultat'   => $r['resultat'],
				'couleur'    => $r['couleur'],
				'adversaire' => ! empty( $r['adversaire']['nom'] ) ? array( 'nom' => $r['adversaire']['nom'], 'elo' => $r['adversaire']['elo'] ) : null,
			);
		}
		$liens = array(
			array( 'cle' => 'joueur', 'href' => $fiche['url'] ),
			array( 'cle' => 'fiche', 'href' => $fiche['urlTournoi'] ),
		);
		if ( $attente ) {
			$liens[] = array( 'cle' => 'ronde', 'href' => $fiche['url'], 'ronde' => $attente['numero'] );
		}
		$jouees = 0;
		foreach ( $rondes as $r ) {
			if ( $r['resultat'] ) {
				$jouees++;
			}
		}

		return Suivi_Engagement::construire(
			array(
				'source'     => 'chess-results',
				'joueurId'   => $joueur['id'],
				'nomPapi'    => '' !== $fiche['nom'] ? $fiche['nom'] : $joueur['nomComplet'],
				'tournoi'    => array(
					'ref'      => 'cr' . $entree['tnr'],
					'nom'      => '' !== $fiche['tournoi'] ? $fiche['tournoi'] : $entree['nom'],
					'dateFin'  => $entree['dateFin'],
					'nbRondes' => self::v( $entree, 'nbRondes' ),
					'misAJour' => $fiche['misAJour'],
				),
				'liens'      => $liens,
				'rondes'     => $rondes,
				'enAttente'  => $attente
					? array(
						'ronde'      => $attente['numero'],
						'echiquier'  => $attente['echiquier'],
						'couleur'    => $attente['couleur'],
						'adversaire' => array( 'nom' => $attente['adversaire']['nom'], 'elo' => $attente['adversaire']['elo'] ),
					)
					: null,
				'rondeJouee' => $jouees,
				'place'      => self::premier( $fiche['place'], self::v( $entree, 'rang' ) ),
				'points'     => $fiche['points'],
				'nbJoueurs'  => self::v( $entree, 'nbJoueurs' ),
				'eloTournoi' => $fiche['elo'],
				'club'       => $fiche['club'],
				'perfSource' => $fiche['performance'] ? $fiche['performance'] : null,
				// Le titre FIDE sert au coefficient K de l'écran Elo : un maître
				// garde K=10 même sous 2400. La FFE ne le publie pas, chess-results si.
				'extra'      => array( 'variationFide' => $fiche['variationFide'], 'titre' => $fiche['titre'] ),
			)
		);
	}

	/**
	 * Fiche chess-results d'un joueur dans un tournoi, depuis le cache si elle
	 * est assez fraîche : relue à chaque passage tant que le tournoi n'est pas
	 * fini, une fois par jour ensuite. Rend null si rien n'est lisible.
	 */
	private function fiche_cr( $entree, &$memoire, &$erreurs ) {
		$cle     = $entree['tnr'] . '|' . $entree['snr'];
		$cached  = self::v( $memoire['fiches'], $cle );
		$fini    = ! empty( $entree['dateFin'] ) && strcmp( $entree['dateFin'], Suivi_Horloge::today() ) < 0;
		$fraiche = isset( $cached['luA'] ) && $fini && Suivi_Horloge::ts() - $cached['luA'] < self::TTL_DETAIL_FINI;
		if ( $fraiche || isset( $this->lus[ "cr$cle" ] ) || $this->budget_atteint || self::echec_recent( $cached ) ) {
			return self::v( $cached, 'fiche' );
		}
		try {
			$fiche                     = $this->cr->fiche_par_tournoi( $entree['tnr'], $entree['snr'] );
			$memoire['fiches'][ $cle ] = array( 'fiche' => $fiche, 'luA' => Suivi_Horloge::ts() );
			$this->lus[ "cr$cle" ]     = true;
			return $fiche;
		} catch ( Suivi_Budget_Epuise $e ) {
			return self::v( $cached, 'fiche' );
		} catch ( Exception $e ) {
			// L'original écartait le tournoi pour ce passage ; on garde la dernière fiche lue.
			$erreurs[]                           = "chess-results tnr{$entree['tnr']} : " . $e->getMessage();
			$memoire['fiches'][ $cle ]           = is_array( $cached ) ? $cached : array( 'fiche' => null );
			$memoire['fiches'][ $cle ]['echecA'] = Suivi_Horloge::ts();
			return self::v( $cached, 'fiche' );
		}
	}

	/**
	 * Engagements vus par chess-results et absents du côté FFE. La découverte
	 * (une recherche par joueur) est mise en cache, elle bouge peu.
	 *
	 * @param array $memoire Document 'chessresults' (parJoueur, fiches), modifié sur place.
	 */
	private function completer_par_chess_results( $joueurs, $suivi_ffe, $cfg, &$memoire, &$erreurs ) {
		$today   = Suivi_Horloge::today();
		$du      = Suivi_Horloge::shift( $today, -$cfg['joursAvant'] );
		$au      = Suivi_Horloge::shift( $today, $cfg['joursApres'] );
		$ttl     = ( null !== self::v( $cfg, 'chessResultsTtlHeures' ) ? $cfg['chessResultsTtlHeures'] : 6 ) * 3600;
		$fenetre = "$du|$au";
		$now     = Suivi_Horloge::ts();
		if ( ! isset( $memoire['fiches'] ) ) {
			$memoire['fiches'] = array();
		}
		$ajouts    = array();
		$utilisees = array();

		// Le direct d'abord : les fiches des tournois non finis déjà connus sont
		// relues avant les recherches, plafonnées à 60 % du temps restant pour
		// que la découverte avance aussi à chaque passage.
		$t0         = microtime( true );
		$fin_direct = $this->echeance ? $t0 + 0.6 * max( 0, $this->echeance - $t0 ) : null;
		foreach ( $joueurs as $joueur ) {
			$memo = empty( $joueur['fideId'] ) ? null : self::v( $memoire['parJoueur'], $joueur['id'] );
			foreach ( $memo ? $memo['tournois'] : array() as $entree ) {
				if ( null !== $fin_direct && microtime( true ) >= $fin_direct ) {
					break 2;
				}
				if ( ! self::entree_ignoree( $entree ) && ! empty( $entree['dateFin'] ) && strcmp( $entree['dateFin'], $today ) >= 0 ) {
					$this->fiche_cr( $entree, $memoire, $erreurs );
				}
			}
		}

		foreach ( $joueurs as $joueur ) {
			if ( empty( $joueur['fideId'] ) ) {
				continue;
			}
			$id       = $joueur['id'];
			$memo     = self::v( $memoire['parJoueur'], $id );
			$tournois = $memo ? $memo['tournois'] : array();
			$perime   = ! $memo || $memo['fenetre'] !== $fenetre || $now - $memo['a'] > $ttl;

			if ( $perime && ! $this->budget_atteint && ! self::echec_recent( $memo ) ) {
				try {
					$tournois                    = $this->cr->tournois_du_joueur( $joueur['fideId'], $du, $au );
					$memoire['parJoueur'][ $id ] = array( 'tournois' => $tournois, 'a' => $now, 'fenetre' => $fenetre );
				} catch ( Suivi_Budget_Epuise $e ) {
					unset( $e ); // recherche faite à un prochain passage
				} catch ( Exception $e ) {
					$erreurs[]                             = "chess-results {$joueur['nomComplet']} : " . $e->getMessage();
					$memoire['parJoueur'][ $id ]           = $memo ? $memo : array( 'tournois' => array(), 'a' => 0, 'fenetre' => '' );
					$memoire['parJoueur'][ $id ]['echecA'] = $now;
				}
			}

			$siens = array();
			foreach ( $suivi_ffe as $s ) {
				if ( $s['joueurId'] === $id ) {
					$siens[] = $s;
				}
			}
			$du_joueur = array();
			foreach ( $tournois as $entree ) {
				if ( self::entree_ignoree( $entree ) ) {
					continue;
				}
				$utilisees[ $entree['tnr'] . '|' . $entree['snr'] ] = true;
				$fiche = $this->fiche_cr( $entree, $memoire, $erreurs );
				if ( ! $fiche ) {
					continue;
				}
				// La recherche tronque les noms à trente caractères : on ne compare
				// avec la FFE qu'une fois le nom complet connu.
				$complet        = $entree;
				$complet['nom'] = '' !== $fiche['tournoi'] ? $fiche['tournoi'] : $entree['nom'];
				if ( preg_match( self::VUE_AGREGEE, $complet['nom'] ) || Suivi_Appariement::est_doublon( $complet, $siens ) ) {
					continue;
				}
				$e = self::engagement_chess_results( $joueur, $complet, $fiche );
				/* Les grands événements ont un tournoi-chapeau et des groupes ; la
				   fiche du chapeau n'a ni rondes ni points. Un engagement fini sans
				   aucune partie lisible est cette coquille : on l'écarte. */
				if ( 'a_venir' !== $e['statut'] && ! $e['rondes'] && empty( $e['points'] ) ) {
					continue;
				}
				$du_joueur[] = $e;
			}

			/* Pendant qu'un événement multi-groupes se joue, la page-chapeau passe
			   le filtre précédent (« à venir », zéro ronde). On la démasque : elle
			   finit le même jour que le groupe qui porte le parcours et réunit PLUS
			   de participants que lui. */
			foreach ( $du_joueur as $k => $e ) {
				if ( $e['rondes'] || ! empty( $e['points'] ) ) {
					$ajouts[] = $e;
					continue;
				}
				$chapeau = false;
				foreach ( $du_joueur as $g => $groupe ) {
					if ( $g !== $k && $groupe['rondes']
						&& ! empty( $groupe['tournoi']['dateFin'] ) && $groupe['tournoi']['dateFin'] === self::v( $e['tournoi'], 'dateFin' )
						&& $e['nbJoueurs'] && $groupe['nbJoueurs'] && $e['nbJoueurs'] > $groupe['nbJoueurs']
						&& Suivi_Appariement::est_doublon( $e['tournoi'], array( $groupe ) ) ) {
						$chapeau = true;
						break;
					}
				}
				if ( ! $chapeau ) {
					$ajouts[] = $e;
				}
			}
		}

		// Les fiches qui ne servent plus (tournoi sorti de la fenêtre, joueur retiré) sont oubliées.
		$memoire['fiches'] = array_intersect_key( $memoire['fiches'], $utilisees );
		return $ajouts;
	}

	/** Ce que l'affichage reçoit : joueurs (avec leur Elo en direct), suivi, fil. */
	public function etat() {
		$db  = $this->store->get( 'joueurs' );
		$st  = $this->store->get( 'etat' );
		$cfg = $this->store->get( 'config' );
		$jl  = $this->store->get( 'journal' );

		$par_joueur = array();
		foreach ( $st['suivi'] as $s ) {
			$par_joueur[ $s['joueurId'] ][] = $s;
		}
		$joueurs = array();
		foreach ( $db['joueurs'] as $j ) {
			$joueurs[] = Suivi_Joueurs::joueur_public( $j ) + array(
				'direct' => Suivi_Elo::direct_joueur( $j, isset( $par_joueur[ $j['id'] ] ) ? $par_joueur[ $j['id'] ] : array() ),
			);
		}
		return array_merge(
			array( 'joueurs' => $joueurs ),
			$st,
			array(
				'evenements'              => array_slice( $jl['evenements'], 0, 150 ),
				'config'                  => $cfg,
				'rafraichissementEnCours' => false,
			)
		);
	}

	/** Bilan de saison d'un joueur : archive + tournois en cours. */
	public function bilan_joueur( $id ) {
		$st = $this->store->get( 'etat' );
		return Suivi_Archive::bilan( $this->store->get( 'archive' ), $id, $st['suivi'] );
	}

	// ---------------------------------------------------------------------
	// Gestion des joueurs suivis (ce que l'admin WordPress appellera)
	// ---------------------------------------------------------------------

	/**
	 * Ajoute un joueur, par code fédéral ou par nom (+ prénom).
	 *
	 * @param array $args codeFFE | nom, prenom, club
	 */
	public function ajouter_joueur( $args ) {
		$code   = isset( $args['codeFFE'] ) ? trim( $args['codeFFE'] ) : '';
		$nom    = isset( $args['nom'] ) ? trim( $args['nom'] ) : '';
		$prenom = isset( $args['prenom'] ) ? trim( $args['prenom'] ) : '';
		$db     = $this->store->get( 'joueurs' );
		$fiche  = null;

		if ( $code || $nom ) {
			$res = $this->ffe->chercher_joueur( $code ? $code : $nom, $code ? '' : $prenom );
			if ( $code ) {
				foreach ( $res as $r ) {
					if ( $r['codeFFE'] === $code ) {
						$fiche = $r;
						break;
					}
				}
			}
			if ( ! $fiche && $res ) {
				$fiche = $res[0];
			}
		}
		if ( ! $fiche && ! $nom ) {
			throw new RuntimeException( 'Joueur introuvable' );
		}

		$joueur = Suivi_Joueurs::creer( $fiche, $args );
		foreach ( $db['joueurs'] as $j ) {
			if ( $j['id'] === $joueur['id'] ) {
				throw new RuntimeException( $joueur['nomComplet'] . ' est déjà suivi' );
			}
		}
		$db['joueurs'][] = $joueur;
		$this->store->set( 'joueurs', $db );
		return $joueur;
	}

	/**
	 * Ajoute d'un coup tous les licenciés d'un club au-dessus d'un Elo.
	 * Le ClubRef se lit dans l'adresse de la page du club (Cannes Échecs = 311).
	 */
	public function ajouter_club( $club_ref, $elo_min = 0 ) {
		if ( ! $club_ref ) {
			throw new RuntimeException( 'ClubRef manquant' );
		}
		$db        = $this->store->get( 'joueurs' );
		$licencies = $this->ffe->joueurs_du_club( $club_ref );
		if ( ! $licencies ) {
			throw new RuntimeException( "Aucun licencié trouvé pour le club $club_ref" );
		}
		$connus = array();
		foreach ( $db['joueurs'] as $j ) {
			$connus[ $j['id'] ] = true;
		}
		$retenus = array_values(
			array_filter(
				$licencies,
				function ( $f ) use ( $elo_min ) {
					return ( null === $f['elo'] ? 0 : $f['elo'] ) >= $elo_min;
				}
			)
		);
		$ajoutes = array();
		foreach ( $retenus as $fiche ) {
			$joueur = Suivi_Joueurs::creer( $fiche );
			if ( isset( $connus[ $joueur['id'] ] ) ) {
				continue;
			}
			$connus[ $joueur['id'] ] = true;
			$db['joueurs'][]          = $joueur;
			$ajoutes[]                = $joueur;
		}
		// Le classement du club sert de référence d'affichage.
		usort(
			$db['joueurs'],
			function ( $a, $b ) {
				return ( null === $b['elo'] ? 0 : $b['elo'] ) <=> ( null === $a['elo'] ? 0 : $a['elo'] );
			}
		);
		$this->store->set( 'joueurs', $db );
		return array(
			'club'    => $licencies[0]['club'],
			'total'   => count( $licencies ),
			'retenus' => count( $retenus ),
			'ajoutes' => $ajoutes,
		);
	}

	/** Retire un joueur et lui fait oublier fil, archive et caches. */
	public function retirer_joueur( $id ) {
		$db    = $this->store->get( 'joueurs' );
		$avant = count( $db['joueurs'] );
		$db['joueurs'] = array_values(
			array_filter(
				$db['joueurs'],
				function ( $j ) use ( $id ) {
					return $j['id'] !== $id;
				}
			)
		);
		if ( count( $db['joueurs'] ) === $avant ) {
			throw new RuntimeException( 'Joueur inconnu' );
		}
		$st          = $this->store->get( 'etat' );
		$st['suivi'] = array_values(
			array_filter(
				$st['suivi'],
				function ( $s ) use ( $id ) {
					return $s['joueurId'] !== $id;
				}
			)
		);
		$jl = $this->store->get( 'journal' );
		Suivi_Journal::oublier( $jl, $id );
		$a = $this->store->get( 'archive' );
		Suivi_Archive::oublier( $a, $id );
		$cr = $this->store->get( 'chessresults' );
		unset( $cr['parJoueur'][ $id ] );

		$this->store->set( 'joueurs', $db );
		$this->store->set( 'etat', $st );
		$this->store->set( 'journal', $jl );
		$this->store->set( 'archive', $a );
		$this->store->set( 'chessresults', $cr );
	}
}
