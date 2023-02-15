<?php
/**
 * Filter Functions
 *
 * @package wpshortlist
 */

/**
 * Return the current query type and values.
 *
 * @return array
 */
function wpshortlist_get_current_query_type() {
	$qo = get_queried_object();

	if ( is_post_type_archive() ) {
		return array(
			'type' => 'post_type_archive',
			'name' => $qo->name,
		);
	}

	if ( is_category() || is_tag() || is_tax() ) {
		return array(
			'type' => 'tax_archive',
			'tax'  => $qo->taxonomy,
			'term' => $qo->slug,
		);
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
	return wpshortlist_get_filter_set( $params );
}

/**
 * Return the filter set for a specific taxonomy and term.
 * Return false if not found.
 *
 * @param array $params  Parameters.
 *
 * @return array|false
 */
function wpshortlist_get_filter_set( $params ) {
	q2( $params, __FUNCTION__ );

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
	$variants     = array();
	$last_variant = '';
	foreach ( $params as $param ) {
		$variant      = ( $last_variant ? $last_variant . ':' : '' ) . $param;
		$variants[]   = $variant;
		$last_variant = $variant;
	}

	// Match filter set rules against the current page conditions.
	foreach ( $filter_sets as $filter_set ) {
		if ( isset( $filter_set['rules'] ) && array_intersect( $variants, (array) $filter_set['rules'] ) ) {
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
			if ( $filter_set ) {
				$filter_sets[] = $filter_set;
			}
		}
	}

	q2( $filter_sets, '', 'o', 'filter-sets.log' );
	update_option( 'wpshortlist_filter_sets', $filter_sets );
}
