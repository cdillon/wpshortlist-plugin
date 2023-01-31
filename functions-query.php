<?php
/**
 * Query Functions
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
