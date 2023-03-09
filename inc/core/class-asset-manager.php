<?php
/**
 * Asset Manager
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
 * Class Asset_Manager
 */
class Asset_Manager {

	/**
	 * Init
	 */
	public function init() {
		// add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	}

	/**
	 * Load scripts.
	 */
	public function enqueue_scripts() {
		// Only load if the current page has a filter set.
		$filter_sets = new Filter_Sets();
		$start       = $filter_sets->get_start();
		if ( ! $start ) {
			return;
		}

		// @todo Use plugin's version number.
		wp_enqueue_script( 'wpshortlist', WPSHORTLIST_URL . '/js/wpshortlist.js', array( 'jquery' ), '1.0', true );

		$data = array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'action'  => 'filter_change',
			'nonce'   => wp_create_nonce( 'wpshortlist' ),
			'start'   => $start,
			// @todo Should this be the imploded string instead?
			'current' => get_current_query_type(),
		);
		$code = 'const wpshortlistSettings = ' . wp_json_encode( $data );
		wp_add_inline_script( 'wpshortlist', $code );
	}

	/**
	 * Load styles.
	 */
	public function enqueue_styles() {
		// Only load if the current page has a filter set.
		$filter_sets = new Filter_Sets();
		$start       = $filter_sets->get_start();
		if ( ! $start ) {
			return;
		}

		// @todo Use plugin's version number.
		wp_enqueue_style( 'wpshortlist', WPSHORTLIST_URL . '/css/style.css', array(), '1.0', 'all' );
	}

}
