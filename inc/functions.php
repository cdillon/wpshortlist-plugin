<?php
/**
 * Plugin Functions
 *
 * @package wpshortlist
 */

/**
 * Use default featured image if Tool has no image.
 *
 * @param int     $thumbnail_id  Thumbnail ID.
 * @param WP_Post $post          A post.
 */
function wpshortlist_post_thumbnail_id( $thumbnail_id, $post ) {
	if ( 0 === $thumbnail_id && 'tool' === $post->post_type ) {
		return 233;
	}

	return $thumbnail_id;
}

add_filter( 'post_thumbnail_id', 'wpshortlist_post_thumbnail_id', 10, 2 );
