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
 * @todo Make this more robust.
 *
 * @return boolean
 */
function wpshortlist_ok_modify() {
	return ( is_post_type_archive( array( 'tool' ) ) || is_tax( array( 'wp_feature' ) ) );
}

/**
 * Add filters to meta query.
 *
 * @param object $query  WP_Query.
 *
 * @todo Handle CPT and other CT.
 */
function wpshortlist_alter_query( $query ) {
	if ( ! wpshortlist_ok_modify() ) {
		return;
	}
	if ( ! in_array( 'feature', array_keys( $query->query ), true ) ) {
		return;
	}

	$meta_query = array();
	// phpcs:ignore
	// $meta_query['relation'] = 'AND';   // 'OR' or 'AND' (default)

	$config = wpshortlist_get_config();

	foreach ( $config as $filter_set ) {
		foreach ( $filter_set['filters'] as $filter ) {

			if ( isset( $query->query[ $filter['query_var'] ] ) ) {
				$meta_query[] = array(
					'key'     => $filter['query_var'],
					'value'   => explode( '|', $query->query[ $filter['query_var'] ] ),
					'compare' => 'IN',
				);

			}
		}
	}

	$query->set( 'meta_query', $meta_query );
}

add_action( 'pre_get_posts', 'wpshortlist_alter_query' );
