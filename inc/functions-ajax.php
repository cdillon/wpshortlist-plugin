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
	Array
	(
		[action] => filter_change
		[nonce] => 90e6a55f84
		[formData] => Array
			(
				[method] => block
			)
		[taxonomy] => features
		[term] => display-term-list
	)
	*/
	// phpcs:enable

	/**
	 * Sanitize the request and get our values.
	 */

	$request = map_deep( wp_unslash( (array) $_REQUEST ), 'sanitize_text_field' );

	// Get form values.
	if ( ! isset( $request['formData'] ) ) {
		wp_send_json_error();
	}
	$args = (array) $request['formData'];

	// Get term.
	if ( ! isset( $request['term'] ) || ! $request['term'] ) {
		wp_send_json_error();
	}
	$current_term = $request['term'];

	// Get taxonomy.
	if ( ! isset( $request['taxonomy'] ) || ! $request['taxonomy'] ) {
		wp_send_json_error();
	}

	$matching_taxonomies = get_taxonomies(
		array(
			'public' => true,
			'path'   => $request['taxonomy'],
		),
		'names'
	);

	if ( empty( $matching_taxonomies ) ) {
		wp_send_json_error();
	}

	// Assuming only one taxonomy found.
	$current_taxonomy = array_keys( $matching_taxonomies )[0];

	/**
	 * Build the requested URL.
	 */

	// Get link to the current taxonomy/term.
	$new_url = get_term_link( $current_term, $current_taxonomy );

	// Assemble the query vars.
	$new_args   = array();
	$filter_set = wpshortlist_get_filter_set( $current_taxonomy, $current_term );

	foreach ( $filter_set['filters'] as $filter ) {
		$option_names = array_keys( $filter['options'] );
		foreach ( $args as $arg_key => $arg_values ) {
			if ( $arg_key === $filter['query_var'] ) {
				foreach ( $arg_values as $arg_value ) {
					if ( in_array( $arg_value, $option_names, true ) ) {
						$new_args[ $arg_key ][] = $arg_value;
					}
				}
			}
		}
	}

	// Create query string from multiple values.
	foreach ( $new_args as $qkey => $qval ) {
		$new_args[ $qkey ] = implode( '|', $qval );
	}

	// Assemble the URL.
	if ( $new_args ) {
		$new_url = add_query_arg( $new_args, $new_url );
		wp_send_json_success( $new_url );
	}

	wp_send_json_error();
}

add_action( 'wp_ajax_filter_change', 'wpshortlist_ajax_handler' );
add_action( 'wp_ajax_nopriv_filter_change', 'wpshortlist_ajax_handler' );
