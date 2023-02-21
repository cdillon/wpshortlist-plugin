<?php
/**
 * WP Shortlist plugin.
 *
 * @package     wpshortlist
 * @author      Chris Dillon
 * @copyright   2023 Chris Dillon
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: WP Shortlist
 * Plugin URI:
 * Description:
 * Version: 0.1
 * Author: Chris Dillon
 * Author URI: https://chrisdillon.dev
 */

define( 'WPSHORTLIST_INC_DIR', plugin_dir_path( __FILE__ ) . 'inc/' );
define( 'WPSHORTLIST_DATA_DIR', plugin_dir_path( __FILE__ ) . 'data/' );
define( 'WPSHORTLIST_TPL_DIR', plugin_dir_path( __FILE__ ) . 'template-parts/' );

/**
 * Load files.
 */
require_once WPSHORTLIST_INC_DIR . 'functions.php';
require_once WPSHORTLIST_INC_DIR . 'functions-ajax.php';
require_once WPSHORTLIST_INC_DIR . 'functions-query.php';
require_once WPSHORTLIST_INC_DIR . 'functions-meta-box.php';
require_once WPSHORTLIST_INC_DIR . 'post-types.php';
require_once WPSHORTLIST_INC_DIR . 'taxonomies.php';
require_once WPSHORTLIST_INC_DIR . 'filters.php';
require_once WPSHORTLIST_INC_DIR . 'filters-functions.php';
require_once WPSHORTLIST_INC_DIR . 'filters-templates.php';
require_once WPSHORTLIST_INC_DIR . 'filters-theme.php';
require_once WPSHORTLIST_INC_DIR . 'class-wpshortlist-filters-widget.php';

/**
 * On plugin activation.
 */
function wpshortlist_activate() {
	wpshortlist_register_post_types();
	wpshortlist_register_taxonomies();
	flush_rewrite_rules();

	wpshortlist_load_filter_sets();
}

register_activation_hook( __FILE__, 'wpshortlist_activate' );

/**
 * On plugin deactivation.
 */
function wpshortlist_deactivate() {
	wpshortlist_unregister_post_types();
	wpshortlist_unregister_taxonomies();
	flush_rewrite_rules();

	delete_option( 'wpshortlist_filter_sets' );
}

register_deactivation_hook( __FILE__, 'wpshortlist_deactivate' );

/**
 * Load scripts and styles.
 */
function wpshortlist_enqueue_scripts() {
	// Only load if the current page has a filter set.
	$start = wpshortlist_get_start();
	if ( ! $start ) {
		return;
	}

	// @todo Use plugin's version number.
	wp_enqueue_style( 'wpshortlist', plugins_url( '/css/style.css', __FILE__ ), array(), '1.0', 'all' );

	wp_enqueue_script( 'wpshortlist', plugins_url( '/js/wpshortlist.js', __FILE__ ), array( 'jquery' ), '1.0', true );

	$data = array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'action'  => 'filter_change',
		'nonce'   => wp_create_nonce( 'wpshortlist' ),
		'start'   => $start,
		// @todo Should this be the imploded string instead?
		'current' => wpshortlist_get_current_query_type(),
	);
	$code = 'const wpshortlistSettings = ' . wp_json_encode( $data );
	wp_add_inline_script( 'wpshortlist', $code );
}

add_action( 'wp_enqueue_scripts', 'wpshortlist_enqueue_scripts' );

/**
 * Register and load widgets.
 */
function wpshortlist_load_widgets() {
	register_widget( 'wpshortlist_filters_widget' );
}

add_action( 'widgets_init', 'wpshortlist_load_widgets' );

/**
 * Initialize.
 */
function wpshortlist_init() {
	// Compile list of filter sets, if missing.
	if ( ! get_option( 'wpshortlist_filter_sets' ) ) {
		wpshortlist_load_filter_sets();
	}
}

add_action( 'init', 'wpshortlist_init' );
