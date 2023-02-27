<?php
/**
 * Images
 *
 * @since 1.0.0
 *
 * @package wpshortlist
 */

namespace Shortlist\Core;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Images
 *
 * @since 1.0.0
 */
class Images {

	/**
	 * Init
	 */
	public function init() {
		add_filter( 'post_thumbnail_id', array( $this, 'default_post_thumbnail_id' ), 10, 2 );
	}

	/**
	 * Use default featured image if Tool has no image.
	 *
	 * @param int     $thumbnail_id  Thumbnail ID.
	 * @param WP_Post $post          A post.
	 */
	public function default_post_thumbnail_id( $thumbnail_id, $post ) {
		if ( 0 === $thumbnail_id && 'tool' === $post->post_type ) {
			return 233;
		}

		return $thumbnail_id;
	}

}
