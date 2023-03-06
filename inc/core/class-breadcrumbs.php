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
		if ( is_home() || is_front_page() || is_singular( array( 'post', 'page' ) ) ) {
			return;
		}

		$server  = map_deep( wp_unslash( (array) $_SERVER ), 'sanitize_text_field' );
		$referer = $server['HTTP_REFERER'];

		echo '<div class="wpshortlist-breadcrumbs">';
		echo '<div class="wpshortlist-breadcrumbs-container">';

		$this->print_breadcrumb( 'home' );

		// If feature selected.
		if ( $this->is_primary_archive( 'feature' ) ) {
			$this->print_sep();
			$this->print_breadcrumb( 'feature_directory' );
			$this->print_sep();
			$this->print_breadcrumb( 'feature' );
		}

		// If feature category selected.
		if ( $this->is_primary_archive( 'feature_category' ) ) {
			$this->print_sep();
			$this->print_breadcrumb( 'feature_directory' );
			$this->print_sep();
			$this->print_breadcrumb( 'feature_category' );
		}

		// If tool type selected.
		if ( $this->is_primary_archive( 'tool_type' ) ) {
			$this->print_sep();
			$this->print_breadcrumb( 'tool_directory' );
			$this->print_sep();
			$this->print_breadcrumb( 'tool_type' );
		}

		// If tool.
		if ( is_singular( 'tool' ) ) {
			$this->print_divider();
			$this->print_referer( $referer );
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
				$url    = get_taxonomy_archive_link( 'feature_category' );
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
	 * Is primary archive?
	 *
	 * @param string $tax Taxonomy name.
	 *
	 * @todo move to common function
	 */
	public function is_primary_archive( $tax ) {
		$current_query = get_current_query_type();
		// phpcs:ignore
		/*
		Array
		(
			[type] => tax_archive
			[tax] => feature
			[term] => display-term-list
		)
		*/
		if ( isset( $current_query['type'] ) && 'tax_archive' === $current_query['type'] ) {
			if ( isset( $current_query['tax'] ) && $tax === $current_query['tax'] ) {
				return true;
			}
		}

		return false;
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
	 */
	public function print_referer( $referer ) {
		printf( '<span><a class="wpshortlist-breadcrumb" href="%s">%s</a></span>', esc_url( $referer ), esc_html__( 'Back to list', 'wpshortlist' ) );
	}

}
