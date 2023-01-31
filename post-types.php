<?php

require_once plugin_dir_path( __FILE__ ) . 'post-type-tool.php';
require_once plugin_dir_path( __FILE__ ) . 'post-type-feature-proxy.php';
require_once plugin_dir_path( __FILE__ ) . 'post-type-category-proxy.php';

/**
 * Register our custom post types
 */
function wpshortlist_register_post_types() {
	wpshortlist_register_post_type__tool();
	wpshortlist_register_post_type__feature_proxy();
	wpshortlist_register_post_type__category_proxy();
}
add_action( 'init', 'wpshortlist_register_post_types' );

/**
 * Unregister our custom post types.
 */
function wpshortlist_unregister_post_types() {
	unregister_post_type( 'tool' );
	unregister_post_type( 'feature_proxy' );
	unregister_post_type( 'category_proxy' );
}
