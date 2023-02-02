<?php
/**
 * Filter Configuration
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
 * Create our filter configuration.
 * 
 * Tools have Features. Features have unique Methods and Supports. Define them here.
 */
function wpshortlist_set_config() {

	$filter_sets = [];
	/*
	// Loop:
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

	$filter_set_base = [
		'name'           => '',
		'term'           => '',
		'taxonomy'       => $tax_name,
		'taxonomy_title' => $taxonomy_title,
		'post_types'     => [ 'tool' ],
		'filters'        => [],
	];

	/*
	 * Display Term List
	 */
	$term = 'display-term-list';  // must match term slug

	$filter_sets[] = array_merge( $filter_set_base, [
		'name'    => __( 'Display Term List', 'wpshortlist' ),
		'term'    => $term,
		'filters' => [
			[
				// Methods
				'name'          => __( 'Methods', 'wpshortlist' ),
				'id'            => 'method',
				'query_var'     => 'method' . '-' . $term,
				'meta_box_type' => 'checkbox_list',
				
				'options'       => [
					'block'     => 'block',
					'widget'    => 'widget',
					'shortcode' => 'shortcode',
				],
			],
			[
				// Supports
				'name'          => __( 'Supports', 'wpshortlist' ),
				'id'            => 'supports',
				'query_var'     => 'supports' . '-' . $term,
				'meta_box_type' => 'checkbox_list',
				
				'options'       => [
					'categories'        => 'categories', 
					'tags'              => 'tags', 
					'custom-taxonomies' => 'custom taxonomies',
				],
			],
		]
	] );
		
	/*
	 * Display Terms Current Post
	 */
	$term = 'display-terms-current-post';
	
	$filter_sets[] = array_merge( $filter_set_base, [
		'name'    => __( 'Display Terms Current Post', 'wpshortlist' ),
		'term'    => $term,
		'filters' => [
			[ 
				// Methods
				'name'          => __( 'Methods', 'wpshortlist' ),
				'id'            => 'method',
				'query_var'     => 'method' . '-' . $term,
				'meta_box_type' => 'checkbox_list',
				
				'options'       => [
					'automatic' => 'automatic',
					'block'     => 'block',
					'widget'    => 'widget',
					'shortcode' => 'shortcode',
				]
			],
			[
				// Supports
				'name'          => __( 'Supports', 'wpshortlist' ),
				'id'            => 'supports',
				'query_var'     => 'supports' . '-' . $term,
				'meta_box_type' => 'checkbox_list',
				
				'options'       => [
					'categories'        => 'categories',
					'tags'              => 'tags',
					'custom-taxonomies' => 'custom taxonomies',
				],
			],
		]
	] );

	q2($filter_sets,'','o','filter-sets.log');
	update_option( 'wpshortlist_filters', $filter_sets );
}

add_action( 'init', 'wpshortlist_set_config' );
