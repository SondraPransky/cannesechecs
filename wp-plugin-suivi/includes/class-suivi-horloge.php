<?php
/**
 * Horloge du suivi, figeable pour les tests.
 *
 * Tout le moteur raisonne en dates ISO UTC ("2026-11-01"), comme l'original
 * (`today()` / `shift()` de src/engagement.js). Les comparaisons de dates se
 * font sur ces chaînes, dont l'ordre lexicographique est l'ordre chronologique.
 *
 * @package Suivi_Tournoi
 */

if ( ! defined( 'ABSPATH' ) && ! defined( 'SUIVI_CLI' ) ) {
	exit;
}

class Suivi_Horloge {

	/** @var int|null Horodatage figé (secondes), ou null pour l'heure réelle. */
	private static $fixe = null;

	/** Fige l'horloge sur une date ISO ("2026-11-01T12:00:00Z"), ou la libère (null). */
	public static function fixer( $iso ) {
		self::$fixe = null === $iso ? null : self::parse( $iso );
	}

	/** Maintenant, en secondes. */
	public static function ts() {
		return null === self::$fixe ? time() : self::$fixe;
	}

	/** Date du jour UTC, "YYYY-MM-DD". */
	public static function today() {
		return gmdate( 'Y-m-d', self::ts() );
	}

	/** Maintenant au format de Date.toISOString() : "2026-11-01T12:00:00.000Z". */
	public static function iso() {
		return gmdate( 'Y-m-d\TH:i:s', self::ts() ) . '.000Z';
	}

	/** Décale une date ISO d'un nombre de jours. */
	public static function shift( $iso, $jours ) {
		return gmdate( 'Y-m-d', self::parse( $iso ) + (int) $jours * 86400 );
	}

	/** Date ou date-heure ISO -> secondes UTC (comme Date.parse). */
	public static function parse( $iso ) {
		$iso = (string) $iso;
		if ( preg_match( '/^(\d{4})-(\d{2})-(\d{2})$/', $iso, $m ) ) {
			return gmmktime( 0, 0, 0, (int) $m[2], (int) $m[3], (int) $m[1] );
		}
		$t = strtotime( $iso );
		return false === $t ? 0 : $t;
	}
}
