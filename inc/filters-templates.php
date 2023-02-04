<?php

// for now
add_action( 'init', function () {
	wpshortlist_set_current_filter_template( 'default' );
});

/**
 * Set our filter template options.
 */ 
function wpshortlist_set_filter_templates() {
	$templates = [
		'default' => 'Default',
	];	

	update_option( 'wpshortlist_filter_templates', apply_filters( 'wpshortlist_filter_templates', $templates ) );
}	

/**
 * Set current filter template.
 */ 
function wpshortlist_set_current_filter_template( $template_name ) {
	if ( ! $template_name ) {
		$template_name = 'default';
	}	

	update_option( 'wpshortlist_current_filter_template', $template_name );
}	

/**
 * Get current filter template.
 */
function wpshortlist_get_current_filter_template() {
	return get_option( 'wpshortlist_current_filter_template', 'default' );
}
