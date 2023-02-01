<?php

/**
 * Tools have Features. 
 * Features have unique Methods and Supports. Define them here.
 */
function wpshortlist_get_config() {

	$filter_sets = [];
	/*
	Loop:

	foreach ( $config as $filter_set ) {
		foreach ( $filter_set['filters'] as $filter ) {
			foreach ( $filter['options'] as $option_id => $option_name ) {
			}
		}
	}
	*/
	
	$tax_name   = 'wp_feature';
	$taxonomy   = get_taxonomy( $tax_name );
	$post_types = [ 'tool' ];

	/*
	 * Display Term List
	 */
	$term = 'display-term-list';  // must match term slug

	$filter_sets[] = [
		// should name be pulled from term?
		'name'           => __( 'Display Term List', 'wpshortlist' ),
		'taxonomy'       => $tax_name,
		'taxonomy_title' => $taxonomy->labels->singular_name,
		'term'           => $term,
		'post_types'     => $post_types,
		
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
	];
		
	/*
	 * Display Terms Current Post
	 */
	$term = 'display-terms-current-post';
	
	$filter_sets[] = [
		'name'           => __( 'Display Terms Current Post', 'wpshortlist' ),
		'taxonomy'       => $tax_name,
		'taxonomy_title' => $taxonomy->labels->singular_name,
		'term'           => $term,
		'post_types'     => $post_types,
		
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
	];

	return $filter_sets;
}

/**
 * Register our query vars.
 */
function wpshortlist_register_query_vars( $vars ) {
	$config = wpshortlist_get_config();
	
	foreach ( $config as $filter_set ) {
		foreach ( $filter_set['filters'] as $filter ) {
			$vars[] = $filter['query_var'];
		}
	}
	
	return $vars;
}

add_filter( 'query_vars', 'wpshortlist_register_query_vars' );

/**
 * Print our filters.
 */
function wpshortlist_filters() {

	// get current URL without args (i.e. CPT/CT permalink only)
	$current_url = wpshortlist_get_current_url();
	q2($current_url,__FUNCTION__.': current_url');
	
	// get query args (i.e. our post meta)
	$current_args = wpshortlist_get_current_query_args();
	// remove CPT/CT
	unset( $current_args['feature'] );
	q2($current_args, __FUNCTION__.': current_args');

	/*
	 * Filter sets
	 */
	$config = wpshortlist_get_config();
	
	foreach ( $config as $filter_set ) {

		// Is this filter set for this term?
		if ( is_tax( $filter_set['taxonomy'], $filter_set['term'] ) ) {

			// debug
			// echo '<p><code>' . esc_html( $filter_set['name'] ) . '</code></p>';
			// echo '<p><code>' . $current_args[0] . '</code></p>';

			// Print filter
			foreach ( $filter_set['filters'] as $filter ) {

				echo '<h3>' . esc_html( $filter['name'] ) . '</h3>';
				echo '<ul>';

				// Print options (as links for now)
				foreach ( $filter['options'] as $option_id => $option_name ) {
					
					echo '<li>';
					if ( isset( $current_args[ $filter['query_var'] ] ) && $option_name == $current_args[ $filter['query_var'] ] ) {
							echo '<strong>' . $option_name . '</strong>';
					} else {
						// this appends one field only to current URL (good for radio buttons)
						$url = add_query_arg( $filter['query_var'], $option_id, $current_url );
						printf( '<a class="wpshortlist-filter" href="%s">%s</a>', $url, $option_name );
					}
					echo '</li>';
					
				}

				echo '</ul>';

			}

		}

	}

}

/**
 * 
 */
function wpshortlist_get_current_url() {
	global $wp;
	q2($wp->request,'wp->request');
	return trailingslashit( home_url( add_query_arg( array(), $wp->request ) ) );
}

/**
 * 
 */
function wpshortlist_get_current_query_args() {
	/*
	wp_query->query = Array
	(
		[feature] => display-term-list
		[method-display-term-list] => block
	)
	*/
	global $wp_query;
	return $wp_query->query;
}

/**
 * 
 */
function wpshortlist_alter_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || isset( $query->query['favicon'] ) ) {
		return;
	}
	q2($query->query,'pre_get_posts: query->query');
	
	$meta_query = '';

	$config = wpshortlist_get_config();

	foreach ( $config as $filter_set ) {

		foreach ( $filter_set['filters'] as $filter ) {

			if ( isset( $query->query[ $filter['query_var'] ] ) ) {
				// This overwrites any existing meta query,
				$meta_query = [
					[
						'key'     => $filter['query_var'],
						'value'   => [ $query->query[ $filter['query_var'] ] ],
						'compare' => 'IN',
					]
				];
					
				$query->set( 'meta_query', $meta_query );
			}

		}

	}

	q2($meta_query,'new meta query');
}

add_action( 'pre_get_posts', 'wpshortlist_alter_query' );
