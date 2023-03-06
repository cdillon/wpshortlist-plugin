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
	 * @todo How to store these configurations somewhere?
	 *
	 * @return string
	 */
	public function archive_title( $title, $original_title, $prefix ) {
		if ( is_post_type_archive( 'tool' ) ) {

			$obj    = get_post_type_object( 'tool' );
			$labels = get_post_type_labels( $obj );
			if ( isset( $labels->archive_title ) && $labels->archive_title ) {
				$title = $labels->archive_title;
			}

			// Remove default WordPress prefix.
			$prefix = '';

		} elseif ( is_post_type_archive( 'feature_proxy' ) ) {

			$obj    = get_post_type_object( 'feature_proxy' );
			$labels = get_post_type_labels( $obj );
			if ( isset( $labels->archive_title ) && $labels->archive_title ) {
				$title = $labels->archive_title;
			}

			// Remove default WordPress prefix.
			$prefix = '';

		} elseif ( is_tax() ) {

			// Find the primary tax.
			$current_query = get_current_query_type();
			if ( $current_query ) {
				$tax    = get_taxonomy( $current_query['tax'] );
				$term   = get_term_by( 'slug', $current_query['term'], $current_query['tax'] );
				$title  = $term->name;
				$prefix = sprintf(
				/* translators: %s: Taxonomy singular name. */
					_x( '%s:', 'taxonomy term archive title prefix' ),
					$tax->labels->singular_name
				);
				$prefix = '';
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
