<?php
/**
 * The Filters
 *
 * @package wpshortlist
 */

/**
 * Print our filters. Called by widget.
 */
function wpshortlist_filters() {

	// Get current URL without args (the CPT/CT permalink only).
	$current_url = wpshortlist_get_current_url();
	// q2($current_url,__FUNCTION__.': current_url');

	// Get query args (our post meta).
	$current_args = wpshortlist_get_current_query_args();

	// Remove CPT/CT.
	unset( $current_args['feature'] );
	// q2($current_args, __FUNCTION__.': current_args');

	// Filter sets.
	$config = wpshortlist_get_config();

	$template       = wpshortlist_get_current_filter_template();
	$template_found = get_template_part( 'template-parts/wpshortlist/' . $template );

	if ( false === $template_found ) {
		// Load default.
		include 'template-parts/default.php';
	}
}

/**
 * Get the current URL.
 */
function wpshortlist_get_current_url() {
	global $wp;
	// phpcs:ignore
	// q2($wp->request,'wp->request');
	return trailingslashit( home_url( add_query_arg( array(), $wp->request ) ) );
}

/**
 * Get the current query args.
 */
function wpshortlist_get_current_query_args() {
	// phpcs:ignore
	/* wp_query->query = Array
		(
			[feature] => display-term-list
			[method-display-term-list] => block
		)
	*/
	global $wp_query;
	return $wp_query->query;
}
