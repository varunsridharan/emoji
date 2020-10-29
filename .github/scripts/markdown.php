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

		if ( 0 !== $i && 0 === $i % 5 ) {
			$emoji_html[] = '</tr><tr valign="top">';
		}

		#$emoji_html[]    = "<td class='emoji $id'><a href=\"$emoji\"><img src=\"$emoji\" /></a></td> <td class='emoji-id'><code>:$id:</code> </td>";
		$emoji_html[] = "<td class='emoji $id'><a href=\"$emoji\"><img src=\"$emoji\" /></a></td> <td class='emoji-id'><clipboard-copy value=\":$id:\"> <code>:$id:</code> <span class=\"notice\" hidden>Copied!</span> </clipboard-copy></td>";

		$emoji_unicode[] = "<td >:$id: <code>:$id:</code></td>";
		$i++;
	}
	$emoji_html[]    = '</tr>';
	$emoji_unicode[] = '</tr>';
	$emoji_unicode   = '<table>' . implode( '', $emoji_unicode ) . '</table>';
	$emoji_html      = '<table class="table  table-sm">' . implode( '', $emoji_html ) . '</table>';

	/**
	 * Generate README.md
	 */
	$_ex = file_get_contents( __DIR__ . '/readme.md' );
	$_ex = str_replace( '{{ list }}', $emoji_unicode, $_ex );
	$_ex = str_replace( '{{ last_updated }}', date( 'D d-M-Y h:i a' ), $_ex );
	file_put_contents( __DIR__ . '/../../README.md', $_ex );

	/**
	 * Generate HTML Image.md
	 */
	$_ex = file_get_contents( __DIR__ . '/index.html' );
	$_ex = str_replace( '{{ list }}', $emoji_html, $_ex );
	$_ex = str_replace( '{{ last_updated }}', date( 'D d-M-Y h:i: a' ), $_ex );
	file_put_contents( __DIR__ . '/../../index.html', $_ex );
}
