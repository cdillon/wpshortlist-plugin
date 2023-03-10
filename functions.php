<?php
/**
 * Plugin Functions
 */

// helper function to get all post meta values for a specific key
function wpshortlist_get_all_post_meta() {
	global $wpdb;

	$sql = "SELECT DISTINCT `meta_value` FROM `wp_postmeta`
			WHERE `meta_key` = 'method-display-terms-current-post'
			ORDER BY 1 ASC";

	if ( $wpdb->last_error ) {
		return 'wpdb error: ' . $wpdb->last_error;
	}

	$results = $wpdb->get_results( $sql, ARRAY_A );
	
	foreach ( $results as $result ) {
		$meta_values[] = $result['meta_value'];
	}
	q2($meta_values,'meta_values');

	return $meta_values;
}

/**
 * Use default featured image if Tool has no image.
 */
function wpshortlist_post_thumbnail_id( $thumbnail_id, $post ) {
	if ( 0 === $thumbnail_id && 'tool' == $post->post_type ) {
		return 233;
	}

	return $thumbnail_id;
}

add_filter( 'post_thumbnail_id', 'wpshortlist_post_thumbnail_id', 10, 2 );
