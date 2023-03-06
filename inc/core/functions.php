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
 * ---
 * The problem: We need to identify the primary taxonomy.
 * ---
 *
 * Feature archive:
 * Query = Array (
 *   [feature] => display-term-list   <--- primary
 * )
 *
 * With multiple filters:
 * Query = Array (
 *   [tool-type] => plugin                  <--- secondary
 *   [feature] => display-term-list         <--- primary
 *   [method-display-term-list] => widget   <--- post meta
 * )
 *
 * WordPress is designed for displaying one taxonomy at a time.
 *
 * Only the last taxonomy is stored in queried_object which may not be
 * the primary taxonomy.
 *
 * So we need to compare a list of our primary taxonomies to the query
 * vars. There should be only one primary taxonomy since we are using
 * archive pages as starting points.
 */
function get_current_query_type() {
	global $wp_query;

	// Post type archives are simple.
	if ( $wp_query->is_post_type_archive() ) {
		return array(
			'type' => 'post_type_archive',
			'name' => $wp_query->query['post_type'],
		);
	}

	// Find the primary taxonomy.
	if ( $wp_query->is_tax() ) {
		$taxonomies = get_option( 'wpshortlist_taxonomies', array() );
		foreach ( $taxonomies as $tax_query_var => $tax_name ) {
			if ( isset( $wp_query->query[ $tax_query_var ] ) ) {
				return array(
					'type' => 'tax_archive',
					'tax'  => $tax_name,
					'term' => $wp_query->query[ $tax_query_var ],
				);
			}
		}
	}

	// @todo If single, this should return post type info.
	return false;
}

/**
 * Return the current post's primary label.
 *
 * For tools, that's its tool type.
 * For features, that's its target name.
 */
function get_post_type_primary_label() {
	$label = '';
	$post  = get_post();
	$type  = get_post_type( $post );

	switch ( $type ) {
		case 'tool':
			$terms = wp_get_post_terms( get_the_ID(), 'tool_type' );
			if ( ! is_wp_error( $terms ) ) {
				$label = $terms[0]->name;
			}
			break;
		case 'feature_proxy':
			$label = 'Feature';
			break;
	}

	return $label;
}
