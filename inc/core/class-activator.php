<?php
/**
 * Fired during plugin activation
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
 * Class Activator
 */
class Activator {

	/**
	 * On plugin activation.
	 *
	 * @since 1.0.0
	 *
	 * @link https://developer.wordpress.org/plugins/plugin-basics/activation-deactivation-hooks/
	 */
	public static function activate() {
		$post_types = new Post_Types();
		$post_types->register();

		$taxonomies = new Taxonomies();
		$taxonomies->register();

		flush_rewrite_rules();

		// Build new array of filter sets.
		$filter_set_manager = new Filter_Set_Manager();
		$filter_set_manager->build();
	}

}
