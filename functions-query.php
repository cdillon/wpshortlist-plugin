<?php
/**
 * Query Functions
 */

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
 * Sort our post types and taxonomies alphabetically everywhere.
 */
function wpshortlist_change_sort_order( $query ) {
	// both admin and front end
	// regardless if main query
	$post_types = [ 'tool', 'category_proxy', 'feature_proxy' ];
	$taxonomies = [ 'wp_feature', 'wp_category', 'tool_type' ];
	
	if ( is_post_type_archive( $post_types  ) || is_tax( $taxonomies ) ) {
		$query->set( 'orderby', 'title' );
		$query->set( 'order', 'ASC' );
	}
};

add_action( 'pre_get_posts', 'wpshortlist_change_sort_order'); 

/**
 * If it's OK to modify or debug stuff like the main query. 
 * 
 * @todo Make this more robust.
 */
function wpshortlist_ok_modify() {
	return ( is_post_type_archive( ['tool'] ) || is_tax( [ 'wp_feature' ] ) );
}

/**
 * Add filters to meta query.
 */
function wpshortlist_alter_query( $query ) {
	if ( ! wpshortlist_ok_modify() ) {
		return;
	}
	// q2($query->query,'pre_get_posts: query->query');
	
	$meta_query = [];
	// $meta_query['relation'] = 'AND';   // 'OR' or 'AND' (default)

	$config = wpshortlist_get_config();

	foreach ( $config as $filter_set ) {
		foreach ( $filter_set['filters'] as $filter ) {

			if ( isset( $query->query[ $filter['query_var'] ] ) ) {
				$meta_query[] = [
					'key'     => $filter['query_var'],
					'value'   => [ $query->query[ $filter['query_var'] ] ],
					'compare' => 'IN',
				];
					
			}
			
		}
	}

	$query->set( 'meta_query', $meta_query );
	// q2($meta_query,'new meta query');
}

add_action( 'pre_get_posts', 'wpshortlist_alter_query' );
