<?php
/**
 * Tests CLI du moteur de suivi (Suivi_Tracker) sur fixtures, horloge figée.
 * Usage : php bin/test-tracker.php
 *
 * Le résultat d'un passage complet a été validé par parité avec le moteur Node
 * d'origine. Ce test couvre en plus ce qui est propre au portage : passages
 * reprenables sous budget de temps, fil des nouveaux résultats, archivage,
 * gestion des joueurs suivis.
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

/** Transport : fixtures, sinon erreur 404 ; $delai_ms simule la latence réseau. */
function transport( $delai_ms = 0, &$compteur = null ) {
	global $FIX;
	return function ( $url ) use ( $FIX, $delai_ms, &$compteur ) {
		if ( null !== $compteur ) {
			$compteur++;
		}
		if ( $delai_ms ) {
			usleep( $delai_ms * 1000 );
		}
		if ( false !== strpos( $url, 'JOUEURCLUBREF' ) || false !== strpos( $url, 'Action=FFE' ) ) {
			$f = 'club-311.html';
		} elseif ( false !== strpos( $url, 'ListeTournois' ) ) {
			$f = 'liste-2026-10.html';
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
		return array( 'body' => file_get_contents( "$FIX/$f" ), 'content_type' => 'text/html; charset=utf-8' );
	};
}

/** Magasin de départ : 4 joueurs suivis, Elo frais (pas de relecture annuaire). */
function magasin() {
	$maj   = '2026-11-01T12:00:00.000Z';
	$tac   = 'Tremblay Athletique Club - TAC Echecs';
	$store = new Suivi_Store_Memoire();
	$store->set( 'config', array( 'joursAvant' => 15, 'joursApres' => 30, 'cacheParticipantsHeures' => 12, 'chessResultsActif' => false, 'budgetSecondes' => 0 ) );
	$store->set(
		'joueurs',
		array(
			'joueurs' => array(
				array( 'id' => 'NICOT_VALENTIN', 'codeFFE' => null, 'nomComplet' => 'NICOT Valentin', 'club' => $tac, 'elo' => 2345, 'cat' => 'BenM', 'eloMajLe' => $maj ),
				array( 'id' => 'NICOT_ANNA', 'codeFFE' => null, 'nomComplet' => 'NICOT Anna', 'club' => $tac, 'elo' => 1638, 'cat' => 'MinF', 'eloMajLe' => $maj ),
				array( 'id' => 'PRIE_ERIC', 'codeFFE' => null, 'nomComplet' => 'PRIE Eric', 'club' => "Club d'Echecs - Les Tours de Foix", 'elo' => 2328, 'cat' => 'SepM', 'eloMajLe' => $maj ),
				array( 'id' => 'S50192', 'codeFFE' => 'S50192', 'nomComplet' => 'PRAGGNANANDHAA R', 'club' => 'Cannes Echecs', 'elo' => 2763, 'cat' => 'SenM', 'eloMajLe' => $maj ),
			),
		)
	);
	return $store;
}

function engagement( $etat, $joueur ) {
	foreach ( $etat['suivi'] as $s ) {
		if ( $s['joueurId'] === $joueur ) {
			return $s;
		}
	}
	return null;
}

/** Suivi trié, comparable d'un magasin à l'autre. */
function suivi_trie( $etat ) {
	$s = $etat['suivi'];
	usort(
		$s,
		function ( $a, $b ) {
			return strcmp( $a['tournoi']['ref'] . $a['joueurId'], $b['tournoi']['ref'] . $b['joueurId'] );
		}
	);
	return json_encode( $s );
}

Suivi_Horloge::fixer( '2026-11-01T12:00:00Z' );

section( 'Passage complet (sans limite de budget)' );
$store = magasin();
$trk   = new Suivi_Tracker( $store, transport() );
$st    = $trk->rafraichir();
ok( 3 === count( $st['suivi'] ) && true === $st['scanComplet'], '3 engagements, scan complet' );
ok( 40 === $st['dernierScan']['tournoisExamines'], '40 tournois examinés' );
ok( null === engagement( $st, 'S50192' ), 'joueur engagé nulle part : aucun engagement' );
$v = engagement( $st, 'NICOT_VALENTIN' );
ok( 'en_cours' === $v['statut'] && 1.0 === $v['place'] && 5.0 === $v['points'] && 20 === $v['nbJoueurs'], 'NICOT Valentin : en cours, 1er/20, 5 pts' );
ok( 'NICOT Anna' === $v['rondes'][1]['adversaire']['nom'] && 'gagne' === $v['rondes'][1]['resultat'], 'grille : adversaire de la ronde 2 résolu (NICOT Anna)' );
ok( 2622 == $v['performance']['perf'] && 'FFE' === $v['performance']['source'], 'performance publiée par la FFE retenue' );
ok( 5 === $v['partieEnCours']['ronde'] && false === $v['partieEnCours']['enCours'], 'partie du moment : ronde 5, résultat saisi' );
$p = engagement( $st, 'PRIE_ERIC' );
ok( 6 === count( $p['rondes'] ) && 1 === $p['rondes'][0]['numero'] && 'noirs' === $p['rondes'][0]['couleur'], 'toutes-rondes : parcours reconstruit des pages de ronde (numéros, couleurs)' );
ok( null === $p['performance']['perf'], 'perf vide à 100 % (règle FIDE)' );
ok( 7 === $p['partieEnCours']['ronde'] && true === $p['partieEnCours']['enCours'] && 'LARAND Manuel' === $p['partieEnCours']['adversaire']['nom'], 'partie en cours détectée (ronde 7 contre LARAND)' );
$jl = $store->get( 'journal' );
ok( 0 === $st['dernierScan']['nouveauxResultats'] && 3 === count( $jl['connus'] ), '1er passage : parcours mémorisés sans rien annoncer' );

section( 'Classement en direct (etat)' );
$e = $trk->etat();
$d = array();
foreach ( $e['joueurs'] as $j ) {
	$d[ $j['id'] ] = $j['direct'];
}
ok( 2.68 === $d['NICOT_VALENTIN']['delta'] && 20 === $d['NICOT_VALENTIN']['tournois'][0]['k'], 'Valentin : +2.68 à K=20' );
ok( 49.08 === $d['NICOT_ANNA']['delta'] && 40 === $d['NICOT_ANNA']['tournois'][0]['k'], 'Anna (minime) : +49.08 à K=40' );
ok( null === $d['S50192'], 'pas de direct sans tournoi en cours' );
ok( ! isset( $e['joueurs'][0]['clubRef'] ) && ! isset( $e['joueurs'][0]['historiqueElo'] ), 'projection publique : clés internes masquées' );

section( 'Fil des nouveaux résultats' );
$jl                                       = $store->get( 'journal' );
$jl['connus']['72680|NICOT_VALENTIN']    = array( 1 => 'gagne', 2 => 'gagne', 3 => 'gagne', 4 => 'gagne' ); // ronde 5 « pas encore vue »
$store->set( 'journal', $jl );
$st2 = $trk->rafraichir();
$jl  = $store->get( 'journal' );
ok( 1 === $st2['dernierScan']['nouveauxResultats'], 'la ronde 5 est annoncée une fois' );
$ev = $jl['evenements'][0];
ok( 'NICOT_VALENTIN' === $ev['joueurId'] && 5 === $ev['ronde'] && 'gagne' === $ev['resultat'] && 'ROBERT Louis' === $ev['adversaire']['nom'], 'événement : ronde 5 gagnée contre ROBERT Louis' );
ok( false !== strpos( $ev['lien'], 'Action=05' ), 'événement : lien vers la page de la ronde' );
ok( 0 === $trk->rafraichir()['dernierScan']['nouveauxResultats'], 'jamais réannoncé au passage suivant' );

section( 'Fin de tournoi : archivage et bilan' );
Suivi_Horloge::fixer( '2026-11-05T12:00:00Z' );
$st3 = $trk->rafraichir();
ok( 'termine' === engagement( $st3, 'PRIE_ERIC' )['statut'], 'tournoi fini : statut terminé' );
ok( true === engagement( $st3, 'PRIE_ERIC' )['partieEnCours']['resultatManquant'], 'ronde 7 jamais saisie : signalée « résultat manquant »' );
$a = $store->get( 'archive' );
ok( 16 === count( $a['parties'] ) && 3 === count( $a['cles'] ), '16 parties archivées (5 + 5 + 6), 3 paires' );
$trk->rafraichir();
ok( 16 === count( $store->get( 'archive' )['parties'] ), 'pas de doublon d\'archive au passage suivant' );
$b = $trk->bilan_joueur( 'PRIE_ERIC' );
ok( 6 === $b['parties'] && 6.0 === (float) $b['points'] && 1 === $b['tournois'], 'bilan PRIE : 6 parties, 6 pts, 1 tournoi' );
ok( 'WEERHEIJM Harry' === $b['meilleureVictoire']['adversaire'] && 1931.0 === (float) $b['meilleureVictoire']['elo'], 'bilan : meilleure victoire (WEERHEIJM 1931)' );
ok( 0 === $trk->bilan_joueur( 'S50192' )['parties'], 'bilan vide pour un joueur sans partie' );

section( 'Budget de temps : passages reprenables' );
Suivi_Horloge::fixer( '2026-11-01T12:00:00Z' );
$ref_suivi = suivi_trie( ( new Suivi_Tracker( magasin(), transport() ) )->rafraichir() );

$vide  = magasin();
$st0   = ( new Suivi_Tracker( $vide, transport() ) )->rafraichir( array( 'budget_s' => 0.000001 ) );
ok( false === $st0['scanComplet'] && 0 === count( $st0['suivi'] ), 'budget nul : rien lu, pas d\'erreur, scan marqué incomplet' );

$store   = magasin();
$appels  = 0;
$trk     = new Suivi_Tracker( $store, transport( 4, $appels ) );
$passes  = 0;
$partiel = false;
do {
	$st = $trk->rafraichir( array( 'budget_s' => 0.25 ) );
	$passes++;
	if ( ! $st['scanComplet'] && count( $st['suivi'] ) ) {
		$partiel = true; // état déjà utile avant la fin du scan
	}
} while ( ! $st['scanComplet'] && $passes < 300 );
echo "    scan sous budget de 0,25 s : $passes passages, $appels requêtes\n";
ok( $passes > 1, "le scan s'est étalé sur plusieurs passages" );
ok( $partiel, 'état déjà utile avant la fin du scan (direct d\'abord)' );
ok( true === $st['scanComplet'], 'le scan finit par être complet' );
ok( suivi_trie( $st ) === $ref_suivi, 'résultat final identique à un passage unique sans limite' );

section( 'Gestion des joueurs suivis' );
$store = magasin();
$trk   = new Suivi_Tracker( $store, transport() );
$trk->rafraichir();
$trk->retirer_joueur( 'NICOT_ANNA' );
$jl = $store->get( 'journal' );
ok( null === engagement( $store->get( 'etat' ), 'NICOT_ANNA' ) && ! isset( $jl['connus']['72680|NICOT_ANNA'] ), 'retrait : engagement et mémoire du fil oubliés' );
$err = '';
try {
	$trk->retirer_joueur( 'INCONNU' );
} catch ( RuntimeException $x ) {
	$err = $x->getMessage();
}
ok( 'Joueur inconnu' === $err, 'retrait d\'un joueur inconnu refusé' );
$r   = $trk->ajouter_club( 311, 2700 );
$ids = array_column( $store->get( 'joueurs' )['joueurs'], 'id' );
ok( 'S50194' === $r['ajoutes'][0]['id'] && 1 === count( $r['ajoutes'] ), 'ajout du club au-dessus de 2700 : GUKESH ajouté, Praggnanandhaa déjà suivi' );
ok( 'S50192' === $ids[0] && 'S50194' === $ids[1], 'joueurs triés par Elo décroissant' );
$j = $trk->ajouter_joueur( array( 'codeFFE' => 'Y50486' ) );
ok( 'ADHIBAN B.' === $j['nomComplet'] && 2573.0 === $j['elo'], 'ajout par code fédéral (ADHIBAN, 2573)' );
$err = '';
try {
	$trk->ajouter_joueur( array( 'codeFFE' => 'Y50486' ) );
} catch ( RuntimeException $x ) {
	$err = $x->getMessage();
}
ok( false !== strpos( $err, 'déjà suivi' ), 'doublon refusé' );

section( 'Masquer un tournoi pour un joueur (depuis l\'admin)' );
Suivi_Horloge::fixer( '2026-11-01T12:00:00Z' );
$store = magasin();
$trk   = new Suivi_Tracker( $store, transport() );
$trk->rafraichir();
$trk->masquer( '72680', 'NICOT_ANNA' );
$m = $store->get( 'masques' );
ok( null === engagement( $store->get( 'etat' ), 'NICOT_ANNA' ) && 'NICOT Anna' === $m['liste']['72680|NICOT_ANNA']['joueur'], 'masqué : retiré tout de suite, nom gardé pour l\'admin' );
ok( null === engagement( $trk->rafraichir(), 'NICOT_ANNA' ) && null !== engagement( $store->get( 'etat' ), 'NICOT_VALENTIN' ), 'passage suivant : reste masqué, les autres joueurs du tournoi non' );
$trk->demasquer( '72680|NICOT_ANNA' );
ok( null !== engagement( $trk->rafraichir(), 'NICOT_ANNA' ), 'réaffiché : revient au passage suivant' );
$trk->masquer( '72680', 'NICOT_ANNA' );
$trk->retirer_joueur( 'NICOT_ANNA' );
ok( ! $store->get( 'masques' )['liste'], 'retrait du joueur : ses masques sont oubliés' );

section( 'Listes de mois : relecture selon le mois, progression du premier scan' );
Suivi_Horloge::fixer( '2026-11-01T12:00:00Z' );
$urls  = array();
$base  = transport();
$espion = function ( $url, $opts = array() ) use ( $base, &$urls ) {
	if ( false !== strpos( $url, 'ListeTournois' ) && empty( $opts['method'] ) ) {
		$urls[] = preg_match( '/Mois=(\d+)/', $url, $m ) ? (int) $m[1] : 0;
	}
	return $base( $url, $opts );
};
$store = magasin();
$trk   = new Suivi_Tracker( $store, $espion );
$pr0   = $trk->progression();
ok( 0 === $pr0['fait'] && 3 === $pr0['detail']['mois'][1], 'progression avant tout passage : rien de fait, 3 mois à lire' );
$trk->rafraichir();
$pr = $trk->progression();
ok( $pr['fait'] === $pr['total'] && $pr['total'] > 3 && true === $pr['scanComplet'], 'progression après un passage complet : tout est lu' );
$urls = array();
Suivi_Horloge::fixer( '2026-11-01T15:00:00Z' ); // +3 h
$trk->rafraichir();
ok( array( 11 ) === $urls, '+3 h : seul le mois en cours est relu (2 h) ; précédent (12 h) et suivant (6 h) gardés' );
$urls = array();
Suivi_Horloge::fixer( '2026-11-01T19:00:00Z' ); // +7 h
$trk->rafraichir();
sort( $urls );
ok( array( 11, 12 ) === $urls, '+7 h : mois en cours et mois suivant relus, pas le précédent' );

echo "\n" . ( $fail ? "ÉCHEC : $fail/$nb assertion(s)\n" : "SUCCÈS : $nb assertions passent\n" );
exit( $fail ? 1 : 0 );
