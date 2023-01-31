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
require_once plugin_dir_path( __FILE__ ) . 'functions-query.php';
require_once plugin_dir_path( __FILE__ ) . 'functions-meta-box.php';
require_once plugin_dir_path( __FILE__ ) . 'post-types.php';
require_once plugin_dir_path( __FILE__ ) . 'taxonomies.php';

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
