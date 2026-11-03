<?php
/**
 * Désinstallation (bouton « Supprimer » de l'admin) : efface les données du suivi.
 *
 * @package Suivi_Tournoi
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
require_once __DIR__ . '/includes/class-suivi-store.php';
require_once __DIR__ . '/includes/class-suivi-wp.php';
wp_clear_scheduled_hook( Suivi_WP::HOOK_CRON );
Suivi_Store_WP::tout_effacer();
