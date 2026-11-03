<?php
/**
 * Tests CLI du cœur data (parsing FFE + estimation Elo) contre des fixtures réelles.
 * Usage : php bin/test-ffe.php
 *
 * Aucune requête réseau : les pages FFE sont sauvegardées dans tests/fixtures/.
 * Les valeurs attendues ont été validées par parité avec le code JS d'origine
 * (suivi-tournoi/src/ffe.js et elo.js), sauf la correction volontaire des noms
 * titrés en grille Berger (voir Suivi_FFE::nom_berger).
 */

define( 'SUIVI_CLI', true );
require __DIR__ . '/../includes/class-suivi-ffe.php';
require __DIR__ . '/../includes/class-suivi-elo.php';

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
function fx( $f ) {
	global $FIX;
	return Suivi_FFE::decode( file_get_contents( "$FIX/$f" ), 'text/html; charset=utf-8' );
}
function section( $t ) {
	echo "• $t\n";
}

// Transport simulé : sert les fixtures selon l'URL demandée (GET comme POST).
$http = function ( $url ) use ( $FIX ) {
	if ( false !== strpos( $url, 'JOUEURCLUBREF' ) ) {
		$f = 'club-311.html';
	} elseif ( false !== strpos( $url, 'ListeTournois' ) ) {
		$f = 'liste-2026-10.html';
	} elseif ( preg_match( '/FicheTournoi\.aspx\?Ref=(\d+)/', $url, $m ) ) {
		$f = "fiche-{$m[1]}.html";
	} elseif ( preg_match( '#Tournois/Id/(\d+)/\d+&Action=(\w+)#', $url, $m ) ) {
		$f = "res-{$m[1]}-{$m[2]}.html";
	} else {
		throw new RuntimeException( "pas de fixture pour $url" );
	}
	return array( 'body' => file_get_contents( "$FIX/$f" ), 'content_type' => 'text/html; charset=utf-8' );
};
$ffe = new Suivi_FFE( $http );

section( 'Licenciés du club 311 (Cannes)' );
$club = $ffe->joueurs_du_club( 311 );
ok( 40 === count( $club ), 'club : 40 joueurs' );
ok( 'S50192' === $club[0]['codeFFE'] && 'PRAGGNANANDHAA R' === $club[0]['nomComplet'], 'club : 1er = Praggnanandhaa' );
ok( 2763.0 === $club[0]['elo'] && '617797' === $club[0]['id'] && '311' === $club[0]['clubRef'], 'club : Elo, id, clubRef' );
ok( false !== stripos( $club[0]['club'], 'cannes' ), 'club : nom du club' );
$n_club = 0;
$coupe  = new Suivi_FFE(
	function ( $url, $opts = array() ) use ( $http, &$n_club ) {
		if ( ++$n_club > 2 ) {
			throw new RuntimeException( 'budget' );
		}
		return $http( $url, $opts );
	}
);
$rc = null;
try {
	$coupe->joueurs_du_club( 311, $rc );
} catch ( RuntimeException $e ) {
	unset( $e );
}
$n_suite = 0;
$suite   = new Suivi_FFE(
	function ( $url, $opts = array() ) use ( $http, &$n_suite ) {
		$n_suite++;
		return $http( $url, $opts );
	}
);
ok( 2 === $rc['page'] && 40 === count( $suite->joueurs_du_club( 311, $rc ) ) && $n_suite >= 1, 'club interrompu page 2 puis repris sans relire la page 1' );

section( 'Liste des tournois du mois' );
$liste = Suivi_FFE::parse_liste_page( fx( 'liste-2026-10.html' ) );
ok( 40 === count( $liste ), 'liste : 40 tournois sur la page' );
ok( '72680' === $liste[0]['ref'] && '2026-10-31' === $liste[0]['dateDebut'] && '58' === $liste[0]['dept'], 'liste : ref, date, département' );
ok( 'SAINT BRISSON' === $liste[0]['ville'], 'liste : ville' );
ok( 40 === count( $ffe->liste_tournois_mois( 10, 2026 ) ), 'liste : pagination dédoublonnée' );

// Reprise : coupée après 4 requêtes (page 1 + 3 pages suivantes), puis reprise.
$compte = 0;
$coupe  = new Suivi_FFE(
	function ( $url, $opts = array() ) use ( $http, &$compte ) {
		if ( ++$compte > 4 ) {
			throw new RuntimeException( 'budget' );
		}
		return $http( $url, $opts );
	}
);
$reprise = null;
try {
	$coupe->liste_tournois_mois( 10, 2026, $reprise );
} catch ( RuntimeException $e ) {
	unset( $e );
}
ok( 4 === $reprise['page'] && 10 === $reprise['max'] && '' !== $reprise['champs']['__VIEWSTATE'], 'liste interrompue : progression gardée (page 4/10, jeton de pagination)' );
$posts  = 0;
$suite  = new Suivi_FFE(
	function ( $url, $opts = array() ) use ( $http, &$posts ) {
		$posts += ( isset( $opts['method'] ) && 'POST' === $opts['method'] ) ? 1 : 0;
		return $http( $url, $opts );
	}
);
ok( 40 === count( $suite->liste_tournois_mois( 10, 2026, $reprise ) ) && 6 === $posts, 'liste reprise : continue à la page 5 (6 pages restantes), même résultat' );

