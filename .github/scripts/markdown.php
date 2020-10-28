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
	$emoji_html    = [ '<tr>' . PHP_EOL ];
	$emoji_unicode = [ '<tr>' . PHP_EOL ];
	foreach ( $result as $id => $emoji ) {

		if ( $i !== 0 && $i % 5 == 0 ) {
			$emoji_html[]    = PHP_EOL . '</tr>' . PHP_EOL . PHP_EOL . '<tr>' . PHP_EOL;
			$emoji_unicode[] = PHP_EOL . '</tr>' . PHP_EOL . PHP_EOL . '<tr>' . PHP_EOL;
		}
		$emoji_html[] = <<<HTML
<td align="center"> <a href="$emoji"><img src="$emoji" width="15%"/></a>
<br/>
<code>$id</code> </td>
HTML;

		$emoji_unicode[] = <<<HTML
<td> :$id: <small><code>$id</code></small> </td>

HTML;

		$i++;
	}
	$emoji_html[]    = PHP_EOL . '</tr>';
	$emoji_unicode[] = PHP_EOL . '</tr>';
	$emoji_unicode   = '<table>' . PHP_EOL . implode( PHP_EOL, $emoji_unicode ) . PHP_EOL . '</table>';
	$emoji_html      = '<table>' . PHP_EOL . implode( PHP_EOL, $emoji_html ) . PHP_EOL . '</table>';
	file_put_contents( __DIR__ . '/../../unicode.md', $emoji_unicode );
	file_put_contents( __DIR__ . '/../../images.md', $emoji_html );
}
