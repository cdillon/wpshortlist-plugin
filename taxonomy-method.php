<?php
/**
 * Taxonomy: Method
 * 
 * No archive.
 */
function wpshortlist_register_taxonomy__method() {

	$labels = [
		'name' => esc_html__( 'Methods', 'wpshortlist' ),
		'singular_name' => esc_html__( 'Method', 'wpshortlist' ),
		'menu_name' => esc_html__( 'Methods', 'wpshortlist' ),
		'all_items' => esc_html__( 'All Methods', 'wpshortlist' ),
		'edit_item' => esc_html__( 'Edit Method', 'wpshortlist' ),
		'view_item' => esc_html__( 'View Method', 'wpshortlist' ),
		'update_item' => esc_html__( 'Update Method name', 'wpshortlist' ),
		'add_new_item' => esc_html__( 'Add new Method', 'wpshortlist' ),
		'new_item_name' => esc_html__( 'New Method name', 'wpshortlist' ),
		'parent_item' => esc_html__( 'Parent Method', 'wpshortlist' ),
		'parent_item_colon' => esc_html__( 'Parent Method:', 'wpshortlist' ),
		'search_items' => esc_html__( 'Search Methods', 'wpshortlist' ),
		'popular_items' => esc_html__( 'Popular Methods', 'wpshortlist' ),
		'separate_items_with_commas' => esc_html__( 'Separate Methods with commas', 'wpshortlist' ),
		'add_or_remove_items' => esc_html__( 'Add or remove Methods', 'wpshortlist' ),
		'choose_from_most_used' => esc_html__( 'Choose from the most used Methods', 'wpshortlist' ),
		'not_found' => esc_html__( 'No Methods found', 'wpshortlist' ),
		'no_terms' => esc_html__( 'No Methods', 'wpshortlist' ),
		'items_list_navigation' => esc_html__( 'Methods list navigation', 'wpshortlist' ),
		'items_list' => esc_html__( 'Methods list', 'wpshortlist' ),
		'back_to_items' => esc_html__( 'Back to Methods', 'wpshortlist' ),
		'name_field_description' => esc_html__( 'The name is how it appears on your site.', 'wpshortlist' ),
		'parent_field_description' => esc_html__( 'Assign a parent term to create a hierarchy. The term Jazz, for example, would be the parent of Bebop and Big Band.', 'wpshortlist' ),
		'slug_field_description' => esc_html__( 'The slug is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', 'wpshortlist' ),
		'desc_field_description' => esc_html__( 'The description is not prominent by default; however, some themes may show it.', 'wpshortlist' ),
	];
	
	$args = [
		// 'label' => esc_html__( 'Methods', 'wpshortlist' ),
		'labels' => $labels,

		/* 
		 * To enable front-end archive:
		 *   'public' => true,
		 *   'publicly_queryable' => true,
		 *   'show_ui' => true,   // default = $public
		 *   'rewrite' => true,   // or custom; recommended but optional
		 *
		 * To disable front-end archive:
		 *   'public' => false,
		 *   'publicly_queryable' => false,
		 *   'show_ui' => true,   // default = $public -> so must be explicit
		 *   'show_tagcloud' => false,
		 *   'rewrite' => false,
		 */

		// 'public' => true,   // required if using Proxy
		'public' => false,
		/*
		 * inherited from $public: 
		 */
		// 'publicly_queryable' => true,
		'publicly_queryable' => false,
		'show_in_nav_menus' => true,   // false if using proxy
		'show_ui' => true,
		/* 
		 * then inherited from $show_ui: 
		 */
		'show_tagcloud' => false,
		// 'show_in_quick_edit' => true,
		// 'show_in_menu' => true,
		
		'hierarchical' => true,

		/*
		 * if using proxy, `rewrite` and `query_var` recommended but not required
		 */
		// 'rewrite' => true,
		// 'rewrite' => [ 'slug' => 'methods', 'with_front' => true, ],
		'rewrite' => false,
		// 'query_var' => true,
		'query_var' => 'tool-method',

		'show_admin_column' => true,

		'show_in_rest' => true,
		'rest_base' => 'method',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
		'rest_namespace' => 'wp/v2',

		/*
		 * 3rd party
		 */
		'show_in_graphql' => false,
	];

	register_taxonomy( 'method', [ 'tool' ], $args );
}
