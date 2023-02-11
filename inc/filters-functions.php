<?php
/**
 * Filter Functions
 *
 * @package wpshortlist
 */

/**
 * Return the filter set for the current taxonomy and term
 * or a specific taxonomy and term. Return false if not found.
 *
 * @param array $params  Parameters.
 *
 * @todo Is it time for a filter set to be a custom post type? Or to have its own table?
 *
 * @return array|false
 */
function wpshortlist_get_filter_set( $params ) {
	$filter_set = false;

	switch ( $params['type'] ) {
		case 'tax_archive':
			$name = $params['tax'] . '-' . $params['term'];
			break;
		case 'post_type_archive':
			$name = $params['post_type'] . '-' . $params['name'];
			break;
		default:
			$name = '';
	}

	if ( $name ) {
		// Look for stored option.
		// @todo Add admin option to force reload from files.
		$option_name = 'filter_set-' . $name;
		$filter_set  = get_option( $option_name, wpshortlist_new_filter_set( $name, $option_name ) );
	}

	return $filter_set;
}

/**
 * Save a filter set in the options table and update dependent options.
 *
 * @param string $name         The filter set name.
 * @param string $option_name  The option name.
 *
 * @return array|false
 */
function wpshortlist_new_filter_set( $name, $option_name ) {
	if ( ! $name || ! $option_name ) {
		return false;
	}

	// Read config file.
	$json = wpshortlist_load_filter_set( $name );

	if ( $json ) {
		// Save new filter set.
		$filter_set = json_decode( $json, true );
		update_option( $option_name, $filter_set );

		// Update list of filter sets.
		wpshortlist_update_option_filter_set_names( $option_name );

		// Collect new query vars.
		wpshortlist_update_option_query_vars( $filter_set );
	}

	return $json;
}

/**
 * Load a filter set's config file.
 *
 * @see https://developer.wordpress.org/reference/classes/wp_filesystem_direct/get_contents/
 *
 * @param string $name  The filter set name.
 */
function wpshortlist_load_filter_set( $name ) {
	if ( ! $name ) {
		return false;
	}

	require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';

	$config = WPSHORTLIST_DATA_DIR . $name . '.json';
	$wpfsd  = new WP_Filesystem_Direct( false );
	$json   = $wpfsd->get_contents( $config );

	return $json;
}

/**
 * Update list of filter sets used by Meta Box plugin.
 *
 * @param string $option_name  The option name.
 */
function wpshortlist_update_option_filter_set_names( $option_name ) {
	if ( ! $option_name ) {
		return;
	}

	$names   = get_option( 'wpshortlist_filter_set_names', array() );
	$names[] = $option_name;
	update_option( 'wpshortlist_filter_set_names', array_unique( $names ) );
}

/**
 * Update list of query vars.
 *
 * @param array $filter_set  The filter set.
 */
function wpshortlist_update_option_query_vars( $filter_set ) {
	if ( ! $filter_set ) {
		return;
	}

	$query_vars = get_option( 'wpshortlist_query_vars', array() );
	foreach ( $filter_set['filters'] as $filter ) {
		if ( isset( $filter['query_var'] ) ) {
			$query_vars[] = $filter['query_var'];
		}
	}
	update_option( 'wpshortlist_query_vars', array_unique( $query_vars ) );
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
