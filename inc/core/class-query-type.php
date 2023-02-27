<?php
/**
 * Query Type
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
 * Class Query_Type
 *
 * @since 1.0.0
 */
class Query_Type {

	/**
	 * Return the current query type and values, available after parse_query hook.
	 *
	 * Using $wp_query instead of get_queried_object() because this is called
	 * on `pre_get_posts`.
	 *
	 * @return array
	 */
	public function get_current_query_type() {
		global $wp_query;

		if ( $wp_query->is_post_type_archive() ) {
			return array(
				'type' => 'post_type_archive',
				'name' => $wp_query->query['post_type'],
			);
		}

		/*
		 * Feature archive:
		 * Array (
		 *   [feature] => display-term-list
		 * )
		 *
		 * With multiple filters:
		 * Array (
		 *   [tool-type] => plugin
		 *   [feature] => display-term-list
		 *   [method-display-term-list] => widget
		 * )
		 *
		 * The problem: We need to identify the primary taxonomy.
		 *
		 * Only the last taxonomy is stored in queried_object (I think)
		 * which may not be the primary taxonomy.
		 *
		 * So we need to compare a list of our primary taxonomies to the query
		 * vars. There should be only one primary taxonomy since we are using
		 * archive pages as starting points.
		 */
		if ( $wp_query->is_tax() ) {
			// @todo Find a way to store all the info we need in one place: our taxos and their query vars.
			$taxonomies = get_option( 'wpshortlist_taxonomies', array() );
			foreach ( $taxonomies as $tax_name ) {
				$tqv = $this->get_tax_query_var( $tax_name );
				if ( $tqv && isset( $wp_query->query[ $tqv ] ) ) {
					return array(
						'type' => 'tax_archive',
						'tax'  => $tax_name,
						'term' => $wp_query->query[ $tqv ],
					);
				}
			}
		}

		return false;
	}

	/**
	 * Return the query_var for a taxonomy.
	 *
	 * @param string $tax_name A taxonomy name.
	 */
	private function get_tax_query_var( $tax_name ) {
		if ( ! $tax_name ) {
			return false;
		}

		$taxonomy = get_taxonomy( $tax_name );
		if ( $taxonomy ) {
			return $taxonomy->query_var;
		}

		return false;
	}

}
