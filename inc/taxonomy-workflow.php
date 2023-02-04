<?php

/**
 * Taxonomy: Workflow
 */
function wpshortlist_register_taxonomy__workflow() {

	$labels = [
		'name' => esc_html__( 'Workflow', 'wpshortlist' ),
		'singular_name' => esc_html__( 'Workflow', 'wpshortlist' ),
		'menu_name' => esc_html__( 'Workflow (private)', 'wpshortlist' ),
		'all_items' => esc_html__( 'All Workflow', 'wpshortlist' ),
		'edit_item' => esc_html__( 'Edit Workflow', 'wpshortlist' ),
		'view_item' => esc_html__( 'View Workflow', 'wpshortlist' ),
		'update_item' => esc_html__( 'Update Workflow name', 'wpshortlist' ),
		'add_new_item' => esc_html__( 'Add new Workflow', 'wpshortlist' ),
		'new_item_name' => esc_html__( 'New Workflow name', 'wpshortlist' ),
		'parent_item' => esc_html__( 'Parent Workflow', 'wpshortlist' ),
		'parent_item_colon' => esc_html__( 'Parent Workflow:', 'wpshortlist' ),
		'search_items' => esc_html__( 'Search Workflow', 'wpshortlist' ),
		'popular_items' => esc_html__( 'Popular Workflow', 'wpshortlist' ),
		'separate_items_with_commas' => esc_html__( 'Separate Workflow with commas', 'wpshortlist' ),
		'add_or_remove_items' => esc_html__( 'Add or remove Workflow', 'wpshortlist' ),
		'choose_from_most_used' => esc_html__( 'Choose from the most used Workflow', 'wpshortlist' ),
		'not_found' => esc_html__( 'No Workflow found', 'wpshortlist' ),
		'no_terms' => esc_html__( 'No Workflow', 'wpshortlist' ),
		'items_list_navigation' => esc_html__( 'Workflow list navigation', 'wpshortlist' ),
		'items_list' => esc_html__( 'Workflow list', 'wpshortlist' ),
		'back_to_items' => esc_html__( 'Back to Workflow', 'wpshortlist' ),
		'name_field_description' => esc_html__( 'The name is how it appears on your site.', 'wpshortlist' ),
		'parent_field_description' => esc_html__( 'Assign a parent term to create a hierarchy. The term Jazz, for example, would be the parent of Bebop and Big Band.', 'wpshortlist' ),
		'slug_field_description' => esc_html__( 'The slug is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', 'wpshortlist' ),
		'desc_field_description' => esc_html__( 'The description is not prominent by default; however, some themes may show it.', 'wpshortlist' ),
	];
	
	$args = [
		// 'label' => esc_html__( 'Workflow', 'wpshortlist' ),
		'labels' => $labels,

		'public' => false,
		/*
		 * inherited from $public: 
		 */
		// 'publicly_queryable' => false,
		// 'show_in_nav_menus' => false,
		'show_ui' => true,
		/* 
		 * then inherited from $show_ui: 
		 */
		'show_tagcloud' => false,
		'show_in_quick_edit' => true,
		'show_in_menu' => true,

		'hierarchical' => true,

		'rewrite' => false,
		'query_var' => false,

		'show_admin_column' => true,
		
		'show_in_rest' => true,
		'rest_base' => 'workflow',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
		'rest_namespace' => 'wp/v2',

		/*
		 * 3rd party
		 */
		'show_in_graphql' => false,
	];

	register_taxonomy( 'workflow', [ 'tool' ], $args );
}
