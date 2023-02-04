<?php
/**
 * Post Type: Features Proxy
 */
function wpshortlist_register_post_type__feature_proxy() {

	$labels = [
		'name' => esc_html__( 'Features', 'wpshortlist' ),
		'singular_name' => esc_html__( 'Feature Proxy', 'wpshortlist' ),
		'menu_name' => esc_html__( 'Features (proxy)', 'wpshortlist' ),
		'all_items' => esc_html__( 'All Features', 'wpshortlist' ),
		'add_new' => esc_html__( 'Add new', 'wpshortlist' ),
		'add_new_item' => esc_html__( 'Add new Feature Proxy', 'wpshortlist' ),
		'edit_item' => esc_html__( 'Edit Feature Proxy', 'wpshortlist' ),
		'new_item' => esc_html__( 'New Feature Proxy', 'wpshortlist' ),
		'view_item' => esc_html__( 'View Feature Proxy', 'wpshortlist' ),
		'view_items' => esc_html__( 'View Features Proxy', 'wpshortlist' ),
		'search_items' => esc_html__( 'Search Features Proxy', 'wpshortlist' ),
		'not_found' => esc_html__( 'No Features Proxy found', 'wpshortlist' ),
		'not_found_in_trash' => esc_html__( 'No Features Proxy found in trash', 'wpshortlist' ),
		'parent' => esc_html__( 'Parent Feature Proxy:', 'wpshortlist' ),
		'featured_image' => esc_html__( 'Featured image for this Feature Proxy', 'wpshortlist' ),
		'set_featured_image' => esc_html__( 'Set featured image for this Feature Proxy', 'wpshortlist' ),
		'remove_featured_image' => esc_html__( 'Remove featured image for this Feature Proxy', 'wpshortlist' ),
		'use_featured_image' => esc_html__( 'Use as featured image for this Feature Proxy', 'wpshortlist' ),
		'archives' => esc_html__( 'Feature Proxy archives', 'wpshortlist' ),
		'insert_into_item' => esc_html__( 'Insert into Feature Proxy', 'wpshortlist' ),
		'uploaded_to_this_item' => esc_html__( 'Upload to this Feature Proxy', 'wpshortlist' ),
		'filter_items_list' => esc_html__( 'Filter Features Proxy list', 'wpshortlist' ),
		'items_list_navigation' => esc_html__( 'Features Proxy list navigation', 'wpshortlist' ),
		'items_list' => esc_html__( 'Features Proxy list', 'wpshortlist' ),
		'attributes' => esc_html__( 'Features Proxy attributes', 'wpshortlist' ),
		'name_admin_bar' => esc_html__( 'Feature Proxy', 'wpshortlist' ),
		'item_published' => esc_html__( 'Feature Proxy published', 'wpshortlist' ),
		'item_published_privately' => esc_html__( 'Feature Proxy published privately.', 'wpshortlist' ),
		'item_reverted_to_draft' => esc_html__( 'Feature Proxy reverted to draft.', 'wpshortlist' ),
		'item_scheduled' => esc_html__( 'Feature Proxy scheduled', 'wpshortlist' ),
		'item_updated' => esc_html__( 'Feature Proxy updated.', 'wpshortlist' ),
		'parent_item_colon' => esc_html__( 'Parent Feature Proxy:', 'wpshortlist' ),
	];

	$args = [
		'label' => esc_html__( 'Features', 'wpshortlist' ),
		'labels' => $labels,
		'description' => 'Find the <strong>feature you need</strong> in blocks, plugins or themes.',

		'public' => true,
		/*
		 * inherited from $public:
		 */
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_nav_menus' => true,
		/*
		 * then inherited from $show_ui:
		 */ 
		'show_in_menu' => true,
		/*
		 * then inherited from $show_in_menu:
		 */
		'show_in_admin_bar' => false,

		'exclude_from_search' => false,
		
		'hierarchical' => false,

		/*
		 * Don't comment out in case something enables the classic editor.
		 */
		'show_in_rest' => true,
		'rest_base' => '',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
		'rest_namespace' => 'wp/v2',

		'menu_position' => null,
		'menu_icon' => null,   // then 'dashicons-admin-post'

		'capability_type' => 'post',
		'map_meta_cap' => true,

		'has_archive' => 'features',
		'rewrite' => [ 'slug' => 'feature_proxy', 'with_front' => true ],
		'query_var' => true,

		'can_export' => false,
		'delete_with_user' => false,

		'template' => [],
		'supports' => [ 'title', 'editor', 'thumbnail' ],
		'taxonomies' => [],

		/*
		 * 3rd party
		 */
		'show_in_graphql' => false,
	];

	// key must not exceed 20 characters
	register_post_type( 'feature_proxy', $args );
}
