<?php
/**
 * Taxonomy: Feature
 *
 * @package wpshortlist
 */

namespace Shortlist\Core\Taxonomies;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Feature
 */
class Feature {

	/**
	 * Register taxonomy
	 */
	public function register() {

		$labels = array(
			'name'                       => esc_html__( 'Features', 'wpshortlist' ),
			'singular_name'              => esc_html__( 'Feature', 'wpshortlist' ),

			'breadcrumb'                 => esc_html__( 'Feature', 'wpshortlist' ),

			'menu_name'                  => esc_html__( 'Features', 'wpshortlist' ),
			'all_items'                  => esc_html__( 'All Features', 'wpshortlist' ),
			'edit_item'                  => esc_html__( 'Edit Feature', 'wpshortlist' ),
			'view_item'                  => esc_html__( 'View Feature', 'wpshortlist' ),
			'update_item'                => esc_html__( 'Update Feature name', 'wpshortlist' ),
			'add_new_item'               => esc_html__( 'Add new Feature', 'wpshortlist' ),
			'new_item_name'              => esc_html__( 'New Feature name', 'wpshortlist' ),
			'parent_item'                => esc_html__( 'Parent Feature', 'wpshortlist' ),
			'parent_item_colon'          => esc_html__( 'Parent Feature:', 'wpshortlist' ),
			'search_items'               => esc_html__( 'Search Features', 'wpshortlist' ),
			'popular_items'              => esc_html__( 'Popular Features', 'wpshortlist' ),
			'separate_items_with_commas' => esc_html__( 'Separate Features with commas', 'wpshortlist' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Features', 'wpshortlist' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Features', 'wpshortlist' ),
			'not_found'                  => esc_html__( 'No Features found', 'wpshortlist' ),
			'no_terms'                   => esc_html__( 'No Features', 'wpshortlist' ),
			'items_list_navigation'      => esc_html__( 'Features list navigation', 'wpshortlist' ),
			'items_list'                 => esc_html__( 'Features list', 'wpshortlist' ),
			'back_to_items'              => esc_html__( 'Back to Features', 'wpshortlist' ),
			'name_field_description'     => esc_html__( 'The name is how it appears on your site.', 'wpshortlist' ),
			'parent_field_description'   => esc_html__( 'Assign a parent term to create a hierarchy.', 'wpshortlist' ),
			'slug_field_description'     => esc_html__( 'The slug is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', 'wpshortlist' ),
			'desc_field_description'     => esc_html__( 'The description is not prominent by default; however, some themes may show it.', 'wpshortlist' ),
		);

		$args = array(
			'labels'                => $labels,
			'public'                => true,  // Required if using proxy.
			'publicly_queryable'    => true,  // Required if using proxy.
			'show_in_nav_menus'     => false,  // False if using proxy.
			'show_ui'               => true,
			'show_tagcloud'         => false,
			'show_in_quick_edit'    => true,
			'show_in_menu'          => true,
			'show_admin_column'     => true,
			'hierarchical'          => true,

			'rewrite'               => array(
				'slug'       => 'features',
				'with_front' => true,
			),
			'query_var'             => 'feature',

			'show_in_rest'          => true,
			'rest_base'             => 'feature',
			'rest_controller_class' => 'WP_REST_Terms_Controller',
			'rest_namespace'        => 'wp/v2',

			// Custom properties.
			'path'                  => 'features',  // Must match rewrite slug.
		);

		register_taxonomy( 'feature', array( 'tool' ), $args );
	}

	/**
	 * Unregister
	 */
	public function unregister() {
		unregister_taxonomy( 'feature' );
	}

}
