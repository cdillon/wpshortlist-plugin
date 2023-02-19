<?php
/**
 * Plugin Functions
 *
 * @package wpshortlist
 */

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
 * Find a taxonomy by its query_var.
 *
 * @param string $qv The query var.
 */
function wpshortlist_get_tax_by_query_var( $qv ) {
	if ( ! $qv ) {
		return false;
	}

	$tax = get_taxonomies( array( 'query_var' => $qv ) );

	return current( array_keys( $tax ) );
}

/**
 * Find a tax query_var.
 *
 * @param string $t The taxonomy name.
 */
function wpshortlist_get_tax_query_var( $t ) {
	if ( ! $t ) {
		return false;
	}

	$tax = get_taxonomy( $t );
	if ( $tax ) {
		return $tax->query_var;
	}

	return false;
}
