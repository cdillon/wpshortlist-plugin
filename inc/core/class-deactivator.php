<?php
/**
 * Fired during plugin deactivation
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
class Deactivator {

	/**
	 * On plugin deactivation.
	 *
	 * @since 1.0.0
	 *
	 * @link https://developer.wordpress.org/plugins/plugin-basics/activation-deactivation-hooks/
	 */
	public static function deactivate() {
		$post_types = new Post_Types();
		$post_types->unregister();

		$taxonomies = new Taxonomies();
		$taxonomies->unregister();

		flush_rewrite_rules();

		// Delete array of filter sets. Will rebuild upon activation.
		$filter_set_manager = new Filter_Set_Manager();
		$filter_set_manager->erase();
	}

}
