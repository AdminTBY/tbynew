<?php

function monsterinsights_media_allow_youtube_tracking( $oembed ) {
	if ( ! is_admin() ) {
		return str_replace( 'feature=oembed', 'feature=oembed&enablejsapi=1', $oembed );
	}
	return $oembed;
}

/**
 * Alternatively, embed_oembed_html can be used. However, oembed_result might be
 * more performant as it is applied before the oEmbed response is cached as a _oembed_* meta entry.
 * https://developer.wordpress.org/reference/hooks/oembed_result/#comment-2330*
 */
add_filter( 'oembed_result', 'monsterinsights_media_allow_youtube_tracking' );


/**
 * Add video tracking feature to videos added before the Media Addon was available
 * to public.
 *
 * @param string $content Post Content.
 *
 * @return void
 */
function monsterinsights_track_old_videos( $content ) {
	$post_content = do_shortcode( $content ); // We need to 'do_shortcode' to get the result out of 'get_media_embedded_in_content'.
	$media        = get_media_embedded_in_content( $post_content, array( 'video', 'embed', 'iframe' ) );
	$result       = array();

	if ( is_array( $media ) && ! empty( $media ) ) {
		foreach ( $media as $video ) {
			preg_match( '/src="([^"]*)"/i', $video, $result );
			if ( is_array( $result ) && ! empty( $result ) ) {
				if ( array_key_exists( 1, $result ) ) {
					if ( strpos( $result[1], 'youtube' ) ) {
						if ( ! strpos( $result[1], 'enablejsapi=1' ) ) {
							if ( strpos( $result[1], '?' ) ) {
								$content = str_replace( $result[1], $result[1] . '&enablejsapi=1', $content );
							} else {
								$content = str_replace( $result[1], $result[1] . '?enablejsapi=1', $content );
							}
						}
					}
				}
			}
		}
	}
	return $content;
}
add_filter( 'the_content', 'monsterinsights_track_old_videos', 10 );
