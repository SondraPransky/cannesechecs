<?php
/**
 * Persistance du suivi — portage de src/store.js.
 *
 * Le moteur ne lit et n'écrit que des documents nommés (config, joueurs, cache,
 * etat, journal, archive). OÙ ils sont rangés n'est pas son affaire : en
 * WordPress ce sera la base (options / tables), en test un dossier de JSON ou
 * la mémoire. Les valeurs manquantes sont complétées par les défauts, comme
 * après une montée de version dans l'original.
 *
 * @package Suivi_Tournoi
 */

if ( ! defined( 'ABSPATH' ) && ! defined( 'SUIVI_CLI' ) ) {
	exit;
}

interface Suivi_Store_Interface {
	/** Document complet, complété par ses valeurs par défaut. */
	public function get( $cle );

	/** Remplace le document. */
	public function set( $cle, $valeur );
}

/** Défauts des documents, partagés par toutes les implémentations. */
class Suivi_Store_Defauts {

	public static function de( $cle ) {
		switch ( $cle ) {
			case 'config':
				return array(
					// Fenêtre de scan autour d'aujourd'hui, en jours.
					'joursAvant'              => 30,
					'joursApres'              => 14,
					// Intervalle du rafraîchissement automatique, en minutes.
					'intervalleMinutes'       => 8,
					// Âge max du cache des participants d'un tournoi en cours, en heures.
					'cacheParticipantsHeures' => 12,
					// Complément chess-results (tournois hors homologation FFE).
					'chessResultsActif'       => true,
					'chessResultsTtlHeures'   => 6,
					// Durée max d'un passage, en secondes (contrainte des crons WordPress).
					'budgetSecondes'          => 25,
				);
			case 'joueurs':
				return array( 'joueurs' => array() );
			case 'cache':
				return array(
					'tournois'     => array(), // ref -> données de la liste du mois
					'fiches'       => array(), // ref -> fiche tournoi + 'lueA'
					'mois'         => array(), // "AAAA-M" -> ['a' => ts] : liste lue le
					'participants' => array(), // ref -> ['joueurs' => [], 'fetchedAt' => ts]
					'details'      => array(), // ref -> détail du tournoi + 'luA'
					'concernes'    => array(), // refs où un joueur suivi est engagé
				);
			case 'etat':
				return array(
					'dernierScan'             => null,
					'dernierRafraichissement' => null,
					'suivi'                   => array(),
					'erreurs'                 => array(),
					'scanComplet'             => true,
				);
			case 'journal':
				// `connus` retient ce qui a déjà été vu pour ne pas réannoncer.
				return array( 'connus' => array(), 'evenements' => array() );
			case 'archive':
				return array( 'parties' => array(), 'cles' => array() );
			case 'chessresults':
				return array( 'parJoueur' => array() );
		}
		return array();
	}

	/** Complète les clés manquantes d'un document par ses défauts. */
	public static function completer( $cle, $valeur ) {
		$defaut = self::de( $cle );
		return is_array( $valeur ) ? array_merge( $defaut, $valeur ) : $defaut;
	}
}

/** Stockage en mémoire (tests). */
class Suivi_Store_Memoire implements Suivi_Store_Interface {

	private $docs = array();

	public function get( $cle ) {
		return Suivi_Store_Defauts::completer( $cle, isset( $this->docs[ $cle ] ) ? $this->docs[ $cle ] : null );
	}

	public function set( $cle, $valeur ) {
		$this->docs[ $cle ] = $valeur;
	}
}

/** Stockage en fichiers JSON dans un dossier (CLI, outils de dev). */
class Suivi_Store_Fichiers implements Suivi_Store_Interface {

	private $dossier;

	public function __construct( $dossier ) {
		$this->dossier = rtrim( $dossier, '/\\' );
		if ( ! is_dir( $this->dossier ) ) {
			mkdir( $this->dossier, 0777, true );
		}
	}

	private function chemin( $cle ) {
		return $this->dossier . '/' . $cle . '.json';
	}

	public function get( $cle ) {
		$f = $this->chemin( $cle );
		$v = is_file( $f ) ? json_decode( file_get_contents( $f ), true ) : null;
		return Suivi_Store_Defauts::completer( $cle, $v );
	}

	/** Écriture atomique : fichier temporaire puis renommage. */
	public function set( $cle, $valeur ) {
		$f   = $this->chemin( $cle );
		$tmp = $f . '.tmp';
		file_put_contents( $tmp, json_encode( $valeur, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION ) );
		rename( $tmp, $f );
	}
}
