<?php
/**
 * Filter Functions
 *
 * @package wpshortlist
 */

/**
 * Return our filters config array.
 */
function wpshortlist_get_config() {
	return get_option( 'wpshortlist_filters' );
}

/**
 * Return the filter set for the current taxonomy and term
 * or a specific taxonomy and term. Return false if not found.
 *
 * @param array $params  Parameters.
 *
 * @return array|false
 */
function wpshortlist_get_filter_set( $params ) {
	// Is there a way to autoload based on type+tax+term or type+name?
	$config = wpshortlist_get_config();

	foreach ( $config as $filter_set ) {
		// @todo This needs to check filter set type (tax_archive or post_type_archive).
		if ( $filter_set['taxonomy'] === $params['tax']
				&& $filter_set['term'] === $params['term'] ) {
			return $filter_set;
		}
	}

	return false;
}

/**
 * Return the filter set for the current page.
 */
function wpshortlist_get_current_filter_set() {
	$params = array(
		'type' => 'tax_archive',
		'tax'  => get_query_var( 'taxonomy' ),
		'term' => get_query_var( 'term' ),
	);

	return wpshortlist_get_filter_set( $params );
}
