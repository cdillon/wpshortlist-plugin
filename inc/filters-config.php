<?php
/**
 * Filter Configuration
 *
 * @package wpshortlist
 */

/**
 * Create our filter configuration.
 *
 * Tools have Features. Features have unique Methods and Supports. Define them here.
 */
function wpshortlist_set_config() {

	$filter_sets = array();

	/*
	 * Filter setup
	 */
	$tax_name       = 'wp_feature';
	$taxonomy       = get_taxonomy( $tax_name );
	$taxonomy_title = $taxonomy ? $taxonomy->labels->singular_name : 'Taxonomy not found';

	$filter_set_base = array(
		'name'           => '',
		'term'           => '',
		'taxonomy'       => $tax_name,
		'taxonomy_title' => $taxonomy_title,
		'post_types'     => array( 'tool' ),
		'filters'        => array(),
	);

	/*
	 * Display Term List
	 */
	$term = 'display-term-list';  // Must match term slug.

	$filter_sets[] = array_merge(
		$filter_set_base,
		array(
			'name'    => __( 'Display Term List', 'wpshortlist' ),
			'term'    => $term,
			'filters' => array(
				array(
					// Methods.
					'name'          => __( 'How', 'wpshortlist' ),
					'id'            => 'method',
					'query_var'     => "method-{$term}",
					'meta_box_type' => 'checkbox_list',
					'input_type'    => 'radio',
					'relation'      => 'OR',

					'options'       => array(
						'block'     => 'block',
						'widget'    => 'widget',
						'shortcode' => 'shortcode',
					),
				),
				array(
					// Supports.
					'name'          => __( 'For', 'wpshortlist' ),
					'id'            => 'supports',
					'query_var'     => "supports-{$term}",
					'meta_box_type' => 'checkbox_list',
					'input_type'    => 'checkbox',
					'relation'      => 'AND',
					'relation_desc' => __( 'Tool must support all selected options.', 'wpshortlist' ),

					'options'       => array(
						'categories'        => 'categories',
						'tags'              => 'tags',
						'custom-taxonomies' => 'custom taxonomies',
					),
				),
			),
		)
	);

	/*
	 * Display Terms Current Post
	 */
	$term = 'display-terms-current-post';

	$filter_sets[] = array_merge(
		$filter_set_base,
		array(
			'name'    => __( 'Display Terms Current Post', 'wpshortlist' ),
			'term'    => $term,
			'filters' => array(
				array(
					// Method.
					'name'          => __( 'How', 'wpshortlist' ),
					'id'            => 'method',
					'query_var'     => "method-{$term}",
					'meta_box_type' => 'checkbox_list',
					'input_type'    => 'radio',
					'relation'      => 'OR',

					'options'       => array(
						'automatic' => 'automatic',
						'block'     => 'block',
						'widget'    => 'widget',
						'shortcode' => 'shortcode',
					),
				),
				array(
					// Supports.
					'name'          => __( 'For', 'wpshortlist' ),
					'id'            => 'supports',
					'query_var'     => "supports-{$term}",
					'meta_box_type' => 'checkbox_list',
					'input_type'    => 'checkbox',
					'relation'      => 'AND',
					'relation_desc' => __( 'Tool must support all selected options.', 'wpshortlist' ),

					'options'       => array(
						'categories'        => 'categories',
						'tags'              => 'tags',
						'custom-taxonomies' => 'custom taxonomies',
					),
				),
			),
		)
	);

	q2( $filter_sets, '', 'o', 'filter-sets.log' );

	update_option( 'wpshortlist_filters', $filter_sets );
}

add_action( 'init', 'wpshortlist_set_config' );
