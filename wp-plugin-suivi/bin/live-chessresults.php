<?php
/**
 * Test EN DIRECT contre chess-results.com (réseau réel, via curl système).
 * Usage : php bin/live-chessresults.php [FIDE-ID]
 *
 * Valide ce que les fixtures ne peuvent pas couvrir : redirection vers un nœud
 * (S1, S2…), cookie de session et __VIEWSTATE acceptés par la recherche.
 * Quatre à cinq requêtes : à lancer ponctuellement (le site est tenu par une
 * seule personne).
 */

define( 'SUIVI_CLI', true );
require __DIR__ . '/../includes/class-suivi-ffe.php';
require __DIR__ . '/../includes/class-suivi-chessresults.php';
require __DIR__ . '/transport-curl-cli.php';

$fide = isset( $argv[1] ) ? $argv[1] : '25059530'; // Praggnanandhaa, licencié à Cannes
$cr   = new Suivi_ChessResults( suivi_transport_curl_cli( 0 ), 350 );
$au   = gmdate( 'Y-m-d' );
$du   = gmdate( 'Y-m-d', time() - 120 * 86400 );

$t0 = microtime( true );
$ts = $cr->tournois_du_joueur( $fide, $du, $au );
printf( "FIDE %s, tournois finissant du %s au %s : %d (en %.1fs)\n", $fide, $du, $au, count( $ts ), microtime( true ) - $t0 );
foreach ( array_slice( $ts, 0, 5 ) as $t ) {
	printf( "  tnr%s  %s  %s  (%s rondes, %s joueurs)\n", $t['tnr'], $t['dateFin'], $t['nom'], $t['nbRondes'], $t['nbJoueurs'] );
}
if ( ! $ts ) {
	exit( 0 );
}
$t0 = microtime( true );
$f  = $cr->fiche_par_tournoi( $ts[0]['tnr'], $ts[0]['snr'] );
printf( "\nFiche %s (en %.1fs) : %s — %s pts, place %s, %d rondes lues\n", $ts[0]['tnr'], microtime( true ) - $t0, $f['tournoi'], $f['points'], $f['place'], count( $f['rondes'] ) );
foreach ( array_slice( $f['rondes'], 0, 3 ) as $r ) {
	printf( "  R%d %s %s contre %s (%s)\n", $r['numero'], $r['couleur'], $r['resultat'], $r['adversaire']['nom'], $r['adversaire']['elo'] );
}
