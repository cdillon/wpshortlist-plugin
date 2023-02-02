<?php
/**
 * Default filter template.
 */

echo '<h3>' . esc_html( $filter['name'] ) . '</h3>';

echo '<div class="wpshortlist-filter-container">';
echo '<div class="wpshortlist-filter-item">';
echo '<fieldset>';
echo '<legend></legend>';

foreach ( $filter['options'] as $option_id => $option_name ) {
	
	$unique_id = $filter['query_var'] . '-' . $option_id;  // e.g. supports-display-term-list-tags
	$checked   = isset( $current_args[ $filter['query_var'] ] ) && $option_id == $current_args[ $filter['query_var'] ];

	echo '<div class="wpshortlist-filter-option">';
	printf( '<input type="radio" id="%1$s" name="%2$s" value="%3$s" title="%3$s" %4$s/>', 
		$unique_id,
		$filter['id'],
		$option_id, 
		$checked ? 'checked="checked"' : '',
	);
	printf( '<label for="%s">%s</label>', $unique_id, $option_name );
	echo '</div>';
	
}

echo '</fieldset>';
echo '</div>';
echo '</div>';
