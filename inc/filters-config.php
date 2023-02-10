<?php
/**
 * Filter Configuration
 *
 * @package wpshortlist
 *
 * @todo This is one of the first things that should be converted to a class.
 */

/**
 * Return our filters config array.
 */
function wpshortlist_get_config() {
	return get_option( 'wpshortlist_filters' );
}

/**
 * Return the filter set for the current taxonomy and term
 * or a specific taxonomy and term. Returns false if not found.
 *
 * @param array $params  Parameters.
 *
 * @return array|false
 */
function wpshortlist_get_filter_set( $params ) {
	// This needs to use wpshortlist_get_current_query_type instead.
	$config = wpshortlist_get_config();

	foreach ( $config as $filter_set ) {
		if ( $filter_set['taxonomy'] === $params['tax']
				&& $filter_set['term'] === $params['term'] ) {
			return $filter_set;
		}
	}

	return false;
}

/**
 * Return the filter set for the current page.
 */
function wpshortlist_get_current_filter_set() {
	$params = array(
		'type' => 'tax_archive',
		'tax'  => get_query_var( 'taxonomy' ),
		'term' => get_query_var( 'term' ),
	);

	return wpshortlist_get_filter_set( $params );
}

/**
 * Create our filter configuration.
 *
 * Tools have Features. Features have unique Methods and Supports. Define them here.
 */
function wpshortlist_set_config() {

	$filter_sets = array();

	// phpcs:ignore
	/*
	Loop:
	foreach ( $config as $filter_set ) {
		foreach ( $filter_set['filters'] as $filter ) {
			foreach ( $filter['options'] as $option_id => $option_name ) {
			}
		}
	}
	*/

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
