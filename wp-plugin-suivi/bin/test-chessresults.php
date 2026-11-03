<?php
/**
 * Tests CLI du complément chess-results (lecture des pages + moteur), sur
 * fixtures, horloge figée, sans réseau.
 * Usage : php bin/test-chessresults.php
 *
 * Validé par parité avec l'app Node d'origine : mêmes fiches lues champ par
 * champ sur les six dispositions de pages, et même état du moteur sur le
 * scénario ci-dessous (8 engagements, 23 requêtes chess-results), avant l'ajout
 * volontaire du filtre des fiches de test d'organisateurs (+2 requêtes).
 *
 * Le faux réseau reproduit le comportement du site : redirection vers un nœud
 * (S3…), cookie de session posé par le formulaire et exigé par la recherche
 * (sinon erreur 500, comme le vrai site quand le __VIEWSTATE est rejeté).
 */

define( 'SUIVI_CLI', true );
foreach ( array( 'ffe', 'elo', 'horloge', 'store', 'engagement', 'appariement', 'joueurs', 'journal', 'chessresults', 'tracker' ) as $c ) {
	require __DIR__ . "/../includes/class-suivi-$c.php";
}

$FIX  = __DIR__ . '/../tests/fixtures';
$fail = 0;
$nb   = 0;
function ok( $cond, $label ) {
	global $fail, $nb;
	$nb++;
	if ( ! $cond ) {
		$fail++;
		echo "  FAIL $label\n";
	}
}
function section( $t ) {
	echo "• $t\n";
}

const FICHES_CR = array(
	'1496391|3'   => 'cr-fiche-en-cours.html',
	'1450094|13'  => 'cr-fiche-ouvert.html',
	'1454061|6'   => 'cr-fiche-grec.html',
	'1436548|6'   => 'cr-fiche-parent.html',
	'1338669|141' => 'cr-fiche-9col.html',
	'1338670|2'   => 'cr-fiche-9col.html',
	'1448047|2'   => 'cr-fiche-ferme.html',
	'1500000|4'   => 'cr-fiche-doublon.html',
	'1529799|12'  => 'cr-fiche-test-organisateur.html',
);

/**
 * Faux réseau FFE + chess-results.
 *
 * @param array $journal  Reçoit "METHODE url" de chaque requête chess-results.
 * @param array $en_panne URLs chess-results (sous-chaîne) qui répondent 503.
 */
