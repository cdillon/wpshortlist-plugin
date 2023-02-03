<?php
/**
 * The Filters
 */

/**
 * Print our filters. Called by widget.
 */
function wpshortlist_filters() {

	// get current URL without args (the CPT/CT permalink only)
	$current_url = wpshortlist_get_current_url();
	// q2($current_url,__FUNCTION__.': current_url');
	
	// get query args (our post meta)
	$current_args = wpshortlist_get_current_query_args();
	
	// remove CPT/CT
	unset( $current_args['feature'] );
	// q2($current_args, __FUNCTION__.': current_args');

	// Filter sets
	$config = wpshortlist_get_config();

	$template       = wpshortlist_get_current_filter_template();
	$template_found = get_template_part( 'template-parts/wpshortlist/' . $template );

	if ( false === $template_found ) {
		// load default
		include 'template-parts/default.php';
	}
}

/**
 * 
 */
function wpshortlist_get_current_url() {
	global $wp;
	// q2($wp->request,'wp->request');
	return trailingslashit( home_url( add_query_arg( array(), $wp->request ) ) );
}

/**
 * 
 */
function wpshortlist_get_current_query_args() {
	/*
	wp_query->query = Array
	(
		[feature] => display-term-list
		[method-display-term-list] => block
	)
	*/
	global $wp_query;
	return $wp_query->query;
}
