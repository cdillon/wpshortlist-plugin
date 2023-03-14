<?php
/**
 * Common functions not suitable for a class. Namespaced to prevent conflict.
 *
 * @package wpshortlist
 */

namespace Shortlist\Core;

/**
 * Return the current URL.
 */
function get_current_url() {
	global $wp;

	// phpcs:disable
	// This does not work with query string.
	// home_url( $wp->request );
	// https://wpshortlist.test/features/display-term-list

	// This does not apply rewrite rules.
	// add_query_arg( $wp->query_vars, home_url( $wp->request ) ) );
	// https://wpshortlist.test/features/display-term-list?feature=display-term-list&method-display-term-list=block
	//                                   ^                         ^
	// phpcs:enable

	// Not perfect. This does not filter out unregistered query vars.
	$server = map_deep( wp_unslash( (array) $_SERVER ), 'sanitize_text_field' );
	$url    = home_url( $server['REQUEST_URI'] );

	return $url;
}

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
	// phpcs:disable
	/*
	Array
	(
		[tool-type] => core-block
		[feature] => display-terms-current-post  <----- PRIMARY -----
	)

	Array
	(
		[tool-type] => core-block
		[post_type] => tool  <----- PRIMARY -----
	)
	*/
	// phpcs:enable

	// *************************************************************************
	// HARDCODE FIRST
	// *************************************************************************

	if ( isset( $wp_query->query['feature'] ) ) {
		return array(
			'type' => 'tax_archive',
			'tax'  => 'feature',
			'term' => $wp_query->query['feature'],
		);
	}

	if ( isset( $wp_query->query['post_type'] ) && 'feature_proxy' === $wp_query->query['post_type'] ) {
		if ( isset( $wp_query->query['feature-category'] ) ) {
			return array(
				'type' => 'tax_archive',
				'tax'  => 'feature_category',
				'term' => $wp_query->query['feature-category'],
			);
		} else {
			return array(
				'type' => 'post_type_archive',
				'name' => 'feature_proxy',
			);
		}
	}

	if ( isset( $wp_query->query['post_type'] ) && 'tool' === $wp_query->query['post_type'] ) {
		if ( isset( $wp_query->query['tool-type'] ) ) {
			return array(
				'type' => 'tax_archive',
				'tax'  => 'tool_type',
				'term' => $wp_query->query['tool-type'],
			);
		} else {
			return array(
				'type' => 'post_type_archive',
				'name' => 'tool',
			);
		}
	}

	// @todo If single, this should return post type info.
	return false;
}

/**
 * Return the current post's primary label.
 *
 * For tools, its tool type.
 * For features, its target name.
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
