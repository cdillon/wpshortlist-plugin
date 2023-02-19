<?php
/**
 * Filter Functions
 *
 * @package wpshortlist
 */

/**
 * Return the current query type and values.
 *
 * Using $wp_query instead of get_queried_object() because this is called
 * on `pre_get_posts` before queried_object is reliable.
 *
 * @return array
 */
function wpshortlist_get_current_query_type() {
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
		$tax_names = wpshortlist_get_filter_sets_property( 'taxonomy' );
		foreach ( $tax_names as $tax ) {
			$tqv = wpshortlist_get_tax_query_var( $tax );
			if ( $tqv && isset( $wp_query->query[ $tqv ] ) ) {
				return array(
					'type' => 'tax_archive',
					'tax'  => $tax,
					'term' => $wp_query->query[ $tqv ],
				);
			}
		}
	}

	return false;
}

/**
 * Return the filter set for the current page.
 */
function wpshortlist_get_current_filter_set() {
	$params = wpshortlist_get_current_query_type();
	// phpcs:ignore
	// q2($params,'CURRENT QUERY TYPE');
	return wpshortlist_get_filter_sets( $params );
}

/**
 * Return the filter set for a specific taxonomy and term.
 * Return false if not found.
 *
 * @param array $params  Parameters.
 *
 * @return array|false
 */
function wpshortlist_get_filter_sets( $params ) {
	if ( ! $params ) {
		return false;
	}

	$filter_sets = get_option( 'wpshortlist_filter_sets' );
	if ( ! $filter_sets ) {
		return false;
	}

	$active_filters = array();

	// phpcs:ignore
	/*
	Build an array of current page conditions like:
	Array
	(
		[0] => tax_archive
		[1] => tax_archive:feature
		[2] => tax_archive:feature:display-term-list
	)
	*/
	$conditions     = array();
	$last_condition = '';
	foreach ( $params as $param ) {
		$condition      = ( $last_condition ? $last_condition . ':' : '' ) . $param;
		$conditions[]   = $condition;
		$last_condition = $condition;
	}

	// Match filter set rules against the current page conditions.
	$has_rules = wpshortlist_get_filter_sets_with( $filter_sets, 'rules' );
	foreach ( $has_rules as $filter_set ) {
		if ( array_intersect( $conditions, (array) $filter_set['rules'] ) ) {
			$active_filters[ $filter_set['order'] ] = $filter_set;
		}
	}
	ksort( $active_filters );

	// phpcs:ignore
	// q2( $active_filters, 'ACTIVE FILTERS' );
	return $active_filters;
}

/**
 * Load filter sets. Called from plugin activation function.
 */
function wpshortlist_load_filter_sets() {
	require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';

	$filter_sets = array();

	$wpfsd = new WP_Filesystem_Direct( false );
	$files = array_filter(
		array_keys( $wpfsd->dirlist( WPSHORTLIST_DATA_DIR, false ) ),
		function( $f ) {
			return 'json' === pathinfo( $f, PATHINFO_EXTENSION );
		}
	);

	foreach ( $files as $file ) {
		$json = $wpfsd->get_contents( WPSHORTLIST_DATA_DIR . $file );

		if ( $json ) {
			// Save new filter set.
			$filter_set = json_decode( $json, true );
			if ( is_null( $filter_set ) ) {
				q2( $file, 'Error: invalid JSON' );
			} else {
				$filter_sets[] = $filter_set;
			}
		}
	}

	q2( $filter_sets, '', 'o', 'filter-sets.log' );
	update_option( 'wpshortlist_filter_sets', $filter_sets );
}

/**
 * Return filter sets that have a specific element.
 *
 * @param array  $filter_sets  Filter sets.
 * @param string $criterion    The element to check for.
 *                             For example, 'rules' or 'filters'.
 *
 * @return array
 */
function wpshortlist_get_filter_sets_with( $filter_sets, $criterion ) {
	if ( ! $filter_sets || ! is_array( $filter_sets ) || ! $criterion ) {
		return $filter_sets;
	}

	return array_filter(
		$filter_sets,
		function( $f ) use ( $criterion ) {
			return ( isset( $f[ $criterion ] ) && $f[ $criterion ] );
		}
	);
}

/**
 * Return filter sets with filters that have a specific query var.
 * Used by Ajax handler.
 *
 * @param string $query_var  A query var.
 *
 * @return array|bool
 */
function wpshortlist_get_filter_by_query_var( $query_var ) {
	if ( ! $query_var ) {
		return false;
	}

	$filter_sets = get_option( 'wpshortlist_filter_sets' );
	$has_filters = wpshortlist_get_filter_sets_with( $filter_sets, 'filters' );
	foreach ( $has_filters as $filter_set ) {
		foreach ( $filter_set['filters'] as $filter ) {
			if ( $query_var === $filter['query_var'] ) {
				return $filter;
			}
		}
	}

	return false;
}

/**
 * Return the starting page for the active filter sets.
 * Also serves as indicator that the current page has filter sets.
 *
 * @todo How to make intelligent guess instead of hard-coding in config?
 *
 * @return string
 */
function wpshortlist_get_start() {
	$filter_sets = wpshortlist_get_current_filter_set();
	if ( ! $filter_sets ) {
		return false;
	}

	// Progressive assignment: The last 'start' wins.
	$start = '';
	foreach ( $filter_sets as $filter_set ) {
		if ( isset( $filter_set['start'] ) && $filter_set['start'] ) {
			$start = $filter_set['start'];
		}
	}

	return $start;
}

/**
 * Return a list of filter properties.
 *
 * @param string $prop  The property.
 *
 * @return array|bool
 */
function wpshortlist_get_filter_sets_property( $prop ) {
	if ( ! $prop ) {
		return false;
	}

	$filter_sets = get_option( 'wpshortlist_filter_sets' );
	if ( ! $filter_sets ) {
		return false;
	}

	$props = array();
	foreach ( $filter_sets as $filter_set ) {
		if ( isset( $filter_set[ $prop ] ) && $filter_set[ $prop ] ) {
			$props[] = $filter_set[ $prop ];
		}
	}

	return array_unique( $props );
}
