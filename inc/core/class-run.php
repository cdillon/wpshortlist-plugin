<?php
/**
 * Run
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
 * The main class used to run the plugin
 *
 * @since 1.0.0
 */
class Run {

	/**
	 * Constructor
	 */
	public function __construct() {
		$post_types = new Post_Types();
		$post_types->init();

		$taxos = new Taxonomies();
		$taxos->init();

		$q = new Query();
		$q->init();

		$filter_set_manager = new Filter_Set_Manager();
		$filter_set_manager->init();

		$widgets = new Widget_Manager();
		$widgets->init();

		$asset_manager = new Asset_Manager();
		$asset_manager->init();

		$meta_box = new Meta_Box_Integration();
		$meta_box->init();

		$images = new Images();
		$images->init();

		$theme = new Theme_Integration();
		$theme->init();

		$breadcrumbs = new Breadcrumbs();
		$breadcrumbs->init();

		$ajax = new Ajax();
		$ajax->init();
	}

}