section( 'Fiche tournoi' );
$f = $ffe->fiche_tournoi( 72680 );
ok( 5.0 === $f['nbRondes'], 'fiche : 5 rondes' );
ok( '2026-10-31' === $f['dateDebut'] && '2026-11-01' === $f['dateFin'], 'fiche : dates début/fin' );
ok( "60' + [30\"]" === $f['cadence'], 'fiche : cadence' );
ok( in_array( 'Ga', $f['actions'], true ) && in_array( 'Cl', $f['actions'], true ) && ! in_array( 'Berger', $f['actions'], true ), 'fiche : vues publiées (suisse)' );
ok( in_array( 'Berger', $ffe->fiche_tournoi( 71425 )['actions'], true ), 'fiche : vue Berger (toutes rondes)' );

section( 'Pages de résultats Papi' );
$ls = Suivi_FFE::participants_depuis_html( fx( 'res-72680-Ls.html' ) );
ok( 20 === count( $ls['joueurs'] ) && '' !== $ls['titre'], 'participants : 20 + titre' );
$cl = Suivi_FFE::classement_depuis_html( fx( 'res-72680-Cl.html' ) );
ok( 20 === count( $cl['joueurs'] ) && 5 === $cl['ronde'], 'classement : 20, après la ronde 5' );
ok( 1.0 === $cl['joueurs'][0]['place'] && 'NICOT Valentin' === $cl['joueurs'][0]['nom'] && 5.0 === $cl['joueurs'][0]['points'], 'classement : 1er NICOT 5 pts' );
$ga = Suivi_FFE::grille_depuis_html( fx( 'res-72680-Ga.html' ) );
$g0 = $ga['joueurs'][0];
ok( 20 === count( $ga['joueurs'] ) && 5 === count( $g0['rondes'] ), 'grille : 20 joueurs × 5 rondes' );
ok( 'gagne' === $g0['rondes'][0]['resultat'] && 14 === $g0['rondes'][0]['adversaire'] && 'noirs' === $g0['rondes'][0]['couleur'], 'grille : ronde 1 = gagné contre n°14 avec les noirs' );
ok( 2622.0 === $g0['perfFFE'] && 5.0 === $g0['points'], 'grille : perf 2622, 5 pts' );
$r1 = Suivi_FFE::ronde_depuis_html( fx( 'res-72680-01.html' ), 1 );
ok( 10 === count( $r1['parties'] ) && false === $r1['appariementSeul'], 'ronde 1 : 10 parties, résultats saisis' );
ok( 'BARBIER Francois' === $r1['parties'][0]['blancs']['nom'] && '0 - 1' === $r1['parties'][0]['resultat'] && false === $r1['parties'][0]['enCours'], 'ronde 1 : échiquier 1' );
ok( Suivi_FFE::fichier_absent( fx( 'res-72680-Berger.html' ) ), 'fichier absent détecté (Berger d\'un suisse)' );

section( 'Grille Berger (toutes rondes)' );
$b = Suivi_FFE::berger_depuis_html( fx( 'res-71425-Berger.html' ) );
ok( 8 === count( $b['joueurs'] ) && 7 === $b['ronde'], 'berger : 8 joueurs, après la ronde 7' );
ok( 'PRIE Eric' === $b['joueurs'][0]['nom'] && 'g' === $b['joueurs'][0]['titre'], 'berger : titré "g Eric PRIE" -> PRIE Eric [g]' );
ok( 'MARTINE Jean-Marie' === $b['joueurs'][5]['nom'], 'berger : prénom composé' );
$r = $b['joueurs'][0]['resultats'][0];
ok( 'gagne' === $r['resultat'] && 'WEERHEIJM Harry' === $r['adversaire']['nom'], 'berger : adversaires résolus par nom' );
ok( array( 'DUPONT Jean Pierre', null ) === Suivi_FFE::nom_berger( 'Jean Pierre DUPONT' ), 'nom_berger : prénom en 2 mots' );
ok( array( 'LÉVY Anna', 'm' ) === Suivi_FFE::nom_berger( 'm Anna LÉVY' ), 'nom_berger : titre + accent' );

