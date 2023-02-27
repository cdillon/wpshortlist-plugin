<?php
/**
 * Common functions not suitable for a class. Namespaced to prevent conflict.
 *
 * @package wpshortlist
 */

namespace Shortlist\Core;

/**
 * Return the current query type and values, available after parse_query hook.
 *
 * This is like a custom `get_queried_object` function.
 *
 * Using $wp_query functions because this is called on `pre_get_posts`.
 *
 * @todo Can this info be stored in $wp_query after parse_request so we don't have to assemble it every time?
 *
 * @return array
 */
function get_current_query_type() {
	global $wp_query;

	if ( $wp_query->is_post_type_archive() ) {
		return array(
			'type' => 'post_type_archive',
			'name' => $wp_query->query['post_type'],
		);
	}

	/*
	 * The problem: We need to identify the primary taxonomy.
	 *
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
	 * Only the last taxonomy is stored in queried_object which may not be
	 * the primary taxonomy.
	 *
	 * So we need to compare a list of our primary taxonomies to the query
	 * vars. There should be only one primary taxonomy since we are using
	 * archive pages as starting points.
	 */
	if ( $wp_query->is_tax() ) {
		// @todo Find a way to store all the info we need in one place: our taxos and their query vars.
		$taxonomies = get_option( 'wpshortlist_taxonomies', array() );
		foreach ( $taxonomies as $tax_name ) {
			$tqv = get_tax_query_var( $tax_name );
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
 * Return the query var of a taxonomy.
 *
 * @param string $tax_name The taxonomy name.
 */
function get_tax_query_var( $tax_name ) {
	if ( ! $tax_name ) {
		return false;
	}

	$taxonomy = get_taxonomy( $tax_name );
	if ( $taxonomy ) {
		return $taxonomy->query_var;
	}

	return false;
}
