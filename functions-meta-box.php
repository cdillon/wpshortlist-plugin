<?php

/**
 * Add meta boxes for feature-specific post meta.
 */
function wpshortlist_meta_boxes( $meta_boxes ) {

	if ( ! function_exists( 'wpshortlist_get_config' ) ) {
		return $meta_boxes;
	}

	$config = wpshortlist_get_config();

	foreach ( $config as $filter_set ) {
		$meta_box = [];
		$fields   = [];

		$meta_box['id']         = $filter_set['term'];
		$meta_box['title']      = $filter_set['taxonomy_title'] . ': ' . $filter_set['name'];
		$meta_box['post_types'] = $filter_set['post_types'];

		foreach ( $filter_set['filters'] as $filter ) {
			$fields[] = [
				'name'    => $filter['name'],
				'id'      => $filter['query_var'],
				'type'    => $filter['meta_box_type'],
				'options' => $filter['options'],
			];
		}
		
		$meta_box['fields'] = $fields;
		
		$meta_boxes[] = $meta_box;
	}

	/* --- SAVE AS REFERENCE --- */
	/*
	// Display Term List
	$meta_boxes[] = [
		'id'         => 'display-term-list',  // same as $term
		'title'      => 'Feature: Display Term List',  // similar to filter_set[name], can be assembled
		'post_types' => 'tool',
		'fields'     => [
			[
				'name' => 'Method',  // same as filter[name]
				'id'   => 'method-display-term-list',  // same a filter[query_var]
				'type' => 'checkbox_list',  // unique
				'options' => [  // same as filter[options]
					'block'     => 'block',
					'widget'    => 'widget',
					'shortcode' => 'shortcode',
				],
			],
			[
				'name' => 'Supports',
				'id'   => 'supports-display-term-list',
				'type' => 'checkbox_list',
				'options' => [
					'categories'        => 'categories',
					'tags'              => 'tags',
					'custom-taxonomies' => 'custom taxonomies',
				],
			],
		],
	];

	// Display Terms Current Post
	$meta_boxes[] = [
		'id'         => 'display-terms-current-post',	
		'title'      => 'Feature: Display Terms Current Post',
		'post_types' => 'tool',
		'fields'     => [
			[
				'name' => 'Method',
				'id'   => 'method-display-terms-current-post',
				'type' => 'checkbox_list',
				'options' => [
					'automatic' => 'automatic (optional)',
					'block'     => 'block',
					'widget'    => 'widget',
					'shortcode' => 'shortcode',
				],
			],
			[
				'name' => 'Supports',
				'id'   => 'supports-display-terms-current-post',
				'type' => 'checkbox_list',
				'options' => [
					// 'posts'             => 'posts',
					// 'pages'             => 'pages',
					// 'custom post types' => 'custom post types',
					'categories'        => 'categories',
					'tags'              => 'tags',
					'custom-taxonomies' => 'custom taxonomies',
				],
			],
		],
	];
	*/

	return $meta_boxes;
}

add_filter( 'rwmb_meta_boxes', 'wpshortlist_meta_boxes' );

/**
 * 
 */
function wpshortlist_rwmb_begin_html( $begin, $field, $value ) {
	q2($begin);
	q2($field);
	return $begin;
}

// add_filter( 'rwmb_begin_html', 'wpshortlist_rwmb_begin_html', 10, 3 );

/**
 * Only print meta box if the Tool has the Feature.
 */
function wpshortlist_rwmb_show( $show, $meta_box ) {
	if ( is_admin() && isset( $_GET['post'] ) ) {	
		if ( ! has_term( $meta_box['id'], 'wp_feature', $_GET['post'] ) ) {
			return false;
		}
	}

	return $show;
}

add_filter( 'rwmb_show', 'wpshortlist_rwmb_show', 10, 2 );
