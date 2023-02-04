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
require_once WPSHORTLIST_INC_DIR . 'filters-config.php';
require_once WPSHORTLIST_INC_DIR . 'filters-templates.php';
require_once WPSHORTLIST_INC_DIR . 'class-wpshortlist-widget.php';

/**
 * On plugin activation.
 */
function wpshortlist_activate() {
	wpshortlist_register_post_types();
	wpshortlist_register_taxonomies();
	flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'wpshortlist_activate' );

/**
 * On plugin deactivation.
 */
function wpshortlist_deactivate() {
	wpshortlist_unregister_post_types();
	wpshortlist_unregister_taxonomies();
	flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'wpshortlist_deactivate' );

/**
 * Load scripts and styles.
 */
function wpshortlist_enqueue_scripts() {
	// @todo Only load on our CPT/CT archive.
	// @todo Get actual script version number.
	wp_enqueue_script( 'wpshortlist', plugins_url( '/js/wpshortlist.js', __FILE__ ), array( 'jquery' ), '1.0', true );

	// phpcs:disable
	wp_add_inline_script( 'wpshortlist', 'const wpshortlistSettings = ' . json_encode( array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'action'  => 'filter_change',
		'nonce'   => wp_create_nonce( 'wpshortlist' ),
	) ), 'before' );
	// phpcs:enable
}

add_action( 'wp_enqueue_scripts', 'wpshortlist_enqueue_scripts' );