function transport( &$journal = null, $en_panne = array() ) {
	global $FIX;
	return function ( $url, $opts ) use ( $FIX, &$journal, $en_panne ) {
		$lire = function ( $f, $statut = 200, $entetes = array() ) use ( $FIX ) {
			return array( 'status' => $statut, 'body' => $f ? file_get_contents( "$FIX/$f" ) : '', 'content_type' => 'text/html; charset=utf-8', 'headers' => $entetes );
		};
		if ( preg_match( '/chess-results\.com/i', $url ) ) {
			if ( null !== $journal ) {
				$journal[] = $opts['method'] . ' ' . $url;
			}
			foreach ( $en_panne as $p ) {
				if ( false !== stripos( $url, $p ) ) {
					throw new RuntimeException( "HTTP 503 sur $url" );
				}
			}
			$h = array_change_key_case( isset( $opts['headers'] ) ? $opts['headers'] : array(), CASE_LOWER );
			if ( preg_match( '#^https://chess-results\.com/#i', $url ) ) {
				return $lire( null, 302, array( 'Location' => preg_replace( '#^https://chess-results\.com/#i', 'https://S3.chess-results.com/', $url ) . '&SNode=S0' ) );
			}
			if ( preg_match( '/spielersuche\.aspx/i', $url ) ) {
				if ( 'GET' === $opts['method'] ) {
					return $lire( 'cr-formulaire.html', 200, array( 'Set-Cookie' => array( 'ASP.NET_SessionId=sess42; path=/; HttpOnly' ) ) );
				}
				$corps = (string) $opts['body'];
				if ( false === strpos( isset( $h['cookie'] ) ? $h['cookie'] : '', 'ASP.NET_SessionId=sess42' ) || false === strpos( $corps, '__VIEWSTATE=%2FwE' ) ) {
					return $lire( null, 500 );
				}
				$id = preg_match( '/txt_fideID=(\d+)&/', $corps, $m ) ? $m[1] : '';
				$n  = array( '25059530' => 'pragg', '999' => 'prie' );
				return $lire( 'cr-recherche-' . ( isset( $n[ $id ] ) ? $n[ $id ] : 'vide' ) . '.html' );
			}
			if ( preg_match( '/tnr(\d+)\.aspx\?lan=1&art=9&snr=(\d+)/', $url, $m ) && isset( FICHES_CR[ "{$m[1]}|{$m[2]}" ] ) ) {
				return $lire( FICHES_CR[ "{$m[1]}|{$m[2]}" ] );
			}
			return $lire( null, 404 );
		}
		if ( false !== strpos( $url, 'JOUEURCLUBREF' ) ) {
			$f = 'club-311.html';
		} elseif ( false !== strpos( $url, 'ListeTournois' ) ) {
			$f = 'liste-2026-10.html';
		} elseif ( preg_match( '/FicheJoueur\.aspx\?Id=(\d+)/', $url, $m ) ) {
			$f = "ffe-fichejoueur-{$m[1]}.html";
		} elseif ( preg_match( '/FicheTournoi\.aspx\?Ref=(\d+)/', $url, $m ) ) {
			$f = "fiche-{$m[1]}.html";
		} elseif ( preg_match( '#Tournois/Id/(\d+)/\d+&Action=(\w+)#', $url, $m ) ) {
			$f = "res-{$m[1]}-{$m[2]}.html";
		} else {
			$f = null;
		}
		if ( ! $f || ! is_file( "$FIX/$f" ) ) {
			throw new RuntimeException( "HTTP 404 sur $url" );
		}
		return $lire( $f );
	};
}

/** Magasin : PRIE (FIDE 999, doublon de son tournoi FFE), Praggnanandhaa (FIDE connu), Anna (FIDE à lire sur sa fiche FFE). */
function magasin( $budget = 0 ) {
	$maj   = '2026-11-01T12:00:00.000Z';
	$tac   = 'Tremblay Athletique Club - TAC Echecs';
	$store = new Suivi_Store_Memoire();
	$store->set( 'config', array( 'joursAvant' => 15, 'joursApres' => 30, 'cacheParticipantsHeures' => 12, 'chessResultsActif' => true, 'chessResultsTtlHeures' => 6, 'budgetSecondes' => $budget ) );
	$store->set(
		'joueurs',
		array(
			'joueurs' => array(
				array( 'id' => 'NICOT_VALENTIN', 'codeFFE' => null, 'nomComplet' => 'NICOT Valentin', 'club' => $tac, 'elo' => 2345, 'cat' => 'BenM', 'eloMajLe' => $maj ),
				array( 'id' => 'NICOT_ANNA', 'codeFFE' => null, 'ffeId' => '123', 'nomComplet' => 'NICOT Anna', 'club' => $tac, 'elo' => 1638, 'cat' => 'MinF', 'eloMajLe' => $maj ),
				array( 'id' => 'PRIE_ERIC', 'codeFFE' => null, 'fideId' => '999', 'nomComplet' => 'PRIE Eric', 'club' => "Club d'Echecs - Les Tours de Foix", 'elo' => 2328, 'cat' => 'SepM', 'eloMajLe' => $maj ),
				array( 'id' => 'S50192', 'codeFFE' => 'S50192', 'fideId' => '25059530', 'nomComplet' => 'PRAGGNANANDHAA R', 'club' => 'Cannes Echecs', 'elo' => 2763, 'cat' => 'SenM', 'eloMajLe' => $maj ),
			),
		)
	);
	return $store;
}

