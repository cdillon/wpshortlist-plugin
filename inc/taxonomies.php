<?php
/**
 * Load our taxonomies.
 *
 * @package wpshortlist
 */

/*
 * Load files.
 */
require_once plugin_dir_path( __FILE__ ) . 'taxonomy-feature-category.php';
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
	wpshortlist_register_taxonomy__feature_category();
	wpshortlist_register_taxonomy__tool_type();
	wpshortlist_register_taxonomy__feature();
	wpshortlist_register_taxonomy__compatibility();
	wpshortlist_register_taxonomy__workflow();
}

add_action( 'init', 'wpshortlist_register_taxonomies' );

/**
 * Unregister our custom taxonomies.
 */
function wpshortlist_unregister_taxonomies() {
	unregister_taxonomy( 'feature_category' );
	unregister_taxonomy( 'tool_type' );
	unregister_taxonomy( 'feature' );
	unregister_taxonomy( 'compatibility' );
	unregister_taxonomy( 'workflow' );
}
