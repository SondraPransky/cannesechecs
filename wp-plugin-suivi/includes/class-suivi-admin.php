<?php
/**
 * Page d'admin « Suivi tournoi » (menu principal), en quatre onglets :
 * Suivi (état + scan complet), Joueurs, Tournois masqués, Réglages.
 *
 * Écrite pour des bénévoles non techniques : écrans WordPress natifs, libellés
 * en clair, un message après chaque action. Les modifications passent par
 * admin-post.php (jeton + droit manage_options) et sous le verrou des passages.
 *
 * @package Suivi_Tournoi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Suivi_Admin {

	const PAGE   = 'suivi-tournoi';
	const ACTION = 'suivi_tournoi_action';
	const DROIT  = 'manage_options';

	const ONGLETS = array(
		'suivi'    => 'Suivi',
		'joueurs'  => 'Joueurs',
		'masques'  => 'Tournois masqués',
		'reglages' => 'Réglages',
	);

	const STATUTS = array(
		'en_cours' => 'En cours',
		'a_venir'  => 'À venir',
		'termine'  => 'Terminé',
	);

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'menu' ) );
		add_action( 'admin_post_' . self::ACTION, array( __CLASS__, 'traiter' ) );
	}

	public static function menu() {
		add_menu_page( 'Suivi tournoi', 'Suivi tournoi', self::DROIT, self::PAGE, array( __CLASS__, 'page' ), 'dashicons-chart-line', 26 );
	}

	private static function url( $onglet, $args = array() ) {
		return add_query_arg( array_merge( array( 'page' => self::PAGE, 'onglet' => $onglet ), $args ), admin_url( 'admin.php' ) );
	}

	private static function store() {
		return new Suivi_Store_WP();
	}

	// ---------------------------------------------------------------------
	// Actions (formulaires)
	// ---------------------------------------------------------------------

	public static function traiter() {
		if ( ! current_user_can( self::DROIT ) ) {
			wp_die( 'Accès refusé.' );
		}
		check_admin_referer( self::ACTION );
		$op     = isset( $_POST['op'] ) ? sanitize_key( $_POST['op'] ) : '';
		$onglet = isset( $_POST['onglet'] ) && isset( self::ONGLETS[ $_POST['onglet'] ] ) ? $_POST['onglet'] : 'suivi';
		$champ  = function ( $k ) {
			return isset( $_POST[ $k ] ) ? sanitize_text_field( wp_unslash( $_POST[ $k ] ) ) : '';
		};
		if ( function_exists( 'set_time_limit' ) ) {
			@set_time_limit( 180 ); // la FFE peut être lente (ajout d'un club : plusieurs pages)
		}

		try {
			$msg = Suivi_WP::sous_verrou(
				function () use ( $op, $champ ) {
					$trk = Suivi_WP::tracker();
					switch ( $op ) {
						case 'ajouter_joueur':
							$code = strtoupper( $champ( 'codeFFE' ) );
							$nom  = $champ( 'nom' );
							if ( '' === $code && '' === $nom ) {
								throw new RuntimeException( 'Indiquez un code FFE ou un nom.' );
							}
							$j = $trk->ajouter_joueur( '' !== $code ? array( 'codeFFE' => $code ) : array( 'nom' => $nom, 'prenom' => $champ( 'prenom' ) ) );
							return sprintf( '%s ajouté%s. Ses tournois apparaîtront au prochain passage.', $j['nomComplet'], null !== $j['elo'] ? ' (' . (int) $j['elo'] . ')' : '' );

						case 'ajouter_club':
							$club = (int) $champ( 'clubRef' );
							if ( $club <= 0 ) {
								throw new RuntimeException( 'Indiquez le numéro du club (ClubRef).' );
							}
							$r = $trk->ajouter_club( $club, max( 0, (int) $champ( 'eloMin' ) ) );
							return sprintf( '%d joueur(s) retenu(s), %d ajouté(s).', $r['retenus'], count( $r['ajoutes'] ) );

						case 'retirer_joueur':
							$trk->retirer_joueur( $champ( 'id' ) );
							return 'Joueur retiré du suivi.';

						case 'masquer':
							$trk->masquer( $champ( 'ref' ), $champ( 'joueurId' ) );
							return 'Tournoi masqué pour ce joueur. Vous pouvez le réafficher dans l\'onglet « Tournois masqués ».';

						case 'renommer':
							$s           = new Suivi_Store_WP();
							$n           = $s->get( 'noms' );
							$ref         = $champ( 'ref' );
							$nom         = $champ( 'nom' );
							if ( '' === $nom ) {
								unset( $n['liste'][ $ref ] );
							} else {
								$n['liste'][ $ref ] = $nom;
							}
							$s->set( 'noms', $n );
							return '' === $nom ? 'Nom d’origine rétabli.' : 'Tournoi renommé : « ' . $nom . ' ».';

						case 'demasquer':
							$trk->demasquer( $champ( 'cle' ) );
							return 'Tournoi réaffiché : il reviendra au prochain passage.';

						case 'reglages':
							$s   = new Suivi_Store_WP();
							$cfg = $s->get( 'config' );
							$cfg['clubRef']           = max( 0, (int) $champ( 'clubRef' ) );
							$cfg['chessResultsActif'] = ! empty( $_POST['chessResultsActif'] );
							$cfg['joursAvant']        = min( 90, max( 1, (int) $champ( 'joursAvant' ) ) );
							$cfg['joursApres']        = min( 60, max( 0, (int) $champ( 'joursApres' ) ) );
							$avant                    = (int) $cfg['intervalleMinutes'];
							$cfg['intervalleMinutes'] = min( 60, max( 5, (int) $champ( 'intervalleMinutes' ) ) );
							$s->set( 'config', $cfg );
							if ( $avant !== $cfg['intervalleMinutes'] ) {
								Suivi_WP::deplanifier(); // le nouvel intervalle s'applique à la replanification
							}
							return 'Réglages enregistrés.';
					}
					throw new RuntimeException( 'Action inconnue.' );
				}
			);
			$args = array( 'suivi_ok' => rawurlencode( $msg ) );
		} catch ( Exception $e ) {
			$args = array( 'suivi_erreur' => rawurlencode( $e->getMessage() ) );
		}
		Suivi_WP::planifier();
		wp_safe_redirect( self::url( $onglet, $args ) );
		exit;
	}

	/** Bouton d'action dans un petit formulaire POST. */
	private static function bouton( $op, $onglet, $libelle, $champs = array(), $classe = 'button', $confirmer = '' ) {
		echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" style="display:inline"'
			. ( $confirmer ? ' onsubmit="return confirm(' . esc_attr( wp_json_encode( $confirmer ) ) . ')"' : '' ) . '>';
		wp_nonce_field( self::ACTION );
		echo '<input type="hidden" name="action" value="' . esc_attr( self::ACTION ) . '">';
		echo '<input type="hidden" name="op" value="' . esc_attr( $op ) . '">';
		echo '<input type="hidden" name="onglet" value="' . esc_attr( $onglet ) . '">';
		foreach ( $champs as $k => $v ) {
			echo '<input type="hidden" name="' . esc_attr( $k ) . '" value="' . esc_attr( $v ) . '">';
		}
		echo '<button type="submit" class="' . esc_attr( $classe ) . '">' . esc_html( $libelle ) . '</button></form>';
	}

	/** « Renommer » : demande le nouveau nom (vide = nom d'origine), puis envoie le formulaire. */
	private static function bouton_renommer( $ref, $actuel ) {
		echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" style="display:inline" onsubmit="var n = prompt(' . esc_attr( wp_json_encode( 'Nom à afficher pour ce tournoi (laisser vide pour revenir au nom d’origine) :' ) ) . ', this.nom.value); if (n === null) return false; this.nom.value = n; return true;">';
		wp_nonce_field( self::ACTION );
		echo '<input type="hidden" name="action" value="' . esc_attr( self::ACTION ) . '"><input type="hidden" name="op" value="renommer"><input type="hidden" name="onglet" value="suivi">';
		echo '<input type="hidden" name="ref" value="' . esc_attr( $ref ) . '"><input type="hidden" name="nom" value="' . esc_attr( $actuel ) . '">';
		echo '<button type="submit" class="button button-small">Renommer</button></form>';
	}

	private static function debut_formulaire( $op, $onglet ) {
		echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
		wp_nonce_field( self::ACTION );
		echo '<input type="hidden" name="action" value="' . esc_attr( self::ACTION ) . '">';
		echo '<input type="hidden" name="op" value="' . esc_attr( $op ) . '">';
		echo '<input type="hidden" name="onglet" value="' . esc_attr( $onglet ) . '">';
	}

	/** « il y a 5 min » à partir d'une date ISO. */
	private static function depuis( $iso ) {
		if ( ! $iso ) {
			return 'jamais';
		}
		return 'il y a ' . human_time_diff( strtotime( $iso ), time() );
	}

	// ---------------------------------------------------------------------
	// Page
	// ---------------------------------------------------------------------

	public static function page() {
		if ( ! current_user_can( self::DROIT ) ) {
			return;
		}
		$onglet = isset( $_GET['onglet'] ) && isset( self::ONGLETS[ $_GET['onglet'] ] ) ? $_GET['onglet'] : 'suivi';
		echo '<div class="wrap"><h1>Suivi tournoi</h1>';

		if ( ! empty( $_GET['suivi_ok'] ) ) {
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( wp_unslash( $_GET['suivi_ok'] ) ) . '</p></div>';
		}
		if ( ! empty( $_GET['suivi_erreur'] ) ) {
			echo '<div class="notice notice-error is-dismissible"><p>' . esc_html( wp_unslash( $_GET['suivi_erreur'] ) ) . '</p></div>';
		}

		echo '<nav class="nav-tab-wrapper">';
		foreach ( self::ONGLETS as $cle => $libelle ) {
			echo '<a href="' . esc_url( self::url( $cle ) ) . '" class="nav-tab' . ( $cle === $onglet ? ' nav-tab-active' : '' ) . '">' . esc_html( $libelle ) . '</a>';
		}
		echo '</nav><div style="margin-top:1.5em">';
		call_user_func( array( __CLASS__, 'onglet_' . $onglet ) );
		echo '</div></div>';
	}

	private static function noms_joueurs() {
		$noms = array();
		foreach ( self::store()->get( 'joueurs' )['joueurs'] as $j ) {
			$noms[ $j['id'] ] = $j['nomComplet'];
		}
		return $noms;
	}

	// --- Onglet Suivi ------------------------------------------------------

	private static function onglet_suivi() {
		$s    = self::store();
		$st   = $s->get( 'etat' );
		$cfg  = $s->get( 'config' );
		$pr   = Suivi_WP::tracker()->progression();
		$noms     = self::noms_joueurs();
		$renommes = $s->get( 'noms' )['liste'];
		$prochain = wp_next_scheduled( Suivi_WP::HOOK_CRON );
		$pct  = $pr['total'] ? (int) floor( 100 * $pr['fait'] / $pr['total'] ) : 100;

		echo '<h2>État</h2><table class="form-table" role="presentation"><tbody>';
		printf( '<tr><th>Joueurs suivis</th><td>%d</td></tr>', count( $noms ) );
		printf( '<tr><th>Dernière mise à jour</th><td>%s</td></tr>', esc_html( self::depuis( $st['dernierRafraichissement'] ) ) );
		printf( '<tr><th>Prochaine mise à jour</th><td>%s <span class="description">(toutes les %d minutes, déclenchée par les visites du site)</span></td></tr>',
			$prochain ? esc_html( $prochain > time() ? 'dans ' . human_time_diff( time(), $prochain ) : 'à la prochaine visite' ) : 'non planifiée', (int) $cfg['intervalleMinutes'] );
		printf( '<tr><th>Premier scan</th><td><strong id="suivi-pct">%d %%</strong> <span class="description" id="suivi-detail">(%d sur %d éléments lus)</span></td></tr>', $pct, $pr['fait'], $pr['total'] );
		echo '</tbody></table>';

		echo '<h2>Scan complet</h2>';
		echo '<p>Au premier lancement, le suivi doit lire environ un millier de pages de la FFE. Au rythme normal, cela prend une journée. '
			. 'Ce bouton enchaîne les mises à jour sans attendre : <strong>laissez cette page ouverte</strong> jusqu\'à la fin (environ une heure, selon la vitesse du site de la FFE).</p>';
		echo '<p><button type="button" class="button button-primary" id="suivi-scan">Lancer le scan complet</button> '
			. '<button type="button" class="button" id="suivi-stop" style="display:none">Arrêter</button></p>';
		echo '<div style="max-width:600px;background:#dcdcde;border-radius:3px;height:12px;overflow:hidden"><div id="suivi-barre" style="height:12px;width:' . (int) $pct . '%;background:#2271b1"></div></div>';
		echo '<p class="description" id="suivi-etat" aria-live="polite"></p>';
		self::script_scan();

		if ( ! empty( $st['erreurs'] ) ) {
			echo '<h2>Erreurs de la dernière mise à jour</h2><p class="description">Souvent passagères (site de la FFE lent ou indisponible) : elles se règlent seules aux passages suivants.</p><ul style="list-style:disc;margin-left:2em">';
			foreach ( array_slice( $st['erreurs'], 0, 8 ) as $e ) {
				echo '<li>' . esc_html( $e ) . '</li>';
			}
			echo '</ul>';
		}

		echo '<h2>Tournois suivis</h2>';
		if ( ! $st['suivi'] ) {
			echo '<p>Aucun tournoi trouvé pour l\'instant' . ( $pct < 100 ? ' : le premier scan n\'est pas terminé.' : '.' ) . '</p>';
			return;
		}
		echo '<p class="description">Un tournoi qui n\'a rien à faire ici (fiche de test d\'un organisateur, homonyme…) ? « Masquer » le retire pour ce joueur. Un nom mal traduit ? « Renommer » le corrige pour tout le monde.</p>';
		echo '<table class="wp-list-table widefat fixed striped"><thead><tr><th>Joueur</th><th>Tournoi</th><th style="width:90px">Statut</th><th style="width:110px">Résultat</th><th style="width:110px">Source</th><th style="width:180px"></th></tr></thead><tbody>';
		foreach ( $st['suivi'] as $e ) {
			$res = null !== $e['points'] ? ( 0 + $e['points'] ) . ' pt' . ( null !== $e['place'] ? ', ' . (int) $e['place'] . 'e' : '' ) : '—';
			echo '<tr><td>' . esc_html( isset( $noms[ $e['joueurId'] ] ) ? $noms[ $e['joueurId'] ] : $e['joueurId'] ) . '</td>';
			$affiche = Suivi_Noms::affiche( $e['tournoi']['ref'], $e['tournoi']['nom'], $renommes );
			echo '<td>' . esc_html( $affiche ) . ( $affiche !== $e['tournoi']['nom'] ? '<br><span class="description">' . esc_html( $e['tournoi']['nom'] ) . '</span>' : '' ) . '</td>';
			echo '<td>' . esc_html( isset( self::STATUTS[ $e['statut'] ] ) ? self::STATUTS[ $e['statut'] ] : $e['statut'] ) . '</td>';
			echo '<td>' . esc_html( $res ) . '</td>';
			echo '<td>' . esc_html( 'ffe' === $e['source'] ? 'FFE' : $e['source'] ) . '</td><td>';
			self::bouton( 'masquer', 'suivi', 'Masquer', array( 'ref' => $e['tournoi']['ref'], 'joueurId' => $e['joueurId'] ), 'button button-small' );
			echo ' ';
			self::bouton_renommer( $e['tournoi']['ref'], $affiche );
			echo '</td></tr>';
		}
		echo '</tbody></table>';
	}

	/** Boucle AJAX du scan complet : un passage par appel, tant que la page est ouverte. */
	private static function script_scan() {
		$cfg = array(
			'url'   => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( Suivi_WP::AJAX_SCAN ),
		);
		?>
<script>
(function () {
	var C = <?php echo wp_json_encode( $cfg ); ?>;
	var go = document.getElementById('suivi-scan'), stop = document.getElementById('suivi-stop');
	var barre = document.getElementById('suivi-barre'), etat = document.getElementById('suivi-etat');
	var pct = document.getElementById('suivi-pct'), detail = document.getElementById('suivi-detail');
	var actif = false, passages = 0;
	function fin(msg) { actif = false; go.disabled = false; stop.style.display = "none"; etat.textContent = msg; window.onbeforeunload = null; }
	function passage() {
		if (!actif) { return; }
		var corps = new FormData();
		corps.append('action', <?php echo wp_json_encode( Suivi_WP::AJAX_SCAN ); ?>);
		corps.append('_ajax_nonce', C.nonce);
		fetch(C.url, { method: 'POST', body: corps, credentials: 'same-origin' })
			.then(function (r) { return r.json(); })
			.then(function (r) {
				if (!r.success) { return fin('Le scan s\'est arrêté : ' + (r.data && r.data.message ? r.data.message : 'erreur inconnue') + '.'); }
				var p = r.data.progression, v = p.total ? Math.floor(100 * p.fait / p.total) : 100;
				barre.style.width = v + '%'; pct.textContent = v + ' %';
				detail.textContent = '(' + p.fait + ' sur ' + p.total + ' éléments lus)';
				if (r.data.occupe) { etat.textContent = 'Une mise à jour automatique est en cours, reprise dans 15 secondes…'; return setTimeout(passage, 15000); }
				passages++;
				etat.textContent = 'Passage ' + passages + ' terminé. ' + r.data.engagements + ' tournoi(s) suivi(s) pour l\'instant. Laissez cette page ouverte…';
				if (p.fait >= p.total && p.scanComplet) { return fin('Scan complet terminé : ' + r.data.engagements + ' tournoi(s) suivi(s). Le suivi continue tout seul toutes les quelques minutes.'); }
				setTimeout(passage, 1000);
			})
			.catch(function () { etat.textContent = 'Connexion interrompue, nouvel essai dans 15 secondes…'; setTimeout(passage, 15000); });
	}
	go.addEventListener('click', function () {
		actif = true; go.disabled = true; stop.style.display = ""; passages = 0;
		etat.textContent = 'Scan en cours… (chaque passage dure environ 30 secondes)';
		window.onbeforeunload = function () { return 'Le scan complet est en cours.'; };
		passage();
	});
	stop.addEventListener('click', function () { fin('Scan arrêté. Il reprendra où il en était si vous le relancez ; sinon le suivi continue à son rythme normal.'); });
})();
</script>
		<?php
	}

	// --- Onglet Joueurs ----------------------------------------------------

	private static function onglet_joueurs() {
		$s       = self::store();
		$joueurs = $s->get( 'joueurs' )['joueurs'];
		$cfg     = $s->get( 'config' );

		echo '<h2>Ajouter un joueur</h2>';
		self::debut_formulaire( 'ajouter_joueur', 'joueurs' );
		echo '<table class="form-table" role="presentation"><tbody>';
		echo '<tr><th><label for="suivi-code">Code FFE</label></th><td><input type="text" id="suivi-code" name="codeFFE" class="regular-text" placeholder="S50192"><p class="description">Le plus sûr : le code figure sur la fiche du joueur sur le site de la FFE.</p></td></tr>';
		echo '<tr><th>Ou nom et prénom</th><td><input type="text" name="nom" placeholder="Nom" class="regular-text"> <input type="text" name="prenom" placeholder="Prénom" class="regular-text"><p class="description">En cas d\'homonymes, c\'est le premier trouvé qui est ajouté : préférez le code FFE.</p></td></tr>';
		echo '</tbody></table>';
		submit_button( 'Ajouter le joueur', 'primary', 'submit', false );
		echo '</form>';

		echo '<h2 style="margin-top:2em">Ajouter tous les joueurs d\'un club</h2>';
		self::debut_formulaire( 'ajouter_club', 'joueurs' );
		echo '<table class="form-table" role="presentation"><tbody>';
		printf( '<tr><th><label for="suivi-club">Numéro du club</label></th><td><input type="number" id="suivi-club" name="clubRef" value="%d" class="small-text"> <span class="description">311 = Cannes Échecs</span></td></tr>', (int) ( isset( $cfg['clubRef'] ) ? $cfg['clubRef'] : 311 ) );
		echo '<tr><th><label for="suivi-elo">Elo minimum</label></th><td><input type="number" id="suivi-elo" name="eloMin" value="1500" step="50" class="small-text"> <span class="description">Seuls les joueurs à partir de cet Elo sont ajoutés (0 = tous). Ceux déjà suivis ne sont pas dupliqués.</span></td></tr>';
		echo '</tbody></table>';
		submit_button( 'Ajouter les joueurs du club', 'secondary', 'submit', false );
		echo ' <span class="description">Peut prendre une minute.</span></form>';

		printf( '<h2 style="margin-top:2em">Joueurs suivis (%d)</h2>', count( $joueurs ) );
		if ( ! $joueurs ) {
			echo '<p>Aucun joueur suivi pour l\'instant. Ajoutez-en ci-dessus.</p>';
			return;
		}
		echo '<table class="wp-list-table widefat fixed striped"><thead><tr><th>Nom</th><th style="width:100px">Code FFE</th><th style="width:80px">Elo</th><th style="width:90px">Catégorie</th><th>Club</th><th style="width:90px"></th></tr></thead><tbody>';
		foreach ( $joueurs as $j ) {
			$elo = isset( $j['elo'] ) ? (string) (int) $j['elo'] : '—';
			echo '<tr><td>' . esc_html( $j['nomComplet'] ) . '</td><td>' . esc_html( (string) ( $j['codeFFE'] ?? '' ) ) . '</td>';
			echo '<td>' . esc_html( $elo ) . '</td><td>' . esc_html( (string) ( $j['cat'] ?? '' ) ) . '</td><td>' . esc_html( (string) ( $j['club'] ?? '' ) ) . '</td><td>';
			self::bouton( 'retirer_joueur', 'joueurs', 'Retirer', array( 'id' => $j['id'] ), 'button button-small button-link-delete', 'Retirer ' . $j['nomComplet'] . ' du suivi ? Ses résultats archivés seront effacés.' );
			echo '</td></tr>';
		}
		echo '</tbody></table>';
	}

	// --- Onglet Tournois masqués ---------------------------------------------

	private static function onglet_masques() {
		$m = self::store()->get( 'masques' )['liste'];
		echo '<p>Les tournois masqués ne sont ni affichés sur le site, ni annoncés dans le fil des résultats. On masque un tournoi depuis l\'onglet « Suivi ».</p>';
		if ( ! $m ) {
			echo '<p><em>Aucun tournoi masqué.</em></p>';
			return;
		}
		echo '<table class="wp-list-table widefat fixed striped"><thead><tr><th>Joueur</th><th>Tournoi</th><th style="width:160px">Masqué</th><th style="width:110px"></th></tr></thead><tbody>';
		foreach ( $m as $cle => $x ) {
			echo '<tr><td>' . esc_html( $x['joueur'] ) . '</td><td>' . esc_html( $x['tournoi'] ) . '</td><td>' . esc_html( self::depuis( $x['le'] ) ) . '</td><td>';
			self::bouton( 'demasquer', 'masques', 'Réafficher', array( 'cle' => $cle ), 'button button-small' );
			echo '</td></tr>';
		}
		echo '</tbody></table>';
	}

	// --- Onglet Réglages ---------------------------------------------------

	private static function onglet_reglages() {
		$cfg = self::store()->get( 'config' );
		self::debut_formulaire( 'reglages', 'reglages' );
		echo '<table class="form-table" role="presentation"><tbody>';
		printf( '<tr><th><label for="r-club">Club par défaut</label></th><td><input type="number" id="r-club" name="clubRef" value="%d" class="small-text"><p class="description">Numéro proposé dans « Ajouter tous les joueurs d\'un club ». 311 = Cannes Échecs.</p></td></tr>', (int) ( isset( $cfg['clubRef'] ) ? $cfg['clubRef'] : 311 ) );
		printf( '<tr><th>Tournois à l\'étranger</th><td><label><input type="checkbox" name="chessResultsActif" value="1"%s> Suivre aussi les tournois publiés sur chess-results (hors FFE)</label></td></tr>', ! empty( $cfg['chessResultsActif'] ) ? ' checked' : '' );
		printf( '<tr><th>Période suivie</th><td>De <input type="number" name="joursAvant" value="%d" min="1" max="90" class="small-text"> jours avant aujourd\'hui à <input type="number" name="joursApres" value="%d" min="0" max="60" class="small-text"> jours après</td></tr>', (int) $cfg['joursAvant'], (int) $cfg['joursApres'] );
		printf( '<tr><th>Fréquence</th><td>Mise à jour toutes les <input type="number" name="intervalleMinutes" value="%d" min="5" max="60" class="small-text"> minutes<p class="description">Plus court = plus de requêtes vers la FFE. 8 minutes est un bon équilibre.</p></td></tr>', (int) $cfg['intervalleMinutes'] );
		echo '</tbody></table>';
		submit_button( 'Enregistrer les réglages' );
		echo '</form>';
	}
}
