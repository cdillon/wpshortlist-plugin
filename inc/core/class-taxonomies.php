<?php
/**
 * Custom taxonomies.
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
 * Class Taxonomies
 */
class Taxonomies {

	/**
	 * Initialize
	 */
	public function init() {
		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * Register our custom taxonomies
	 *
	 * Admin menu items are added in this order.
	 */
	public function register() {
		$tax = new Taxonomies\Feature_Category();
		$tax->register();

		$tax = new Taxonomies\Tool_Type();
		$tax->register();

		$tax = new Taxonomies\Feature();
		$tax->register();

		$tax = new Taxonomies\Compatibility();
		$tax->register();

		$tax = new Taxonomies\Workflow();
		$tax->register();
	}

	/**
	 * Unregister our custom taxonomies
	 */
	public function unregister() {
		$tax = new Taxonomies\Feature_Category();
		$tax->unregister();

		$tax = new Taxonomies\Tool_Type();
		$tax->unregister();

		$tax = new Taxonomies\Feature();
		$tax->unregister();

		$tax = new Taxonomies\Compatibility();
		$tax->unregister();

		$tax = new Taxonomies\Workflow();
		$tax->unregister();
	}

}
