<?php
/**
 * Theme Integration
 *
 * @since 1.0.0
 *
 * @package wpshortlist
 */

namespace Shortlist\Core;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Theme_Integration
 *
 * @since 1.0.0
 */
class Theme_Integration {

	/**
	 * Init
	 */
	public function init() {
		add_filter( 'get_the_archive_title', array( $this, 'archive_title' ), 20, 3 );
	}

	/**
	 * Modify the archive title.
	 *
	 * @param string $title The title.
	 * @param string $original_title The original title.
	 * @param string $prefix The prefix.
	 *
	 * @return string
	 */
	public function archive_title( $title, $original_title, $prefix ) {
		// Find the primary tax.
		$query_type    = new Query_Type();
		$current_query = $query_type->get_current_query_type();

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

}
