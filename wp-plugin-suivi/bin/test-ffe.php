<?php
/**
 * Test CLI du parsing FFE contre des fixtures HTML réelles.
 * Usage : php bin/test-ffe.php
 *
 * N'exécute aucune requête réseau : charge les pages sauvegardées dans
 * tests/fixtures/ et vérifie que le parsing PHP en extrait les bonnes données.
 */

define( 'SUIVI_CLI', true );
require __DIR__ . '/../includes/class-suivi-ffe.php';

$fixtures = __DIR__ . '/../tests/fixtures';
$fail     = 0;

function ok( $cond, $label ) {
	global $fail;
	echo ( $cond ? "  OK   " : "  FAIL " ) . $label . "\n";
	if ( ! $cond ) {
		$fail++;
	}
}

echo "=== Parsing : licenciés du club (ClubRef 311 = Cannes) ===\n";
$html    = file_get_contents( "$fixtures/club-311.html" );
$html    = Suivi_FFE::decode( $html, 'text/html; charset=utf-8' );
$joueurs = Suivi_FFE::parse_lignes_joueurs( $html );

ok( count( $joueurs ) >= 30, 'au moins 30 joueurs extraits (' . count( $joueurs ) . ' trouvés)' );
$j = $joueurs[0];
ok( ! empty( $j['codeFFE'] ), 'code fédéral présent (' . $j['codeFFE'] . ')' );
ok( ! empty( $j['nomComplet'] ), 'nom présent (' . $j['nomComplet'] . ')' );
ok( is_numeric( $j['elo'] ) && $j['elo'] > 0, 'Elo numérique (' . $j['elo'] . ')' );
ok( false !== stripos( $j['club'], 'cannes' ), 'club = Cannes (' . $j['club'] . ')' );
ok( null !== $j['id'], 'id interne extrait (' . $j['id'] . ')' );

echo "\n--- 8 premiers joueurs (triés par Elo décroissant côté FFE) ---\n";
foreach ( array_slice( $joueurs, 0, 8 ) as $p ) {
	printf(
		"  %-8s %-28s Elo %-5s  %s%s\n",
		$p['codeFFE'],
		mb_substr_fallback( $p['nomComplet'], 28 ),
		$p['elo'] !== null ? (int) $p['elo'] : '--',
		$p['cat'],
		$p['eloEstime'] ? '  (estimé)' : ''
	);
}

echo "\n--- Utilitaires ---\n";
ok( 'RENE O NEILL DUPONT' === Suivi_FFE::norm_name( "René O'Neill-Dupont" ), 'norm_name replie accents + nettoie' );
ok( 'EVARISTE' === Suivi_FFE::norm_name( 'Évariste' ) && 'GERARD' === Suivi_FFE::norm_name( 'Gérard' ), 'norm_name folde é/É de façon fiable' );
ok( 2.5 === Suivi_FFE::num( '2,5' ), 'num gère la virgule décimale' );
ok( 0.5 === Suivi_FFE::num( '.5' ), 'num gère le demi-point (.5)' );
ok( 'Open de Cannes' === Suivi_FFE::text( '  Open de <b>Cannes</b>  ' ), 'text strippe les balises' );

echo "\n" . ( $fail ? "ÉCHEC : $fail assertion(s) en échec\n" : "SUCCÈS : tout passe\n" );
exit( $fail ? 1 : 0 );

/** mb_substr n'étant pas dispo, troncature octet-safe simple pour l'affichage. */
function mb_substr_fallback( $s, $n ) {
	if ( strlen( $s ) <= $n ) {
		return $s;
	}
	// Coupe sans casser un caractère UTF-8 multi-octets.
	$cut = substr( $s, 0, $n );
	while ( $cut !== '' && ( ord( $cut[ strlen( $cut ) - 1 ] ) & 0xC0 ) === 0x80 ) {
		$cut = substr( $cut, 0, -1 );
	}
	return $cut;
}
