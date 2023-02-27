<?php
/**
 * Post Type: Category Proxy
 *
 * @package wpshortlist
 */

namespace Shortlist\Core\Post_Types;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Category_Proxy
 */
class Category_Proxy {

	/**
	 * Register
	 */
	public function register() {

		$labels = array(
			'name'                     => esc_html__( 'Categories Proxy', 'wpshortlist' ),
			'singular_name'            => esc_html__( 'Category Proxy', 'wpshortlist' ),
			'menu_name'                => esc_html__( 'Categories (proxy)', 'wpshortlist' ),
			'all_items'                => esc_html__( 'All Categories Proxy', 'wpshortlist' ),
			'add_new'                  => esc_html__( 'Add new', 'wpshortlist' ),
			'add_new_item'             => esc_html__( 'Add new Category Proxy', 'wpshortlist' ),
			'edit_item'                => esc_html__( 'Edit Category Proxy', 'wpshortlist' ),
			'new_item'                 => esc_html__( 'New Category Proxy', 'wpshortlist' ),
			'view_item'                => esc_html__( 'View Category Proxy', 'wpshortlist' ),
			'view_items'               => esc_html__( 'View Categories Proxy', 'wpshortlist' ),
			'search_items'             => esc_html__( 'Search Categories Proxy', 'wpshortlist' ),
			'not_found'                => esc_html__( 'No Categories Proxy found', 'wpshortlist' ),
			'not_found_in_trash'       => esc_html__( 'No Categories Proxy found in trash', 'wpshortlist' ),
			'parent'                   => esc_html__( 'Parent Category Proxy:', 'wpshortlist' ),
			'featured_image'           => esc_html__( 'Featured image for this Category Proxy', 'wpshortlist' ),
			'set_featured_image'       => esc_html__( 'Set featured image for this Category Proxy', 'wpshortlist' ),
			'remove_featured_image'    => esc_html__( 'Remove featured image for this Category Proxy', 'wpshortlist' ),
			'use_featured_image'       => esc_html__( 'Use as featured image for this Category Proxy', 'wpshortlist' ),
			'archives'                 => esc_html__( 'Category Proxy archives', 'wpshortlist' ),
			'insert_into_item'         => esc_html__( 'Insert into Category Proxy', 'wpshortlist' ),
			'uploaded_to_this_item'    => esc_html__( 'Upload to this Category Proxy', 'wpshortlist' ),
			'filter_items_list'        => esc_html__( 'Filter Categories Proxy list', 'wpshortlist' ),
			'items_list_navigation'    => esc_html__( 'Categories Proxy list navigation', 'wpshortlist' ),
			'items_list'               => esc_html__( 'Categories Proxy list', 'wpshortlist' ),
			'attributes'               => esc_html__( 'Categories Proxy attributes', 'wpshortlist' ),
			'name_admin_bar'           => esc_html__( 'Category Proxy', 'wpshortlist' ),
			'item_published'           => esc_html__( 'Category Proxy published', 'wpshortlist' ),
			'item_published_privately' => esc_html__( 'Category Proxy published privately.', 'wpshortlist' ),
			'item_reverted_to_draft'   => esc_html__( 'Category Proxy reverted to draft.', 'wpshortlist' ),
			'item_scheduled'           => esc_html__( 'Category Proxy scheduled', 'wpshortlist' ),
			'item_updated'             => esc_html__( 'Category Proxy updated.', 'wpshortlist' ),
			'parent_item_colon'        => esc_html__( 'Parent Category Proxy:', 'wpshortlist' ),
		);

		$args = array(
			'labels'                => $labels,
			'description'           => '',

			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'show_in_menu'          => true,
			'show_in_admin_bar'     => false,
			'exclude_from_search'   => false,
			'hierarchical'          => false,

			'show_in_rest'          => true,
			'rest_base'             => '',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'rest_namespace'        => 'wp/v2',

			'menu_position'         => null,
			'menu_icon'             => null,
			'capability_type'       => 'post',
			'map_meta_cap'          => true,
			'can_export'            => false,
			'delete_with_user'      => false,

			'has_archive'           => true,
			'rewrite'               => array(
				'slug'       => 'category_proxy',
				'with_front' => true,
			),
			'query_var'             => true,

			'template'              => array(),
			'supports'              => array( 'title', 'editor', 'thumbnail' ),
			'taxonomies'            => array(),
		);

		register_post_type( 'category_proxy', $args );
	}

	/**
	 * Unregister
	 */
	public function unregister() {
		unregister_post_type( 'category_proxy' );
	}

}
