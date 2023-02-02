<?php
/**
 * Plugin Name: WP Shortlist
 * Plugin URI: 
 * Description: 
 * Version: 0.1
 * Author: Chris Dillon
 * Author URI: https://chrisdillon.dev
 */

require_once plugin_dir_path( __FILE__ ) . 'functions.php';
require_once plugin_dir_path( __FILE__ ) . 'functions-ajax.php';
require_once plugin_dir_path( __FILE__ ) . 'functions-query.php';
require_once plugin_dir_path( __FILE__ ) . 'functions-meta-box.php';
require_once plugin_dir_path( __FILE__ ) . 'post-types.php';
require_once plugin_dir_path( __FILE__ ) . 'taxonomies.php';
require_once plugin_dir_path( __FILE__ ) . 'filters.php';
require_once plugin_dir_path( __FILE__ ) . 'filters-config.php';
require_once plugin_dir_path( __FILE__ ) . 'filters-templates.php';
require_once plugin_dir_path( __FILE__ ) . 'widgets.php';

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
	wp_enqueue_script( 'wpshortlist', plugins_url( '/js/wpshortlist.js', __FILE__ ), array( 'jquery' ) );
	
	wp_add_inline_script( 'wpshortlist', 'const wpshortlistSettings = ' . json_encode( array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'action'  => 'filter_change',
		'nonce'   => wp_create_nonce( 'wpshortlist' ),
	) ), 'before' );
}

add_action( 'wp_enqueue_scripts', 'wpshortlist_enqueue_scripts' );
