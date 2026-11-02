<?php
/**
 * Transport HTTP de DÉVELOPPEMENT : passe par l'exécutable curl du système.
 *
 * La PHP CLI locale n'a ni l'extension curl ni openssl (pas de HTTPS natif) ;
 * ce transport permet quand même de tester le portage contre la vraie FFE.
 * Il n'est PAS livré dans le plugin : en production WordPress, le transport
 * est wp_remote_get / wp_remote_post.
 *
 * @return callable fn(string $url, array $opts): array{body:string,content_type:string}
 */
function suivi_transport_curl_cli( $delai_ms = 150 ) {
	return function ( $url, $opts = array() ) use ( $delai_ms ) {
		usleep( $delai_ms * 1000 ); // politesse envers le serveur de la FFE
		$marque = '__SUIVI_CT__';
		// "\n" laissé en clair : c'est curl qui l'interprète dans le format -w.
		$args = array( 'curl', '-s', '-L', '-A', Suivi_FFE::UA, '-w', '\n' . $marque . '%{content_type}' );
		$tmp    = null;
		if ( isset( $opts['method'] ) && 'POST' === $opts['method'] ) {
			$tmp = tempnam( sys_get_temp_dir(), 'suivi' );
			file_put_contents( $tmp, $opts['body'] );
			$args[] = '--data-binary';
			$args[] = '@' . $tmp;
			foreach ( isset( $opts['headers'] ) ? $opts['headers'] : array() as $k => $v ) {
				$args[] = '-H';
				$args[] = "$k: $v";
			}
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
		if ( 0 !== $code ) {
			throw new RuntimeException( "curl a échoué ($code) sur $url : $err" );
		}
		$pos = strrpos( $out, "\n$marque" );
		return array(
			'body'         => false === $pos ? $out : substr( $out, 0, $pos ),
			'content_type' => false === $pos ? '' : substr( $out, $pos + strlen( "\n$marque" ) ),
		);
	};
}
