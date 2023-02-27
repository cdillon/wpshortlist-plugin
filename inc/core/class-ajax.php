<?php
/**
 * Ajax
 *
 * @since 1.0.0
 *
 * @package wpshortlist
 */

namespace Shortlist\Core;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Ajax
 *
 * @since 1.0.0
 */
class Ajax {

	/**
	 * Init
	 */
	public function init() {
		add_action( 'wp_ajax_filter_change', array( $this, 'ajax_handler' ) );
		add_action( 'wp_ajax_nopriv_filter_change', array( $this, 'ajax_handler' ) );
	}

	/**
	 * Feature Directory options.
	 */
	public function ajax_handler() {
		check_admin_referer( 'wpshortlist', 'nonce' );

		// phpcs:disable
		// error_log(print_r($_REQUEST,true));
		/*
		REQUEST: Array
		(
		[action] => filter_change
		[nonce] => 02ac20c09c
		[pathname] => /features/display-term-list/
		[start] => tax_archive:feature:display-term-list
		[current] => Array
			(
				[type] => tax_archive
				[tax] => feature
				[term] => display-term-list
			)

		[formData] => Array
			(
				[method-display-term-list] => Array
					(
						[0] => widget
					)

				[supports-display-term-list] => Array
					(
						[0] => tags
					)

			)

		)
		*/
		// phpcs:enable

		// Sanitize the request.
		$request = map_deep( wp_unslash( (array) $_REQUEST ), 'sanitize_text_field' );
		// phpcs:ignore
		// error_log( print_r( $request, true ) );

		// If form empty, return to starting page.
		if ( ! isset( $request['formData'] ) ) {
			$new_url = false;
			// start is like 'tax_archive:feature:display-term-list'.
			$start = explode( ':', $request['start'] );
			switch ( $start[0] ) {
				case 'post_type_archive':
					$new_url = get_post_type_archive_link( $start[1] );
					break;
				case 'tax_archive':
					if ( isset( $start[2] ) ) {
						$new_url = get_term_link( $start[2], $start[1] );
					} else {
						$new_url = home_url( $request['pathname'] );
					}
					break;
				default:
			}

			if ( ! $new_url || is_wp_error( $new_url ) ) {
				wp_send_json_error( 'start not found' );
			} else {
				wp_send_json_success( $new_url );
			}
		}

		// Form is not empty so process the form values.
		$new_url  = home_url( $request['pathname'] );
		$new_args = array();

		// Get form values.
		$search_params = (array) $request['formData'];

		// Get the current filter sets.
		$filter_sets = new \Shortlist\Core\Filter_Sets();
		$current     = $filter_sets->get_filter_sets( $request['current'] );

		// Reduce list to those that actually have filters.
		$has_filters = array_filter(
			$current,
			function( $f ) {
				return ( isset( $f['filters'] ) && $f['filters'] );
			}
		);

		// Iterate search arguments.
		foreach ( $search_params as $s_arg => $s_values ) {
			foreach ( $has_filters as $filter_set ) {
				foreach ( $filter_set['filters'] as $filter ) {

					switch ( $filter['type'] ) {

						case 'tax':
							// Get the pretty tax_archive URL. There can be only one.
							$tax     = $this->get_tax_by_query_var( $s_arg );
							$archive = get_term_link( $s_values[0], $tax );
							if ( is_wp_error( $archive ) ) {
								wp_send_json_error( array( 'term archive link not found', $s_arg, $s_values[0] ) );
							} else {
								$new_url = $archive;
							}
							break;

						case 'post_meta':
							// Assemble the search arguments.
							$option_names = array_keys( $filter['options'] );
							foreach ( $s_values as $arg_value ) {
								if ( in_array( $arg_value, $option_names, true ) ) {
									$new_args[ $s_arg ][] = $arg_value;
								}
							}
							break;

						case 'tax_query_var':
							// Assemble the search arguments.
							$option_names = array_keys( $filter['options'] );
							foreach ( $s_values as $arg_value ) {
								if ( in_array( $arg_value, $option_names, true ) ) {
									$new_args[ $s_arg ][] = $arg_value;
								}
							}
							break;

						default:
							wp_send_json_error( 'unknown filter type' );
					}
				}
			}
		}

		// If post_meta, convert multiple values into a delimited string.
		foreach ( $new_args as $qkey => $qval ) {
			$new_args[ $qkey ] = implode( '|', $qval );
		}

		// Assemble the URL.
		if ( $new_url && $new_args ) {
			$new_url = add_query_arg( $new_args, $new_url );
		}

		if ( $new_url ) {
			wp_send_json_success( $new_url );
		}

		wp_send_json_error();
	}

	/**
	 * Find a taxonomy by its query_var.
	 *
	 * @param string $qv The query var.
	 */
	private function get_tax_by_query_var( $qv ) {
		if ( ! $qv ) {
			return false;
		}

		$tax = get_taxonomies( array( 'query_var' => $qv ) );

		return current( array_keys( $tax ) );
	}

}
