<?php
/**
 * Noms de tournois : traduction des noms en écriture non latine (russe, grec,
 * arabe…), noms corrigés à la main depuis l'admin, détection des tournois de test.
 *
 * Pas de service de traduction en ligne (clé, compte, coût : hors de portée
 * des bénévoles). À la place : un glossaire des termes d'échecs courants, puis
 * une transcription en lettres latines pour le reste (extension intl si
 * présente). L'admin peut toujours renommer un tournoi.
 *
 * @package Suivi_Tournoi
 */

if ( ! defined( 'ABSPATH' ) && ! defined( 'SUIVI_CLI' ) ) {
	exit;
}

class Suivi_Noms {

	/** Expressions traduites, les plus longues d'abord (insensible à la casse). */
	private static $glossaire = array(
		// Russe
		'Чемпионат России'          => 'Championnat de Russie',
		'Первенство России'         => 'Championnat de Russie jeunes',
		'Кубок России'              => 'Coupe de Russie',
		'среди мужских команд'      => 'par équipes masculines',
		'среди женских команд'      => 'par équipes féminines',
		'среди юношей и девушек'    => 'jeunes',
		'среди юношей'              => 'garçons',
		'среди девушек'             => 'filles',
		'среди женщин'              => 'féminin',
		'среди мужчин'              => 'masculin',
		'по классическим шахматам'  => 'parties longues',
		'по быстрым шахматам'       => 'parties rapides',
		'по блицу'                  => 'blitz',
		'по шахматам'               => 'd’échecs',
		'Чемпионат'                 => 'Championnat',
		'Первенство'                => 'Championnat jeunes',
		'Кубок'                     => 'Coupe',
		'Турнир'                    => 'Tournoi',
		'Мемориал'                  => 'Mémorial',
		'открытый'                  => 'open',
		'этап'                      => 'étape',
		'года'                      => '',
		'России'                    => 'de Russie',
		'Москвы'                    => 'de Moscou',
		// Grec
		'ΠΑΝΕΛΛΗΝΙΑ'                => 'Panhelléniques',
		'ΠΑΝΕΛΛΗΝΙΟ'                => 'Panhellénique',
		'ΑΤΟΜΙΚΑ'                   => 'individuels',
		'ΠΡΩΤΑΘΛΗΜΑΤΑ'              => 'championnats',
		'ΠΡΩΤΑΘΛΗΜΑ'                => 'championnat',
		'ΝΕΩΝ ΑΝΔΡΩΝ'               => 'jeunes (garçons)',
		'ΝΕΩΝ ΓΥΝΑΙΚΩΝ'             => 'jeunes (filles)',
		'ΑΝΔΡΩΝ'                    => 'hommes',
		'ΓΥΝΑΙΚΩΝ'                  => 'femmes',
		'ΕΦΗΒΩΝ'                    => 'juniors',
		'ΚΑΤΩ'                      => 'moins de',
		'ΜΕΧΡΙ'                     => 'jusqu’à',
		'ΕΤΩΝ'                      => 'ans',
		'ΚΥΠΕΛΛΟ'                   => 'Coupe',
		'ΑΝΟΙΧΤΟ'                   => 'open',
		'ΤΟΥΡΝΟΥΑ'                  => 'tournoi',
		'ΣΚΑΚΙ'                     => 'échecs',
		// Arabe
		'بطولة'                     => 'Championnat',
		'دورة'                      => 'stage',
		'التحكيم'                   => 'd’arbitrage',
		'التجريبية'                 => '(essai)',
		'الشطرنج'                   => 'd’échecs',
		'المفتوحة'                  => 'open',
		'السريع'                    => 'rapide',
		'الخاطف'                    => 'blitz',
		'للسيدات'                   => 'féminin',
		'للناشئين'                  => 'jeunes',
	);

	/** Le nom contient-il une écriture non latine (cyrillique, grec, arabe, CJK…) ? */
	public static function non_latin( $nom ) {
		return (bool) preg_match( '/[\x{0370}-\x{03FF}\x{0400}-\x{04FF}\x{0590}-\x{06FF}\x{0900}-\x{0DFF}\x{0E00}-\x{0E7F}\x{3040}-\x{9FFF}\x{AC00}-\x{D7AF}]/u', (string) $nom );
	}

	/** Nom lisible en français : glossaire, puis transcription du reste. Inchangé s'il est déjà latin. */
	public static function traduire( $nom ) {
		$nom = (string) $nom;
		if ( ! self::non_latin( $nom ) ) {
			return $nom;
		}
		// Numéros de registre ajoutés par la fédération russe : sans intérêt.
		$s = preg_replace( '/\s*\(ЕКП[^)]*\)/u', '', $nom );
		$cles = array_keys( self::$glossaire );
		usort(
			$cles,
			function ( $a, $b ) {
				return strlen( $b ) - strlen( $a );
			}
		);
		foreach ( $cles as $c ) {
			$s = preg_replace( '/' . preg_quote( $c, '/' ) . '/iu', self::$glossaire[ $c ], $s );
		}
		if ( self::non_latin( $s ) && class_exists( 'Transliterator' ) ) {
			$t = Transliterator::create( 'Any-Latin; Latin-ASCII' );
			if ( $t ) {
				$s = preg_replace_callback(
					'/[^\x00-\x7F\x{C0}-\x{24F}\x{2019}]+/u',
					function ( $m ) use ( $t ) {
						return $t->transliterate( $m[0] );
					},
					$s
				);
			}
		}
		$s = trim( preg_replace( '/\s+/u', ' ', $s ) );
		return '' !== $s ? self::majuscule( $s ) : $nom;
	}

	private static function majuscule( $s ) {
		if ( function_exists( 'mb_strtoupper' ) ) {
			return mb_strtoupper( mb_substr( $s, 0, 1 ) ) . mb_substr( $s, 1 );
		}
		return ucfirst( $s );
	}

	/**
	 * Nom de tournoi de test (organisateur qui essaie le logiciel) : « chess
	 * tournament », « ABC », « Test 2 », ou un nom contenant test / essai /
	 * prueba / тест / تجريبي (essai) / δοκιμ…
	 */
	public static function de_test( $nom ) {
		$nom = trim( (string) $nom );
		return '' !== $nom && (
			preg_match( '/^(chess\s+)?(tournament|turnier|torneo|tournoi|abc|test)(\s*\d+)?$/iu', $nom )
			|| preg_match( '/\b(test|testing|prueba|demo|essai)\b/iu', $nom )
			|| preg_match( '/тест|تجريب|δοκιμ/iu', $nom )
		);
	}

	/** Nom à afficher : celui donné dans l'admin s'il existe, sinon la traduction. */
	public static function affiche( $ref, $nom, $renommes ) {
		return isset( $renommes[ (string) $ref ] ) && '' !== $renommes[ (string) $ref ] ? $renommes[ (string) $ref ] : self::traduire( $nom );
	}
}
