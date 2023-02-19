<?php
/**
 * Taxonomy: WP Category
 *
 * @package wpshortlist
 */

/**
 * Register taxonomy.
 */
function wpshortlist_register_taxonomy__feature_category() {

	$labels = array(
		'name'                       => esc_html__( 'Feature Categories', 'wpshortlist' ),
		'singular_name'              => esc_html__( 'Feature Category', 'wpshortlist' ),
		'menu_name'                  => esc_html__( 'Feature Categories', 'wpshortlist' ),
		'all_items'                  => esc_html__( 'All Feature Categories', 'wpshortlist' ),
		'edit_item'                  => esc_html__( 'Edit Feature Category', 'wpshortlist' ),
		'view_item'                  => esc_html__( 'View Feature Category', 'wpshortlist' ),
		'update_item'                => esc_html__( 'Update Feature Category name', 'wpshortlist' ),
		'add_new_item'               => esc_html__( 'Add new Feature Category', 'wpshortlist' ),
		'new_item_name'              => esc_html__( 'New Feature Category name', 'wpshortlist' ),
		'parent_item'                => esc_html__( 'Parent Feature Category', 'wpshortlist' ),
		'parent_item_colon'          => esc_html__( 'Parent Feature Category:', 'wpshortlist' ),
		'search_items'               => esc_html__( 'Search Feature Categories', 'wpshortlist' ),
		'popular_items'              => esc_html__( 'Popular Feature Categories', 'wpshortlist' ),
		'separate_items_with_commas' => esc_html__( 'Separate Feature Categories with commas', 'wpshortlist' ),
		'add_or_remove_items'        => esc_html__( 'Add or remove Feature Categories', 'wpshortlist' ),
		'choose_from_most_used'      => esc_html__( 'Choose from the most used Feature Categories', 'wpshortlist' ),
		'not_found'                  => esc_html__( 'No Feature Categories found', 'wpshortlist' ),
		'no_terms'                   => esc_html__( 'No Feature Categories', 'wpshortlist' ),
		'items_list_navigation'      => esc_html__( 'Feature Categories list navigation', 'wpshortlist' ),
		'items_list'                 => esc_html__( 'Feature Categories list', 'wpshortlist' ),
		'back_to_items'              => esc_html__( 'Back to Feature Categories', 'wpshortlist' ),
		'name_field_description'     => esc_html__( 'The name is how it appears on your site.', 'wpshortlist' ),
		'parent_field_description'   => esc_html__( 'Assign a parent term to create a hierarchy.', 'wpshortlist' ),
		'slug_field_description'     => esc_html__( 'The slug is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', 'wpshortlist' ),
		'desc_field_description'     => esc_html__( 'The description is not prominent by default; however, some themes may show it.', 'wpshortlist' ),
	);

	$args = array(
		'labels'                => $labels,
		'public'                => true,  // Required if using proxy.
		'publicly_queryable'    => true,  // Required if using proxy.
		'show_in_nav_menus'     => false,  // Use proxy instead.
		'show_ui'               => true,
		'show_tagcloud'         => false,
		'show_in_quick_edit'    => true,
		'show_in_menu'          => true,
		'show_admin_column'     => true,
		'hierarchical'          => true,

		// If using proxy, `rewrite` and `query_var` recommended but not required.
		'rewrite'               => array(
			'slug'       => 'feature-category',
			'with_front' => true,
		),
		'query_var'             => 'feature-category',

		'show_in_rest'          => true,
		'rest_base'             => 'feature_category',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
		'rest_namespace'        => 'wp/v2',

		// Custom properties.
		'path'                  => 'feature-category',
	);

	register_taxonomy( 'feature_category', array( 'feature_proxy' ), $args );
}
