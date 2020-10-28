<?php
$url = 'https://api.github.com/emojis';
$ch  = curl_init();
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt( $ch, CURLOPT_URL, $url );
curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Accept: application/vnd.github.v3+json', 'User-Agent: GitEmojiLister' ) );
$result = curl_exec( $ch );
function file_get_contents_curl( $url ) {
	$ch = curl_init();

	curl_setopt( $ch, CURLOPT_HEADER, 0 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_URL, $url );

	$data = curl_exec( $ch );
	curl_close( $ch );

	return $data;
}

if ( ! empty( $result ) ) {
	$i             = 0;
	$result        = json_decode( $result, true );
	$emoji_html    = [ '<tr valign="top">' ];
	$emoji_unicode = [ '<tr valign="top">' ];
	@mkdir( __DIR__ . '/../../emojis/', 0777, true );
	foreach ( $result as $id => $emoji ) {
		$paths      = parse_url( $emoji );
		$query      = $paths['query'];
		$path       = $paths['path'];
		$path       = basename( $path );
		$local_path = __DIR__ . '/../../emojis/' . $query . '/' . $path;
		if ( ! file_exists( $local_path ) ) {
			$data = file_get_contents_curl( $emoji );
			@mkdir( __DIR__ . '/../../emojis/' . $query, 0777, true );
			file_put_contents( $local_path, $data );
		}
	}
}
