<?php
/**
 * Widget Manager
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
 * Class Widget_Manager
 *
 * @since 1.0.0
 */
class Widget_Manager {

	/**
	 * Register
	 */
	public function register() {
		register_widget( 'Shortlist\Core\Filters_Widget' );
	}

	/**
	 * Load
	 */
	public function load() {
		add_action( 'widgets_init', array( $this, 'register' ) );
	}

}
