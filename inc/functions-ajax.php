<?php
/**
 * Ajax Functions
 *
 * @package wpshortlist
 */

/**
 * Feature Directory options.
 */
function wpshortlist_ajax_handler() {
	check_admin_referer( 'wpshortlist', 'nonce' );

	// phpcs:disable
	// error_log(print_r($_REQUEST,true));
	/*
	REQUEST: Array
	(
		[action] => filter_change
		[nonce] => b1963d2221
		[pathname] => /features/display-term-list/
		[formData] => Array
			(
				[method-display-term-list] => Array
					(
						[0] => block
					)
				[supports-display-term-list] => Array
					(
						[0] => tags
					)
			)
	)


	OUTPUT: Array
	(
		[method-display-term-list] => block
		[supports-display-term-list] => tags
	)
	*/
	// phpcs:enable

	/**
	 * Sanitize the request and get our values.
	 */

	$request = map_deep( wp_unslash( (array) $_REQUEST ), 'sanitize_text_field' );

	// If form empty, simply return to taxonomy archive page.
	if ( ! isset( $request['formData'] ) ) {
		wp_send_json_success( home_url( $request['pathname'] ) );
	}

	// Get form values.
	$search_args = (array) $request['formData'];

	// Get pathname. Expecting pretty permalinks like /{taxonomy}/{term}.
	// Would need to adjust for old-school query string.
	$tax_args = explode( '/', trim( $request['pathname'], '/' ) );
	if ( 2 !== count( $tax_args ) ) {
		wp_send_json_error( 'invalid pathname' );
	}

	$current_tax_slug = $tax_args[0];
	if ( ! $current_tax_slug ) {
		wp_send_json_error( 'missing taxonomy' );
	}

	$current_term = $tax_args[1];
	if ( ! $current_term ) {
		wp_send_json_error( 'missing term' );
	}

	// Find taxonomies using our custom `path` property.
	$matching_taxonomies = get_taxonomies(
		array(
			'public' => true,
			'path'   => $current_tax_slug,
		),
		'names'
	);

	if ( empty( $matching_taxonomies ) ) {
		wp_send_json_error( 'taxonomy not found' );
	}

	// Assuming only one taxonomy found.
	$current_taxonomy = array_keys( $matching_taxonomies )[0];

	/**
	 * Build the requested URL.
	 */

	// Assemble the query vars.
	$new_args    = array();
	$params      = array(
		'type' => 'tax_archive',
		'tax'  => $current_taxonomy,
		'term' => $current_term,
	);
	$filter_sets = wpshortlist_get_filter_set( $params );
	if ( ! $filter_sets ) {
		wp_send_json_error( 'filter not found' );
	}

	foreach ( $filter_sets as $filter_set ) {
		if ( ! isset( $filter_set['filters'] ) ) {
			continue;
		}
		foreach ( $filter_set['filters'] as $filter ) {
			// Each filter has options. Get those option names.
			$option_names = array_keys( $filter['options'] );
			// Compare request to those options.
			foreach ( $search_args as $arg_key => $arg_values ) {
				// Does the requested param match the filter's query var?
				if ( $arg_key === $filter['query_var'] ) {
					// Assemble valid args.
					foreach ( $arg_values as $arg_value ) {
						if ( in_array( $arg_value, $option_names, true ) ) {
							$new_args[ $arg_key ][] = $arg_value;
						}
					}
				}
			}
		}
	}

	// Convert multiple values into a delimited string.
	foreach ( $new_args as $qkey => $qval ) {
		$new_args[ $qkey ] = implode( '|', $qval );
	}

	// Assemble the URL.
	if ( $new_args ) {
		// ---------- Should this use get_term_link instead? ----------
		$new_url = add_query_arg( $new_args, home_url( $request['pathname'] ) );
		wp_send_json_success( $new_url );
	}

	wp_send_json_error();
}

add_action( 'wp_ajax_filter_change', 'wpshortlist_ajax_handler' );
add_action( 'wp_ajax_nopriv_filter_change', 'wpshortlist_ajax_handler' );
