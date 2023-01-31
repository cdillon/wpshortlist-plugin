<?php
/**
 * wp-content\plugins\filter-everything\src\Admin\Widgets\FiltersWidget.php
 */
function wpshortlist_related_filters( $filters ) {
	$qo = get_queried_object();
	q2($qo,__FUNCTION__,'o','queried_object.log');
	
	if ( is_post_type_archive( 'tool' ) ) {
		// return $filters;
	}

	$new_filters = $filters;
	
	foreach ( $filters as $filter_id => $filter ) {
		// q2($filter,'filter','','filter-everything.log');


		/*
		 * $filter['entity'] = "Filter by" value (taxonomy or custom field)
		 * for taxonomies, $filter['e_name'] = taxonomy name
		 * for custom fields, $filter['e_name'] = meta key name 
		 */

		/*
		 * -----------------
		 * Feature Directory 
		 * -----------------
		 * 
		 * A _taxonomy_ archive for `wp_feature`.
		 * 
		 * Only show feature-specific (dependent) filters.
		 */
		if ( is_a( $qo, 'WP_Term' ) && 'wp_feature' == $qo->taxonomy ) {

			/*
			 * 1st check: feature match
			 * exclude feature filter because feature has already been selected
			 */
			if ( 'taxonomy' == $filter['entity'] ) {
				if ( $filter['e_name'] == $qo->taxonomy ) {
					unset( $new_filters[ $filter_id ] );
				}
			}

			/*
			 * 2nd check: post meta match
			 * exclude postmeta filters that are NOT feature-specific (dependent)
			 */
			if ( 'post_meta' == $filter['entity'] ) {
				
				$postmeta_keys = [ 
					'method-' . $qo->slug, 
					'supports-' . $qo->slug,
				];

				if ( ! in_array( $filter['slug'], $postmeta_keys ) ) {
					unset( $new_filters[ $filter_id ] );
				}

			}

		}

		/*
		 * --------------
		 * Tool Directory
		 * --------------
		 * 
		 * A _post_type_ archive for `tool`.
		 * 
		 * Only show feature filter.
		 */
		if ( is_a( $qo, 'WP_Post_Type' ) && 'tool' == $qo->name ) {

			/*
			 * 1st check: post meta match
			 * exclude ALL post meta filters that are NOT feature-specific (dependent) --- FOR NOW
			 */
			if ( 'post_meta' == $filter['entity'] ) {

				$postmeta_keys = [ 
					'method-', 
					'supports-',
				];

				foreach ( $postmeta_keys as $postmeta_key ) {
					if ( 0 === strpos( $filter['slug'], $postmeta_key ) ) {
						unset( $new_filters[ $filter_id ] );
					}
				}

			}

		}

	}

	return $new_filters;
}

// add_filter( 'filter_everything_related_filters', 'wpshortlist_related_filters' );

/**
 * 
 */
function wpshortlist_filter_everything_sort_terms( $terms, $sortby, $entity_name ) {
	if ( 'custom' != $sortby ) {
		return $terms;
	}

	$method     = [ 'automatic', 'block', 'widget', 'shortcode' ];
	$supports   = [ 'categories', 'tags', 'custom-taxonomies' ];
	$all_orders = [ 
		'method-display-term-list'            => $method,
		'method-display-terms-current-post'   => $method,
		'supports-display-term-list'          => $supports,
		'supports-display-terms-current-post' => $supports,
	];

	if ( ! in_array( $entity_name, array_keys( $all_orders ) ) ) {
		return $terms;
	}

	$found     = [];
	$not_found = [];

	foreach ( $all_orders[ $entity_name ] as $order ) {
		foreach ( $terms as $term ) {
			if ( $order == $term->slug ) {
				$found[] = $term;
			} else {
				$not_found[] = $term;
			}
		}
	}
	
	// The found come first.
	return array_merge( $found, $not_found );
}

// add_filter( 'filter_everything_sort_terms', 'wpshortlist_filter_everything_sort_terms', 10, 3 );
