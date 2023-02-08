<?php
/**
 * Query Functions
 *
 * @package wpshortlist
 */

/**
 * Register our query vars.
 *
 * @param array $vars  Query vars.
 *
 * @return array
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
 * Sort our post types and taxonomies alphabetically everywhere.
 *
 * @param object $query  WP_Query.
 */
function wpshortlist_change_sort_order( $query ) {
	// Both admin and front end, regardless if main query.
	$post_types = array( 'tool', 'category_proxy', 'feature_proxy' );
	$taxonomies = array( 'wp_feature', 'wp_category', 'tool_type' );

	if ( is_post_type_archive( $post_types ) || is_tax( $taxonomies ) ) {
		$query->set( 'orderby', 'title' );
		$query->set( 'order', 'ASC' );
	}
}

add_action( 'pre_get_posts', 'wpshortlist_change_sort_order' );

/**
 * If it's OK to modify or debug stuff like the main query.
 *
 * @param WP_Query $query  A WP_Query object.
 *
 * @return boolean
 */
function wpshortlist_ok_modify( $query ) {

	if ( ! is_a( $query, 'WP_Query' ) ) {
		return false;
	}

	if ( is_admin() && ! is_main_query() ) {
		return false;
	}

	if ( isset( $query->query['favicon'] ) ) {
		return false;
	}

	if ( isset( $query->query['post_type'] ) ) {
		return 'tool' === $query->query['post_type'];
	}

	// All that just to narrow it down to our Tool/Feature query. Why?

	// Assemble a list of our public custom taxonomies.
	$tax_query_vars = array();
	$taxonomies     = get_object_taxonomies( 'tool', 'objects' );
	foreach ( $taxonomies as $tax_object ) {
		if ( $tax_object->public ) {
			if ( is_bool( $tax_object->query_var ) ) {
				$tax_query_vars[] = $tax_object->name;
			} else {
				$tax_query_vars[] = $tax_object->query_var;
			}
		}
	}

	// Is queried taxonomy one of our custom taxonomies?
	// This is necessary because the post type is not present in $wp_query
	// on a taxonomy archive. Why?
	if ( array_intersect( $tax_query_vars, array_keys( $query->query ) ) ) {
		return true;
	}

	return false;
}

/**
 * Add filters to meta query.
 *
 * @param object $query  WP_Query.
 *
 * @todo Handle CPT and other CT.
 */
function wpshortlist_alter_query( $query ) {
	if ( ! wpshortlist_ok_modify( $query ) ) {
		return;
	}

	$meta_query = array();
	// phpcs:ignore
	// $meta_query['relation'] = 'AND';   // 'OR' or 'AND' (default)

	$config = wpshortlist_get_config();

	foreach ( $config as $filter_set ) {
		foreach ( $filter_set['filters'] as $filter ) {

			if ( isset( $query->query[ $filter['query_var'] ] ) ) {

				$q_values = explode( '|', $query->query[ $filter['query_var'] ] );

				if ( 'AND' === $filter['relation'] ) {
					// Add a meta query for each option value.
					foreach ( $q_values as $q_value ) {
						$meta_query[] = array(
							'key'     => $filter['query_var'],
							'value'   => $q_value,
							'compare' => '=',
						);
					}
				} else {
					// Add a single query with an array of option values.
					$meta_query[] = array(
						'key'     => $filter['query_var'],
						'value'   => $q_values,
						'compare' => 'IN',
					);
				}
			}
		}
	}

	// phpcs:ignore
	// q2( $meta_query, 'NEW META QUERY', '', 'meta-query.log' );
	$query->set( 'meta_query', $meta_query );
}

add_action( 'pre_get_posts', 'wpshortlist_alter_query' );