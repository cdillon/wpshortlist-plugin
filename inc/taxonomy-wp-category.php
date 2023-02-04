<?php
/**
 * Taxonomy: WP Category
 */
function wpshortlist_register_taxonomy__wp_category() {

	$labels = [
		'name' => esc_html__( 'Tool Categories', 'wpshortlist' ),
		'singular_name' => esc_html__( 'Tool Category', 'wpshortlist' ),
		'menu_name' => esc_html__( 'Tool Categories', 'wpshortlist' ),
		'all_items' => esc_html__( 'All Tool Categories', 'wpshortlist' ),
		'edit_item' => esc_html__( 'Edit Tool Category', 'wpshortlist' ),
		'view_item' => esc_html__( 'View Tool Category', 'wpshortlist' ),
		'update_item' => esc_html__( 'Update Tool Category name', 'wpshortlist' ),
		'add_new_item' => esc_html__( 'Add new Tool Category', 'wpshortlist' ),
		'new_item_name' => esc_html__( 'New Tool Category name', 'wpshortlist' ),
		'parent_item' => esc_html__( 'Parent Tool Category', 'wpshortlist' ),
		'parent_item_colon' => esc_html__( 'Parent Tool Category:', 'wpshortlist' ),
		'search_items' => esc_html__( 'Search Tool Categories', 'wpshortlist' ),
		'popular_items' => esc_html__( 'Popular Tool Categories', 'wpshortlist' ),
		'separate_items_with_commas' => esc_html__( 'Separate Tool Categories with commas', 'wpshortlist' ),
		'add_or_remove_items' => esc_html__( 'Add or remove Tool Categories', 'wpshortlist' ),
		'choose_from_most_used' => esc_html__( 'Choose from the most used Tool Categories', 'wpshortlist' ),
		'not_found' => esc_html__( 'No Tool Categories found', 'wpshortlist' ),
		'no_terms' => esc_html__( 'No Tool Categories', 'wpshortlist' ),
		'items_list_navigation' => esc_html__( 'Tool Categories list navigation', 'wpshortlist' ),
		'items_list' => esc_html__( 'Tool Categories list', 'wpshortlist' ),
		'back_to_items' => esc_html__( 'Back to Tool Categories', 'wpshortlist' ),
		'name_field_description' => esc_html__( 'The name is how it appears on your site.', 'wpshortlist' ),
		'parent_field_description' => esc_html__( 'Assign a parent term to create a hierarchy. The term Jazz, for example, would be the parent of Bebop and Big Band.', 'wpshortlist' ),
		'slug_field_description' => esc_html__( 'The slug is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', 'wpshortlist' ),
		'desc_field_description' => esc_html__( 'The description is not prominent by default; however, some themes may show it.', 'wpshortlist' ),
	];
	
	$args = [
		'label' => esc_html__( 'Tool Categories', 'wpshortlist' ),
		'labels' => $labels,

		'public' => true,   // required if using proxy
		/*
		 * inherited from $public: 
		 */
		'publicly_queryable' => true,   // required if using proxy
		'show_in_nav_menus' => false,   // use proxy instead
		// 'show_ui' => true,
		/* 
		 * then inherited from $show_ui: 
		 */
		// 'show_tagcloud' => true,
		// 'show_in_quick_edit' => true,
		// 'show_in_menu' => true,
		
		'hierarchical' => true,

		/*
		 * if using proxy, `rewrite` and `query_var` recommended but not required
		 */
		// 'rewrite' => true,
		'rewrite' => [ 'slug' => 'wp-category', 'with_front' => true, ],   // usually plural
		// 'query_var' => true,
		'query_var' => 'wp-category',   // usually singular

		'show_admin_column' => true,

		'show_in_rest' => true,
		'rest_base' => 'wp_category',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
		'rest_namespace' => 'wp/v2',
		
		// custom
		'path' => 'wp-category',
	];

	register_taxonomy( 'wp_category', [ 'tool' ], $args );
}
