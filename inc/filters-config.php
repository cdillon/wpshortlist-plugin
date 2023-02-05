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
 * @param string $the_tax   A taxonomy.
 * @param string $the_term  A term.
 *
 * @return array|false
 */
function wpshortlist_get_filter_set( $the_tax = '', $the_term = '' ) {
	$the_tax  = $the_tax ? $the_tax : get_query_var( 'taxonomy' );
	$the_term = $the_term ? $the_term : get_query_var( 'term' );

	$config = wpshortlist_get_config();

	foreach ( $config as $filter_set ) {
		if ( $filter_set['taxonomy'] === $the_tax && $filter_set['term'] === $the_term ) {
			return $filter_set;
		}
	}

	return false;
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
					'name'          => __( 'Methods', 'wpshortlist' ),
					'id'            => 'method',
					'query_var'     => "method-{$term}",
					'meta_box_type' => 'checkbox_list',
					'input_type'    => 'radio',

					'options'       => array(
						'block'     => 'block',
						'widget'    => 'widget',
						'shortcode' => 'shortcode',
					),
				),
				array(
					// Supports.
					'name'          => __( 'Supports', 'wpshortlist' ),
					'id'            => 'supports',
					'query_var'     => "supports-{$term}",
					'meta_box_type' => 'checkbox_list',
					'input_type'    => 'checkbox',

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
					'name'          => __( 'Methods', 'wpshortlist' ),
					'id'            => 'method',
					'query_var'     => "method-{$term}",
					'meta_box_type' => 'checkbox_list',
					'input_type'    => 'radio',

					'options'       => array(
						'automatic' => 'automatic',
						'block'     => 'block',
						'widget'    => 'widget',
						'shortcode' => 'shortcode',
					),
				),
				array(
					// Supports.
					'name'          => __( 'Supports', 'wpshortlist' ),
					'id'            => 'supports',
					'query_var'     => "supports-{$term}",
					'meta_box_type' => 'checkbox_list',
					'input_type'    => 'checkbox',

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
