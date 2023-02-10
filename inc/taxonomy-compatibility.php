<?php
/**
 * Taxonomy: Compatibility
 *
 * @package wpshortlist
 */

/**
 * Register taxonomy.
 */
function wpshortlist_register_taxonomy__compatibility() {

	$labels = array(
		'name'                       => esc_html__( 'Compatibility', 'wpshortlist' ),
		'singular_name'              => esc_html__( 'Compatibility', 'wpshortlist' ),
		'menu_name'                  => esc_html__( 'Compatibility (no archive)', 'wpshortlist' ),
		'all_items'                  => esc_html__( 'All Compatibility', 'wpshortlist' ),
		'edit_item'                  => esc_html__( 'Edit Compatibility', 'wpshortlist' ),
		'view_item'                  => esc_html__( 'View Compatibility', 'wpshortlist' ),
		'update_item'                => esc_html__( 'Update Compatibility name', 'wpshortlist' ),
		'add_new_item'               => esc_html__( 'Add new Compatibility', 'wpshortlist' ),
		'new_item_name'              => esc_html__( 'New Compatibility name', 'wpshortlist' ),
		'parent_item'                => esc_html__( 'Parent Compatibility', 'wpshortlist' ),
		'parent_item_colon'          => esc_html__( 'Parent Compatibility:', 'wpshortlist' ),
		'search_items'               => esc_html__( 'Search Compatibility', 'wpshortlist' ),
		'popular_items'              => esc_html__( 'Popular Compatibility', 'wpshortlist' ),
		'separate_items_with_commas' => esc_html__( 'Separate Compatibility with commas', 'wpshortlist' ),
		'add_or_remove_items'        => esc_html__( 'Add or remove Compatibility', 'wpshortlist' ),
		'choose_from_most_used'      => esc_html__( 'Choose from the most used Compatibility', 'wpshortlist' ),
		'not_found'                  => esc_html__( 'No Compatibility found', 'wpshortlist' ),
		'no_terms'                   => esc_html__( 'No Compatibility', 'wpshortlist' ),
		'items_list_navigation'      => esc_html__( 'Compatibility list navigation', 'wpshortlist' ),
		'items_list'                 => esc_html__( 'Compatibility list', 'wpshortlist' ),
		'back_to_items'              => esc_html__( 'Back to Compatibility', 'wpshortlist' ),
		'name_field_description'     => esc_html__( 'The name is how it appears on your site.', 'wpshortlist' ),
		'parent_field_description'   => esc_html__( 'Assign a parent term to create a hierarchy.', 'wpshortlist' ),
		'slug_field_description'     => esc_html__( 'The slug is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', 'wpshortlist' ),
		'desc_field_description'     => esc_html__( 'The description is not prominent by default; however, some themes may show it.', 'wpshortlist' ),
	);

	$args = array(
		'labels'                => $labels,
		'public'                => false,
		'publicly_queryable'    => true,
		'show_in_nav_menus'     => true,
		'show_ui'               => true,
		'show_tagcloud'         => false,
		'show_in_quick_edit'    => true,
		'show_in_menu'          => true,
		'show_admin_column'     => true,
		'hierarchical'          => true,

		'rewrite'               => false,
		'query_var'             => false,

		'show_in_rest'          => true,
		'rest_base'             => 'compatibility',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
		'rest_namespace'        => 'wp/v2',
	);

	register_taxonomy( 'compatibility', array( 'tool' ), $args );
}
