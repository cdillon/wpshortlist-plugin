<?php
/**
 * Ajax Functions
 */

/**
 * Feature Directory options.
 */
function wpshortlist_ajax_handler() {
	check_admin_referer( 'wpshortlist', 'nonce' );

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

	/**
	 * Error checking
	 */

	// form values
	$args = $_REQUEST['formData'];
	if ( empty( $args ) ) {
		wp_send_json_error();
	}

	// term
	if ( ! isset( $_REQUEST['term'] ) || ! $_REQUEST['term'] ) {
		wp_send_json_error();
	}
	$current_term = $_REQUEST['term'];

	// taxonomy
	if ( ! isset( $_REQUEST['taxonomy'] ) || ! $_REQUEST['taxonomy'] ) {
		wp_send_json_error();
	}
	
	$matching_taxonomies = get_taxonomies( 
		[ 
			'public' => true,
			'path'   => $_REQUEST['taxonomy'],
		], 
		'names' 
	);
	/*
	Array 
	( 
		[wp_feature] => wp_feature 
	) 
	*/

	if ( empty( $matching_taxonomies ) ) {
		wp_send_json_error();
	}
	
	// Assuming only one taxonomy found.
	$current_taxonomy = array_keys( $matching_taxonomies )[0];

	/**
	 * Build the requested URL.
	 */

	// Get link to the current taxonomy/term.
	$new_url  = get_term_link( $current_term, $current_taxonomy );
	
	// Append the query vars from our filter config.
	$new_args = [];
	$config   = wpshortlist_get_config();

	foreach ( $config as $filter_set ) {

		// can this be simplified with some config getters?
		if ( $current_term == $filter_set['term'] 
				&& $current_taxonomy == $filter_set['taxonomy'] ) {

			foreach ( $filter_set['filters'] as $filter ) {
				foreach ( $args as $arg_key => $arg_value ) {
					if ( $arg_key == $filter['id'] && in_array( $arg_value, array_keys( $filter['options'] ) ) ) {
						// if ( ! isset( $new_args[ $filter['query_var'] ] ) ) {
							$new_args[ $filter['query_var'] ] = $arg_value;
						// }
					}
				}
			}
		}

	}
	
	// Assemble the URL.
	if ( $new_args ) {
		$new_url = add_query_arg( $new_args, $new_url );
		// error_log($new_url);
		wp_send_json_success( $new_url );
	}
}

add_action( 'wp_ajax_filter_change', 'wpshortlist_ajax_handler' );
add_action( 'wp_ajax_nopriv_filter_change', 'wpshortlist_ajax_handler' );
