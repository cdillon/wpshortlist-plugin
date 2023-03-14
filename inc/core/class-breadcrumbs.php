<?php
/**
 * Breadcrumbs
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
 * Class Breadcrumbs
 *
 * @since 1.0.0
 */
class Breadcrumbs {

	/**
	 * Init
	 */
	public function init() {
		add_action( 'kadence_before_main_content', array( $this, 'print_breadcrumbs' ) );
	}

	/**
	 * Print
	 */
	public function print_breadcrumbs() {
		global $wp_query;
		$q = $wp_query->query;

		// @todo Replace this; check for our CPTs/CTs.
		if ( is_home() || is_front_page() || is_singular( array( 'post', 'page' ) ) ) {
			return;
		}

		// @todo Pre-build a list of potential referrers and check against that.
		$server    = map_deep( wp_unslash( (array) $_SERVER ), 'sanitize_url' );
		$referer   = isset( $server['HTTP_REFERER'] ) ? $server['HTTP_REFERER'] : '';
		$ref_by    = false;
		$ref_parts = wp_parse_url( $referer );
		// phpcs:ignore
		/*
		Feature Directory:
			Array
			(
				[scheme] => https
				[host] => wpshortlist.test
				[path] => /features/display-term-list/
				[query] => fs=&supports-display-term-list[]=categories&supports-display-term-list[]=tags
			)

		Tool Directory:
			Array
			(
				[scheme] => https
				[host] => wpshortlist.test
				[path] => /tools/
			)

		Tool Directory with category filter:
			Array
			(
				[scheme] => https
				[host] => wpshortlist.test
				[path] => /tool-type/core-block/
			)

		Article:
			Array
			(
				[scheme] => https
				[host] => wpshortlist.test
				[path] => /the-core-blocks/
			)
 		*/

		if ( isset( $ref_parts['scheme'] ) ) {
			if ( home_url() === $ref_parts['scheme'] . '://' . $ref_parts['host'] ) {
				// Could be directory or article.
				$path_parts = explode( '/', trim( $ref_parts['path'], '/' ) );
				if ( in_array( $path_parts[0], array( 'features', 'tools', 'tool-type' ), true ) ) {
					$ref_by = 'directory';
				} else {
					$ref_by = 'article';
				}
			}
		}

		echo '<div class="wpshortlist-breadcrumbs">';
		echo '<div class="wpshortlist-breadcrumbs-container">';

		$this->print_breadcrumb( 'home' );

		// If feature selected.
		if ( isset( $q['feature'] ) ) {
			$this->print_sep();
			$this->print_breadcrumb( 'feature_directory' );
			$this->print_sep();
			$this->print_breadcrumb( 'feature' );
		}

		// If feature category selected.
		if ( isset( $q['feature-category'] ) ) {
			$this->print_sep();
			$this->print_breadcrumb( 'feature_directory' );
			$this->print_sep();
			$this->print_breadcrumb( 'feature_category' );
		}

		// If tool type selected.
		if ( isset( $q['post_type'] ) && isset( $q['tool-type'] ) ) {
			$this->print_sep();
			$this->print_breadcrumb( 'tool_directory' );
			$this->print_sep();
			$this->print_breadcrumb( 'tool_type' );
		}

		// If tool.
		if ( is_singular( 'tool' ) && $ref_by ) {
			$this->print_divider();
			$this->print_referer( $referer, $ref_by );
		}

		echo '</div>';
		echo '</div>';
	}

	/**
	 * Print breadcrumb
	 *
	 * @param string $bc The breadcrumb type.
	 *
	 * @todo How to store these configurations somewhere?
	 */
	public function print_breadcrumb( $bc ) {
		switch ( $bc ) {
			case 'home':
				printf( '<span><a class="wpshortlist-breadcrumb" href="%s">%s</a></span>', esc_url( home_url() ), esc_html__( 'Home', 'wpshortlist' ) );
				break;
			case 'tool_directory':
				$post_type_object = get_post_type_object( 'tool' );
				$labels           = get_post_type_labels( $post_type_object );
				$url              = get_post_type_archive_link( 'tool' );
				$text             = $labels->archive_title;
				printf( '<span><a class="wpshortlist-breadcrumb" href="%s">%s</a></span>', esc_url( $url ), esc_html( $text ) );
				break;
			case 'tool_type':
				$tax    = get_taxonomy( 'tool_type' );
				$labels = get_taxonomy_labels( $tax );
				if ( isset( $labels->breadcrumb ) ) {
					$text = $labels->breadcrumb;
				} else {
					$text = $labels->singular_name;
				}
				echo '<span>' . esc_html( $text ) . '</span>';
				break;
			case 'feature_directory':
				// @todo also used by form reset, write a common function
				$post_type_object = get_post_type_object( 'feature_proxy' );
				$labels           = get_post_type_labels( $post_type_object );
				$url              = get_post_type_archive_link( 'feature_proxy' );
				$text             = $labels->archive_title;
				printf( '<span><a class="wpshortlist-breadcrumb" href="%s">%s</a></span>', esc_url( $url ), esc_html( $text ) );
				break;
			case 'feature_category':
				// No proxy, for now.
				$tax    = get_taxonomy( 'feature_category' );
				$labels = get_taxonomy_labels( $tax );
				if ( isset( $labels->breadcrumb ) ) {
					$text = $labels->breadcrumb;
				} else {
					$text = $labels->singular_name;
				}
				echo '<span>' . esc_html( $text ) . '</span>';
				break;
			case 'feature':
				$tax    = get_taxonomy( 'feature' );
				$labels = get_taxonomy_labels( $tax );
				if ( isset( $labels->breadcrumb ) ) {
					$text = $labels->breadcrumb;
				} else {
					$text = $labels->singular_name;
				}
				echo '<span>' . esc_html( $text ) . '</span>';
				break;
			default:
		}
	}

	/**
	 * Print separator
	 */
	public function print_sep() {
		echo '<span class="wpshortlist-breadcrumb-sep"> &raquo; </span>';
	}

	/**
	 * Print divider
	 */
	public function print_divider() {
		echo '<span class="wpshortlist-breadcrumb-sep"> | </span>';
	}

	/**
	 * Print referer
	 *
	 * @param string $referer The referrer URL.
	 * @param string $ref_by The type of referrer.
	 */
	public function print_referer( $referer, $ref_by ) {
		switch ( $ref_by ) {
			case 'directory':
				$ref = __( 'Back to list', 'wpshortlist' );
				break;
			case 'article':
				$ref = __( 'Back to article', 'wpshortlist' );
				break;
			default:
				$ref = __( 'Back', 'wpshortlist' );
		}
		printf( '<span><a class="wpshortlist-breadcrumb" href="%s">%s</a></span>', esc_url( $referer ), esc_html( $ref ) );
	}

}
