<?php

/**
 * Register our query vars.
 */
function wpshortlist_register_query_vars( $vars ) {
	$config = wpshortlist_get_config();
	
	foreach ( $config as $filter_set ) {
		foreach ( $filter_set['filters'] as $filter ) {
			$vars[] = $filter['query_var'];
		}
	}
	
	return $vars;
}

add_filter( 'query_vars', 'wpshortlist_register_query_vars' );

/**
 * Print our filters. Called by widget.
 */
function wpshortlist_filters() {

	// get current URL without args (i.e. CPT/CT permalink only)
	$current_url = wpshortlist_get_current_url();
	// q2($current_url,__FUNCTION__.': current_url');
	
	// get query args (i.e. our post meta)
	$current_args = wpshortlist_get_current_query_args();
	// remove CPT/CT
	unset( $current_args['feature'] );
	// q2($current_args, __FUNCTION__.': current_args');

	echo '<form id="wpshortlist-form" class="wpshortlist-form">';

	/**
	 * Filter sets
	 */
	$config = wpshortlist_get_config();
	
	foreach ( $config as $filter_set ) {

		// Is this filter set for this term?
		if ( is_tax( $filter_set['taxonomy'], $filter_set['term'] ) ) {

			// debug
			// echo '<p><code>' . esc_html( $filter_set['name'] ) . '</code></p>';
			// echo '<p><code>' . $current_args[0] . '</code></p>';

			// Print filter
			foreach ( $filter_set['filters'] as $filter ) {

				$template       = wpshortlist_get_current_filter_template();
				$template_found = get_template_part( 'template-parts/wpshortlist/' . $template );

				if ( false === $template_found ) {
					// fail, load default
					include 'template-parts/default.php';
				}

			}

		}

	}

	echo '</form>';

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

/**
 * 
 */
function wpshortlist_alter_query( $query ) {
	if ( ! wpshortlist_ok_modify() ) {
		return;
	}
	// q2($query->query,'pre_get_posts: query->query');
	
	$meta_query = [];
	// $meta_query['relation'] = 'AND';   // 'OR' or 'AND' (default)

	$config = wpshortlist_get_config();

	foreach ( $config as $filter_set ) {
		foreach ( $filter_set['filters'] as $filter ) {

			if ( isset( $query->query[ $filter['query_var'] ] ) ) {
				$meta_query[] = [
					'key'     => $filter['query_var'],
					'value'   => [ $query->query[ $filter['query_var'] ] ],
					'compare' => 'IN',
				];
					
			}
			
		}
	}

	$query->set( 'meta_query', $meta_query );
	// q2($meta_query,'new meta query');
}

add_action( 'pre_get_posts', 'wpshortlist_alter_query' );

/**
 * If it's OK to modify or debug stuff like the main query. 
 * This can become more robust.
 */
function wpshortlist_ok_modify() {
	return ( is_post_type_archive( ['tool'] ) || is_tax( [ 'wp_feature' ] ) );
}