function par_ref( $etat ) {
	$o = array();
	foreach ( $etat['suivi'] as $s ) {
		$o[ $s['tournoi']['ref'] . '|' . $s['joueurId'] ] = $s;
	}
	ksort( $o );
	return $o;
}

function lire( $f ) {
	global $FIX;
	return file_get_contents( "$FIX/$f" );
}

// -------------------------------------------------------------------------
section( 'Lecture des pages (six dispositions rencontrées)' );
$f = Suivi_ChessResults::fiche_depuis_html( lire( 'cr-fiche-ouvert.html' ), '1450094', 13 );
ok( 'BHARATH SUBRAMANIYAM H,' === $f['nom'] && 2591.0 === $f['elo'] && 'Cannes Echecs' === $f['club'], 'ouvert : identité (nom, Elo 2591, club)' );
$r1 = $f['rondes'][0];
ok( 'blancs' === $r1['couleur'] && 'nulle' === $r1['resultat'] && 'PAK, Alexander' === $r1['adversaire']['nom'] && 2299.0 === $r1['adversaire']['elo'], 'ouvert : ronde 1 nulle avec les blancs contre PAK (2299)' );
$f = Suivi_ChessResults::fiche_depuis_html( lire( 'cr-fiche-9col.html' ), '1338669', 141 );
ok( 'Koepke, Christian' === $f['rondes'][0]['adversaire']['nom'] && 2314.0 === $f['rondes'][0]['adversaire']['elo'] && 'perdu' === $f['rondes'][0]['resultat'], '9 colonnes (Andorre) : parcours lu' );
$f = Suivi_ChessResults::fiche_depuis_html( lire( 'cr-fiche-ferme.html' ), '1448047', 2 );
$courts = array_filter( $f['rondes'], function ( $r ) { return strlen( $r['adversaire']['nom'] ) < 4; } );
ok( 9.5 === $f['points'] && count( $f['rondes'] ) >= 18 && 'Keymer, Vincent' === $f['rondes'][0]['adversaire']['nom'] && ! $courts, 'fermé (GCT) : en-tête décalé, aucun adversaire réduit à un titre' );
$f = Suivi_ChessResults::fiche_depuis_html( lire( 'cr-fiche-parent.html' ), '1436548', 6 );
ok( $f && 0 === count( $f['rondes'] ) && ! $f['points'], 'tournoi-chapeau : fiche lue, zéro ronde, zéro point' );
$f = Suivi_ChessResults::fiche_depuis_html( lire( 'cr-fiche-grec.html' ), '1454061', 6 );
ok( 4.5 === $f['points'] && 'CHATZIGEORGIOU, IOANNIS' === $f['rondes'][0]['adversaire']['nom'] && 'gagne' === $f['rondes'][0]['resultat'] && 'noirs' === $f['rondes'][0]['couleur'], 'noms grecs, colonnes après le résultat' );
ok( 0 === strpos( $f['tournoi'], 'ΠΑΝΕΛΛΗΝΙΑ' ), 'entités numériques décodées en UTF-8 (titre grec)' );
ok( null === Suivi_ChessResults::fiche_depuis_html( '<html>Seite nicht gefunden</html>', '1', 1 ), 'page qui n\'est pas une fiche : null' );
$r = Suivi_ChessResults::recherche_depuis_html( lire( 'cr-recherche.html' ) );
ok( 21 === count( $r ) && '1496391' === $r[0]['tnr'] && 3 === $r[0]['snr'] && '2026-09-13' === $r[0]['dateFin'] && 20.0 === $r[0]['nbJoueurs'], 'recherche réelle : 21 tournois, date au format ISO' );
ok( null === $r[1]['rang'], 'recherche : rang « - » lu comme inconnu' );
ok( 'https://s3.chess-results.com/tnr1.aspx?a=1' === Suivi_ChessResults::resoudre( '/tnr1.aspx?a=1', 'https://s3.chess-results.com/spielersuche.aspx?lan=1' ), 'redirection relative résolue' );