section( 'Utilitaires' );
ok( 'RENE O NEILL DUPONT' === Suivi_FFE::norm_name( "René O'Neill-Dupont" ), 'norm_name' );
ok( 'EVARISTE' === Suivi_FFE::norm_name( 'Évariste' ) && 'GERARD' === Suivi_FFE::norm_name( 'Gérard' ), 'norm_name : accents' );
ok( 2.5 === Suivi_FFE::num( '2,5' ) && 0.5 === Suivi_FFE::num( '.5' ) && null === Suivi_FFE::num( '' ), 'num' );
ok( 'Open de Cannes' === Suivi_FFE::text( '  Open de <b>Cannes</b>  ' ) && '1.5' === Suivi_FFE::text( '1&frac12;' ), 'text (dont demi-point)' );
ok( 2 === Suivi_FFE::mois_to_num( 'févr.' ) && 8 === Suivi_FFE::mois_to_num( 'Août' ) && 12 === Suivi_FFE::mois_to_num( 'décembre' ), 'mois_to_num' );
ok( '2026-10-31' === Suivi_FFE::parse_date_fr( 'samedi 31 octobre 2026' ), 'parse_date_fr' );

section( 'Estimation Elo' );
$k = array();
foreach ( array( array( 2450, 'SenM' ), array( 1800, 'PupM' ), array( 2350, 'MinM' ), array( 1500, 'SenF' ), array( 2299, 'CadF' ), array( 2100, 'JunM' ) ) as $p ) {
	$k[] = Suivi_Elo::coefficient_k( $p[0], $p[1] );
}
ok( array( 10, 40, 20, 20, 40, 20 ) === $k, 'coefficient K (2400+, jeunes, junior)' );
ok( 10 === Suivi_Elo::coefficient_k( 2200, 'SenM', true ), 'K=10 à vie après 2400' );
ok( 0.5 === Suivi_Elo::score_attendu( 1500, 1500 ), 'score attendu à Elo égal' );
$v = Suivi_Elo::variation( 1650, 'MinM', array(
	array( 'resultat' => 'gagne', 'eloAdverse' => 1700 ), array( 'resultat' => 'nulle', 'eloAdverse' => 1550 ),
	array( 'resultat' => 'perdu', 'eloAdverse' => 1820 ), array( 'resultat' => 'gagne', 'eloAdverse' => 1499 ),
) );
ok( 18.15 === $v, 'variation sur 4 parties = 18.15' );
ok( array( 120, 20, 5, 60, null, 120 ) === array_map( array( 'Suivi_Elo', 'minutes_de' ), array( "1h30/40 - 30' + [30'']", "15' + [5'']", "3' + [2'']", '1h', '', "2h + 30''" ) ), 'minutes_de (cadences)' );
ok( 'blitz' === Suivi_Elo::nature_tournoi( array( 'cadence' => '', 'nom' => 'Blitz du vendredi' ) )['nature'], 'nature : blitz d\'après le nom' );
ok( 'classique' === Suivi_Elo::nature_tournoi( array( 'cadence' => "1h30/40 - 30' + [30'']", 'nom' => 'Blitz trompeur' ) )['nature'], 'nature : la cadence prime sur le nom' );
ok( true === Suivi_Elo::est_maitre( 'mi' ) && false === Suivi_Elo::est_maitre( 'MIF' ), 'est_maitre : égalité stricte (MIF ≠ MI)' );
$d = Suivi_Elo::direct_joueur( array( 'elo' => 1650, 'cat' => 'MinM' ), array(
	array( 'statut' => 'en_cours', 'eloTournoi' => 1640, 'tournoi' => array( 'ref' => 'A', 'nom' => 'Open A', 'cadence' => "1h30/40 - 30' + [30'']" ),
		'rondes' => array( array( 'resultat' => 'gagne', 'adversaire' => array( 'elo' => 1700 ) ), array( 'resultat' => 'perdu', 'adversaire' => array( 'elo' => 1800 ) ) ) ),
	array( 'statut' => 'en_cours', 'tournoi' => array( 'ref' => 'B', 'nom' => 'Blitz B', 'cadence' => "3' + [2'']" ),
		'rondes' => array( array( 'resultat' => 'gagne', 'adversaire' => array( 'elo' => 1500 ) ) ) ),
) );
ok( 12.03 === $d['delta'] && 1662.0 === (float) $d['live'] && 2 === $d['parties'], 'direct : +12.03 (classique), blitz exclu' );
ok( null === $d['tournois'][1]['delta'], 'direct : le blitz ne compte pas' );
ok( null === Suivi_Elo::direct_joueur( array( 'elo' => 1650 ), array() ), 'direct : null sans tournoi en cours' );

echo "\n" . ( $fail ? "ÉCHEC : $fail/$nb assertion(s)\n" : "SUCCÈS : $nb assertions passent\n" );
exit( $fail ? 1 : 0 );
