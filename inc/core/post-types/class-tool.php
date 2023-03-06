<?php
/**
 * Post Type: Tool
 *
 * @package wpshortlist
 */

namespace Shortlist\Core\Post_Types;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Tool
 */
class Tool {

	/**
	 * Register
	 */
	public function register() {

		$labels = array(
			'name'                     => esc_html__( 'Tools', 'wpshortlist' ),
			'singular_name'            => esc_html__( 'Tool', 'wpshortlist' ),

			'archive_title'            => esc_html__( 'Tool Directory', 'wpshortlist' ),

			'menu_name'                => esc_html__( 'Tools', 'wpshortlist' ),
			'all_items'                => esc_html__( 'All Tools', 'wpshortlist' ),
			'add_new'                  => esc_html__( 'Add new', 'wpshortlist' ),
			'add_new_item'             => esc_html__( 'Add new Tool', 'wpshortlist' ),
			'edit_item'                => esc_html__( 'Edit Tool', 'wpshortlist' ),
			'new_item'                 => esc_html__( 'New Tool', 'wpshortlist' ),
			'view_item'                => esc_html__( 'View Tool', 'wpshortlist' ),
			'view_items'               => esc_html__( 'View Tools', 'wpshortlist' ),
			'search_items'             => esc_html__( 'Search Tools', 'wpshortlist' ),
			'not_found'                => esc_html__( 'No Tools found', 'wpshortlist' ),
			'not_found_in_trash'       => esc_html__( 'No Tools found in trash', 'wpshortlist' ),
			'parent'                   => esc_html__( 'Parent Tool:', 'wpshortlist' ),
			'featured_image'           => esc_html__( 'Featured image for this Tool', 'wpshortlist' ),
			'set_featured_image'       => esc_html__( 'Set featured image for this Tool', 'wpshortlist' ),
			'remove_featured_image'    => esc_html__( 'Remove featured image for this Tool', 'wpshortlist' ),
			'use_featured_image'       => esc_html__( 'Use as featured image for this Tool', 'wpshortlist' ),
			'archives'                 => esc_html__( 'Tool archives', 'wpshortlist' ),
			'insert_into_item'         => esc_html__( 'Insert into Tool', 'wpshortlist' ),
			'uploaded_to_this_item'    => esc_html__( 'Upload to this Tool', 'wpshortlist' ),
			'filter_items_list'        => esc_html__( 'Filter Tools list', 'wpshortlist' ),
			'items_list_navigation'    => esc_html__( 'Tools list navigation', 'wpshortlist' ),
			'items_list'               => esc_html__( 'Tools list', 'wpshortlist' ),
			'attributes'               => esc_html__( 'Tools attributes', 'wpshortlist' ),
			'name_admin_bar'           => esc_html__( 'Tool', 'wpshortlist' ),
			'item_published'           => esc_html__( 'Tool published', 'wpshortlist' ),
			'item_published_privately' => esc_html__( 'Tool published privately.', 'wpshortlist' ),
			'item_reverted_to_draft'   => esc_html__( 'Tool reverted to draft.', 'wpshortlist' ),
			'item_scheduled'           => esc_html__( 'Tool scheduled', 'wpshortlist' ),
			'item_updated'             => esc_html__( 'Tool updated.', 'wpshortlist' ),
			'parent_item_colon'        => esc_html__( 'Parent Tool:', 'wpshortlist' ),
		);

		$args = array(
			'labels'                => $labels,
			'description'           => '',

			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'show_in_menu'          => true,
			'show_in_admin_bar'     => true,
			'exclude_from_search'   => false,
			'hierarchical'          => false,

			'has_archive'           => true,
			'rewrite'               => array(
				'slug'       => 'tools',
				'with_front' => true,
			),
			'query_var'             => true,

			'show_in_rest'          => true,
			'rest_base'             => '',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'rest_namespace'        => 'wp/v2',

			'menu_position'         => null,
			'menu_icon'             => null,   // Default is 'dashicons-admin-post'.
			'capability_type'       => 'post',
			'map_meta_cap'          => true,
			'can_export'            => false,
			'delete_with_user'      => false,

			/*
			 * Array of blocks to use as the default initial state for an editor session.
			 * Each item should be an array containing block name and optional attributes.
			 */
			'template'              => array(),

			'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' ),
			'taxonomies'            => array(),
		);

		register_post_type( 'tool', $args );
	}

	/**
	 * Unregister
	 */
	public function unregister() {
		unregister_post_type( 'tool' );
	}

}
