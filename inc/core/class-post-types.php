<?php
/**
 * Custom post types.
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
 * Class Post_Types
 */
class Post_Types {

	/**
	 * Initialize
	 */
	public function init() {
		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * Register our custom post types
	 *
	 * @todo Try a separate autoloader?
	 */
	public function register() {
		$post_type = new Post_Types\Tool();
		$post_type->register();

		$post_type = new Post_Types\Feature_Proxy();
		$post_type->register();

		$post_type = new Post_Types\Category_Proxy();
		$post_type->register();
	}

	/**
	 * Unregister our custom post types
	 */
	public function unregister() {
		$post_type = new Post_Types\Tool();
		$post_type->unregister();

		$post_type = new Post_Types\Feature_Proxy();
		$post_type->register();

		$post_type = new Post_Types\Category_Proxy();
		$post_type->register();
	}

}
