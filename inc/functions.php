<?php
/**
 * Plugin Functions
 *
 * @package wpshortlist
 */

/**
 * Get all post meta values for a specific key.
 */
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
	// q2($meta_values,'meta_values');

	return $meta_values;
}

/**
 * Use default featured image if Tool has no image.
 *
 * @param int     $thumbnail_id  Thumbnail ID.
 * @param WP_Post $post          A post.
 */
function wpshortlist_post_thumbnail_id( $thumbnail_id, $post ) {
	if ( 0 === $thumbnail_id && 'tool' === $post->post_type ) {
		return 233;
	}

	return $thumbnail_id;
}

add_filter( 'post_thumbnail_id', 'wpshortlist_post_thumbnail_id', 10, 2 );

/**
 * Return the current query type and values.
 *
 * @return array
 */
function wpshortlist_get_current_query_type() {
	$qo = get_queried_object();

	// How to identify proxy archive?

	if ( is_post_type_archive() ) {
		return array(
			'type' => 'post_type_archive',
			'slug' => $qo->name,
		);
	}

	if ( is_category() || is_tag() || is_tax() ) {
		return array(
			'type' => 'tax_archive',
			'tax'  => $qo->taxonomy,
			'slug' => $qo->slug,
		);
	}

	return $qo;
}
