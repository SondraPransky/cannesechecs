<?php
/**
 * Plugin Name:       Suivi tournoi — Cannes Échecs
 * Description:       Suit en direct les joueurs du club sur les tournois FFE et chess-results.
 * Version:           0.1.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Cannes Échecs
 * Text Domain:       suivi-tournoi
 *
 * @package Suivi_Tournoi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SUIVI_TOURNOI_DIR', __DIR__ );

foreach ( array( 'ffe', 'elo', 'horloge', 'store', 'engagement', 'appariement', 'joueurs', 'journal', 'noms', 'chessresults', 'tracker', 'wp', 'admin', 'public' ) as $suivi_c ) {
	require_once SUIVI_TOURNOI_DIR . "/includes/class-suivi-$suivi_c.php";
}
unset( $suivi_c );

add_filter( 'cron_schedules', array( 'Suivi_WP', 'plannings' ) );
add_action( Suivi_WP::HOOK_CRON, array( 'Suivi_WP', 'passage' ) );
add_action( 'wp_ajax_' . Suivi_WP::AJAX_SCAN, array( 'Suivi_WP', 'ajax_scan' ) );
if ( is_admin() ) {
	Suivi_Admin::init();
}
Suivi_Public::init();

register_activation_hook( __FILE__, array( 'Suivi_WP', 'planifier' ) );
register_deactivation_hook( __FILE__, array( 'Suivi_WP', 'deplanifier' ) );

// Filet de sécurité : si l'événement a disparu (migration, base restaurée), on le replanifie.
add_action( 'init', array( 'Suivi_WP', 'planifier' ) );
