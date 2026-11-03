<?php
/**
 * Transport HTTP de DÉVELOPPEMENT : passe par l'exécutable curl du système.
 *
 * La PHP CLI locale n'a ni l'extension curl ni openssl (pas de HTTPS natif) ;
 * ce transport permet quand même de tester le portage contre les vrais sites.
 * Il n'est PAS livré dans le plugin : en production WordPress, le transport
 * est wp_remote_request.
 *
 * Contrat (le même que le transport WordPress devra tenir) :
 *   $opts : method, body, headers, redirection (0 = ne pas suivre).
 *   Rend  : body, content_type, status, headers (location, set-cookie[]).
 *   Lève une exception si curl échoue ; un statut HTTP d'erreur est rendu tel
 *   quel quand redirection = 0 (chess-results le traite), sinon lève aussi.
 *
 * @return callable fn(string $url, array $opts): array
 */
function suivi_transport_curl_cli( $delai_ms = 150 ) {
	return function ( $url, $opts = array() ) use ( $delai_ms ) {
		usleep( $delai_ms * 1000 ); // politesse envers les serveurs
		$marque  = '__SUIVI_CT__';
		$suivre  = ! ( isset( $opts['redirection'] ) && 0 === $opts['redirection'] );
		$entetes = tempnam( sys_get_temp_dir(), 'suivih' );
		// "\n" laissé en clair : c'est curl qui l'interprète dans le format -w.
		$args = array( 'curl', '-s', '-A', Suivi_FFE::UA, '-D', $entetes, '-w', '\n' . $marque . '%{http_code} %{content_type}' );
		if ( $suivre ) {
			$args[] = '-L';
		}
		foreach ( isset( $opts['headers'] ) ? $opts['headers'] : array() as $k => $v ) {
			$args[] = '-H';
			$args[] = "$k: $v";
		}
		$tmp = null;
		if ( isset( $opts['method'] ) && 'POST' === $opts['method'] ) {
			$tmp = tempnam( sys_get_temp_dir(), 'suivi' );
			file_put_contents( $tmp, $opts['body'] );
			$args[] = '--data-binary';
			$args[] = '@' . $tmp;
		}
		$args[] = $url;
		// proc_open en mode tableau : curl est lancé sans passer par le shell, donc
		// rien à échapper (escapeshellarg remplace les % par des espaces sous Windows).
		$proc = proc_open( $args, array( 1 => array( 'pipe', 'w' ), 2 => array( 'pipe', 'w' ) ), $pipes );
		if ( ! is_resource( $proc ) ) {
			throw new RuntimeException( 'impossible de lancer curl' );
		}
		$out = stream_get_contents( $pipes[1] );
		$err = stream_get_contents( $pipes[2] );
		fclose( $pipes[1] );
		fclose( $pipes[2] );
		$code = proc_close( $proc );
		if ( $tmp ) {
			unlink( $tmp );
		}
		$brut = (string) @file_get_contents( $entetes );
		unlink( $entetes );
		if ( 0 !== $code ) {
			throw new RuntimeException( "curl a échoué ($code) sur $url : $err" );
		}

		// En-têtes de la dernière réponse (avec -L, curl les empile).
		$blocs   = preg_split( "/\r?\n\r?\n/", trim( $brut ) );
		$headers = array( 'set-cookie' => array() );
		foreach ( explode( "\n", (string) end( $blocs ) ) as $l ) {
			if ( preg_match( '/^([^:\s]+):\s*(.*?)\s*$/', $l, $m ) ) {
				$k = strtolower( $m[1] );
				if ( 'set-cookie' === $k ) {
					$headers['set-cookie'][] = $m[2];
				} else {
					$headers[ $k ] = $m[2];
				}
			}
		}

		$pos   = strrpos( $out, "\n$marque" );
		$queue = false === $pos ? '200 ' : substr( $out, $pos + strlen( "\n$marque" ) );
		list( $statut, $ct ) = array_pad( explode( ' ', $queue, 2 ), 2, '' );
		if ( $suivre && (int) $statut >= 400 ) {
			throw new RuntimeException( "HTTP $statut sur $url" );
		}
		return array(
			'body'         => false === $pos ? $out : substr( $out, 0, $pos ),
			'content_type' => $ct,
			'status'       => (int) $statut,
			'headers'      => $headers,
		);
	};
}
