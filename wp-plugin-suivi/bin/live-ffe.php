<?php
/**
 * Test EN DIRECT contre echecs.asso.fr (réseau réel, via curl système).
 * Usage : php bin/live-ffe.php
 *
 * Valide ce que les fixtures ne peuvent pas couvrir : la pagination ASP.NET
 * (__doPostBack) des listes longues. À lancer ponctuellement, pas en boucle.
 */

define( 'SUIVI_CLI', true );
require __DIR__ . '/../includes/class-suivi-ffe.php';
require __DIR__ . '/transport-curl-cli.php';

$ffe = new Suivi_FFE( suivi_transport_curl_cli() );

$t0   = microtime( true );
$club = $ffe->joueurs_du_club( 311 );
$codes = array_column( $club, 'codeFFE' );
printf( "Club 311 (Cannes) : %d licenciés (%d codes uniques) en %.1fs\n", count( $club ), count( array_unique( $codes ) ), microtime( true ) - $t0 );
$avec_elo = array_filter( $club, function ( $j ) { return null !== $j['elo']; } );
printf( "  dont %d avec Elo ; derniers de la liste : %s\n", count( $avec_elo ),
	implode( ', ', array_map( function ( $j ) { return $j['nomComplet'] . ' (' . ( null === $j['elo'] ? '--' : (int) $j['elo'] ) . ')'; }, array_slice( $club, -3 ) ) ) );

$t0    = microtime( true );
$liste = $ffe->liste_tournois_mois( 10, 2026 );
printf( "\nTournois d'octobre 2026 : %d (toutes pages) en %.1fs\n", count( $liste ), microtime( true ) - $t0 );
$dates = array_filter( array_column( $liste, 'dateDebut' ) );
printf( "  dates de %s à %s ; sans date : %d\n", min( $dates ), max( $dates ), count( $liste ) - count( $dates ) );
$alpes = array_filter( $liste, function ( $t ) { return '06' === $t['dept']; } );
printf( "  dont %d dans les Alpes-Maritimes (06)\n", count( $alpes ) );
