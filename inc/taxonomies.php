<?php
/**
 * Load our taxonomies.
 */
require_once plugin_dir_path( __FILE__ ) . 'taxonomy-wp-category.php';
require_once plugin_dir_path( __FILE__ ) . 'taxonomy-tool-type.php';
require_once plugin_dir_path( __FILE__ ) . 'taxonomy-wp-feature.php';
require_once plugin_dir_path( __FILE__ ) . 'taxonomy-compatibility.php';
require_once plugin_dir_path( __FILE__ ) . 'taxonomy-workflow.php';

/**
 * Register our custom taxonomies.
 * 
 * Admin menu items are added in this order.
 */
function wpshortlist_register_taxonomies() {
	wpshortlist_register_taxonomy__wp_category();
	wpshortlist_register_taxonomy__tool_type();
	wpshortlist_register_taxonomy__wp_feature();
	wpshortlist_register_taxonomy__compatibility();
	wpshortlist_register_taxonomy__workflow();
}

add_action( 'init', 'wpshortlist_register_taxonomies' );

/**
 * Unregister our custom taxonomies.
 */
function wpshortlist_unregister_taxonomies() {
	unregister_taxonomy( 'wp_category' );
	unregister_taxonomy( 'tool_type' );
	unregister_taxonomy( 'wp_feature' );
	unregister_taxonomy( 'compatibility' );
	unregister_taxonomy( 'workflow' );
}
