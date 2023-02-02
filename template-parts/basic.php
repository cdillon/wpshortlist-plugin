<?php
/**
 * Default filter template with an unordered list of links.
 * 
 * Offers a single criteria only.
 */

echo '<h3>' . esc_html( $filter['name'] ) . '</h3>';
echo '<ul class="wpshortlist-filter-list">';

// Print options
foreach ( $filter['options'] as $option_id => $option_name ) {
	
	echo '<li class="wpshortlist-filter-list-item">';
	if ( isset( $current_args[ $filter['query_var'] ] ) && $option_name == $current_args[ $filter['query_var'] ] ) {
			echo '<strong>' . $option_name . '</strong>';
	} else {
		// this appends one field only to current URL (good for radio buttons)
		$url = add_query_arg( $filter['query_var'], $option_id, $current_url );
		printf( '<a href="%s">%s</a>', $url, $option_name );
	}
	echo '</li>';
	
}

echo '</ul>';
