<?php
/**
 * Filter Theme Functions
 *
 * @package wpshortlist
 */

/**
 * Modify the archive title.
 *
 * @param string $title The title.
 * @param string $original_title The original title.
 * @param string $prefix The prefix.
 *
 * @return string
 */
function wpshortlist_archive_title( $title, $original_title, $prefix ) {
	// Find the primary tax.
	$current_query = wpshortlist_get_current_query_type();

	if ( is_post_type_archive() ) {
		$title  = post_type_archive_title( '', false );
		$prefix = '';
	} elseif ( is_tax() ) {
		if ( $current_query ) {
			$tax    = get_taxonomy( $current_query['tax'] );
			$term   = get_term_by( 'slug', $current_query['term'], $current_query['tax'] );
			$title  = $term->name;
			$prefix = sprintf(
				/* translators: %s: Taxonomy singular name. */
				_x( '%s:', 'taxonomy term archive title prefix' ),
				$tax->labels->singular_name
			);
		}
	}

	if ( $prefix ) {
		$title = sprintf(
			/* translators: 1: Title prefix. 2: Title. */
			_x( '%1$s %2$s', 'archive title' ),
			$prefix,
			'<span>' . $title . '</span>'
		);
	}

	return $title;
}

add_filter( 'get_the_archive_title', 'wpshortlist_archive_title', 20, 3 );