// -------------------------------------------------------------------------
section( 'Moteur : passage complet' );
Suivi_Horloge::fixer( '2026-11-01T12:00:00Z' );
$store = magasin();
$req   = array();
$trk   = new Suivi_Tracker( $store, transport( $req ), array( 'delaiChessResultsMs' => 0 ) );
$st    = $trk->rafraichir();
$e     = par_ref( $st );
ok( 8 === count( $st['suivi'] ), '8 engagements (3 FFE + 5 chess-results)' );
ok( 25 === count( $req ), '25 requêtes chess-results (23 comme l\'original + la fiche de test, lue pour connaître son nom complet)' );
ok( ! isset( $e['cr1529799|S50192'] ), 'fiche de test d\'organisateur écartée (GM senior dans un tournoi « Juventud … Femenino »)' );
ok( '999999' === $store->get( 'joueurs' )['joueurs'][1]['fideId'], 'ID FIDE d\'Anna lu sur sa fiche FFE' );
ok( ! isset( $e['cr1500000|PRIE_ERIC'] ) && isset( $e['71425|PRIE_ERIC'] ), 'PRIE : tournoi publié aussi par chess-results → la version FFE est gardée seule' );
ok( ! isset( $e['cr1338669|S50192'] ) && ! isset( $e['cr1338670|S50192'] ), 'tournoi de test et vue « Full list » écartés' );
$o = $e['cr1450094|S50192'];
ok( "Open International d'Aix en Provence - Trophée Dole 2026 - Masters (+2100)" === $o['tournoi']['nom'], 'nom complet repris de la fiche (la recherche le tronque)' );
ok( 'termine' === $o['statut'] && 'chess-results' === $o['source'] && 'cr1450094' === $o['tournoi']['ref'], 'engagement chess-results : source, ref crNNN, statut' );
ok( 'joueur' === $o['liens'][0]['cle'] && false !== strpos( $o['liens'][1]['href'], 'tnr1450094.aspx?lan=1' ), 'liens vers la fiche du joueur et du tournoi' );
$g = $e['cr1454061|S50192'];
ok( 'en_cours' === $g['statut'] && $g['rondes'], 'groupe en cours : statut et parcours' );
ok( false !== strpos( implode( ' ', $st['erreurs'] ), 'tnr1000001' ) || count( $st['erreurs'] ) >= 20, 'fiche introuvable (404) : signalée, sans casser le passage' );

section( 'Moteur : cache entre deux passages' );
$req = array();
$trk = new Suivi_Tracker( $store, transport( $req ), array( 'delaiChessResultsMs' => 0 ) );
$trk->rafraichir();
$posts = array_filter( $req, function ( $x ) { return 0 === strpos( $x, 'POST' ); } );
ok( ! $posts, 'recherches en cache : aucune nouvelle recherche' );
$lus = array_unique( array_map( function ( $x ) { return preg_match( '/tnr(\d+)/', $x, $m ) ? $m[1] : ''; }, $req ) );
sort( $lus );
ok( array( '1436548', '1454061', '1496391', '1500000', '1529799' ) === array_values( array_filter( $lus ) ), 'seules les fiches des tournois non finis sont relues' );
ok( ! in_array( '1450094', $lus, true ) && ! in_array( '1448047', $lus, true ), 'fiches des tournois finis non relues (cache 24 h)' );
ok( ! in_array( '1000001', $lus, true ), 'fiche en échec non retentée avant une heure' );
Suivi_Horloge::fixer( '2026-11-01T19:00:00Z' );
$req = array();
$trk->rafraichir();
ok( 3 === count( array_filter( $req, function ( $x ) { return 0 === strpos( $x, 'POST' ); } ) ), 'après 7 h (TTL 6 h) : une recherche par joueur ayant un ID FIDE' );

