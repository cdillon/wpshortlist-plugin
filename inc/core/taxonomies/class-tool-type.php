<?php
/**
 * Taxonomy: Tool Type
 *
 * @package wpshortlist
 */

namespace Shortlist\Core\Taxonomies;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Tool_Type
 */
class Tool_Type {

	/**
	 * Register taxonomy
	 */
	public function register() {

		$labels = array(
			'name'                       => esc_html__( 'Tool Types', 'wpshortlist' ),
			'singular_name'              => esc_html__( 'Tool Type', 'wpshortlist' ),
			'menu_name'                  => esc_html__( 'Tool Types', 'wpshortlist' ),
			'all_items'                  => esc_html__( 'All Tool Types', 'wpshortlist' ),
			'edit_item'                  => esc_html__( 'Edit Tool Type', 'wpshortlist' ),
			'view_item'                  => esc_html__( 'View Tool Type', 'wpshortlist' ),
			'update_item'                => esc_html__( 'Update Tool Type name', 'wpshortlist' ),
			'add_new_item'               => esc_html__( 'Add new Tool Type', 'wpshortlist' ),
			'new_item_name'              => esc_html__( 'New Tool Type name', 'wpshortlist' ),
			'parent_item'                => esc_html__( 'Parent Tool Type', 'wpshortlist' ),
			'parent_item_colon'          => esc_html__( 'Parent Tool Type:', 'wpshortlist' ),
			'search_items'               => esc_html__( 'Search Tool Types', 'wpshortlist' ),
			'popular_items'              => esc_html__( 'Popular Tool Types', 'wpshortlist' ),
			'separate_items_with_commas' => esc_html__( 'Separate Tool Types with commas', 'wpshortlist' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Tool Types', 'wpshortlist' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Tool Types', 'wpshortlist' ),
			'not_found'                  => esc_html__( 'No Tool Types found', 'wpshortlist' ),
			'no_terms'                   => esc_html__( 'No Tool Types', 'wpshortlist' ),
			'items_list_navigation'      => esc_html__( 'Tool Types list navigation', 'wpshortlist' ),
			'items_list'                 => esc_html__( 'Tool Types list', 'wpshortlist' ),
			'back_to_items'              => esc_html__( 'Back to Tool Types', 'wpshortlist' ),
			'name_field_description'     => esc_html__( 'The name is how it appears on your site.', 'wpshortlist' ),
			'parent_field_description'   => esc_html__( 'Assign a parent term to create a hierarchy.', 'wpshortlist' ),
			'slug_field_description'     => esc_html__( 'The slug is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', 'wpshortlist' ),
			'desc_field_description'     => esc_html__( 'The description is not prominent by default; however, some themes may show it.', 'wpshortlist' ),
		);

		$args = array(
			'labels'                => $labels,
			'public'                => true,
			'publicly_queryable'    => true,
			'show_in_nav_menus'     => false,
			'show_ui'               => true,
			'show_tagcloud'         => false,
			'show_in_quick_edit'    => true,
			'show_in_menu'          => true,
			'show_admin_column'     => true,
			'hierarchical'          => true,

			'rewrite'               => array(
				'slug'       => 'tool-type',
				'with_front' => true,
			),
			'query_var'             => 'tool-type',

			'show_in_rest'          => true,
			'rest_base'             => 'tool_type',
			'rest_controller_class' => 'WP_REST_Terms_Controller',
			'rest_namespace'        => 'wp/v2',

			// Custom properties.
			'path'                  => 'tool-type',
		);

		register_taxonomy( 'tool_type', array( 'tool' ), $args );
	}

	/**
	 * Unregister
	 */
	public function unregister() {
		unregister_taxonomy( 'tool_type' );
	}

}
