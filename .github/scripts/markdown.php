<?php
$url = 'https://api.github.com/emojis';
$ch  = curl_init();
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt( $ch, CURLOPT_URL, $url );
curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Accept: application/vnd.github.v3+json', 'User-Agent: GitEmojiLister' ) );
$result = curl_exec( $ch );

if ( ! empty( $result ) ) {
	$i             = 0;
	$result        = json_decode( $result, true );
	$emoji_html    = [ '<tr valign="top">' ];
	$emoji_unicode = [ '<tr valign="top">' ];
	foreach ( $result as $id => $emoji ) {
		if ( 0 !== $i && 0 === $i % 2 ) {
			$emoji_unicode[] = '</tr><tr valign="top">';
		}

		if ( 0 !== $i && 0 === $i % 2 ) {
			$emoji_html[] = '</tr><tr valign="top">';
		}

		$emoji_html[]    = "<td align=\"center\"><a href=\"$emoji\"><img src=\"$emoji\" width=\"15%\"/></a></td> <td><code>:$id:</code> </td>";
		$emoji_unicode[] = "<td>:$id:</td><td><code>:$id:</code></td>";
		$i++;
	}
	$emoji_html[]    = '</tr>';
	$emoji_unicode[] = '</tr>';
	$emoji_unicode   = '<table>' . implode( '', $emoji_unicode ) . '</table>';
	$emoji_html      = '<table>' . implode( '', $emoji_html ) . '</table>';

	/**
	 * Generate README.md
	 */
	$_ex = file_get_contents( __DIR__ . '/readme.md' );
	$_ex = str_replace( '{{ list }}', $emoji_unicode, $_ex );
	file_put_contents( __DIR__ . '/../../README.md', $_ex );

	/**
	 * Generate HTML Image.md
	 */
	$_ex = file_get_contents( __DIR__ . '/index.html' );
	$_ex = str_replace( '{{ list }}', $emoji_unicode, $_ex );
	file_put_contents( __DIR__ . '/../../index.html', $_ex );
}