section( 'Moteur : panne de chess-results' );
Suivi_Horloge::fixer( '2026-11-01T20:00:00Z' );
$avant = par_ref( $store->get( 'etat' ) );
$trk   = new Suivi_Tracker( $store, transport( $req, array( 'tnr1454061' ) ), array( 'delaiChessResultsMs' => 0 ) );
$st    = $trk->rafraichir();
$apres = par_ref( $st );
ok( isset( $apres['cr1454061|S50192'] ) && json_encode( $apres['cr1454061|S50192']['rondes'] ) === json_encode( $avant['cr1454061|S50192']['rondes'] ), 'fiche en panne : la dernière version lue reste affichée' );
$cr = $store->get( 'chessresults' );
ok( isset( $cr['fiches']['1454061|6']['echecA'], $cr['fiches']['1454061|6']['fiche']['rondes'] ), 'panne notée (retentée dans une heure), fiche précédente conservée' );

section( 'Moteur : budget de temps et désactivation' );
Suivi_Horloge::fixer( '2026-11-01T12:00:00Z' );
$ref    = par_ref( ( new Suivi_Tracker( magasin(), transport(), array( 'delaiChessResultsMs' => 0 ) ) )->rafraichir() );
// 4 ms de latence simulée (~15 ms réels sous Windows) et 0,6 s par passage :
// ~40 requêtes par passage, le rapport d'un cron réel (25 s, ~0,7 s/requête).
$t      = transport();
$lent   = function ( $url, $opts ) use ( $t ) {
	usleep( 4000 );
	return $t( $url, $opts );
};
$store  = magasin();
$trk    = new Suivi_Tracker( $store, $lent, array( 'delaiChessResultsMs' => 0 ) );
$passes = 0;
$cr_vu  = false;
do {
	$st = $trk->rafraichir( array( 'budget_s' => 0.6 ) );
	$passes++;
	foreach ( $st['suivi'] as $s ) {
		$cr_vu = $cr_vu || ( 'chess-results' === $s['source'] && ! $st['scanComplet'] );
	}
} while ( ! $st['scanComplet'] && $passes < 300 );
echo "    sous budget de 0,6 s : $passes passages\n";
ok( $passes > 1 && true === $st['scanComplet'], 'scan étalé sur plusieurs passages, puis complet' );
ok( $cr_vu, 'chess-results avance pendant que la découverte FFE n\'est pas finie (part réservée)' );
ok( json_encode( par_ref( $st ) ) === json_encode( $ref ), 'résultat final identique à un passage unique sans limite' );
$store = magasin();
$cfg   = $store->get( 'config' );
$cfg['chessResultsActif'] = false;
$store->set( 'config', $cfg );
$req = array();
$st  = ( new Suivi_Tracker( $store, transport( $req ), array( 'delaiChessResultsMs' => 0 ) ) )->rafraichir();
ok( 3 === count( $st['suivi'] ) && ! $req, 'chess-results désactivé : aucune requête, FFE seule' );

section( 'Retrait d\'un joueur' );
$store = magasin();
$trk   = new Suivi_Tracker( $store, transport(), array( 'delaiChessResultsMs' => 0 ) );
$trk->rafraichir();
$trk->retirer_joueur( 'S50192' );
$trk->rafraichir();
$cr = $store->get( 'chessresults' );
ok( ! isset( $cr['parJoueur']['S50192'] ) && 1 === count( $cr['fiches'] ), 'mémoire chess-results et fiches du joueur oubliées' );

echo "\n" . ( $fail ? "ÉCHEC : $fail/$nb assertion(s)\n" : "SUCCÈS : $nb assertions passent\n" );
exit( $fail ? 1 : 0 );
