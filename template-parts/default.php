<?php
/**
 * Default filter template.
 */

/*
Structure:

<form id="wpshortlist-form" class="wpshortlist-form">
	<div class="wpshortlist-filterset">
		<div class="wpshortlist-filter">
			<fieldset>
				<legend>
					<h3 class="wpshortlist-filter-heading">...</h3>
				</legend>
				<ul class="wpshortlist-filter-list">
					<li class="wpshortlist-filter-list-item">
						<input type="radio" ...>
						<label>...</label>
					</li>
				</ul>
			</fieldset>
		</div><!-- .wpshortlist-filter -->
	</div><!-- .wpshortlist-filterset -->
</form>
*/

echo '<form id="wpshortlist-form" class="wpshortlist-form">';

foreach ( $config as $filter_set ) {
	// Is this filter set for this term?
	if ( ! is_tax( $filter_set['taxonomy'], $filter_set['term'] ) ) {
		continue;
	}

	echo '<div class="wpshortlist-filterset">';

	// Print filter
	foreach ( $filter_set['filters'] as $filter ) {

echo '<div class="wpshortlist-filter">';
echo '<fieldset>';
echo '<legend>';
echo '<h3 class="wpshortlist-filter-heading">' . esc_html( $filter['name'] ) . '</h3>';
echo '</legend>';

echo '<ul class="wpshortlist-filter-list">';

foreach ( $filter['options'] as $option_id => $option_name ) {
	
	$unique_id = $filter['query_var'] . '-' . $option_id;  // like 'supports-display-term-list-tags'
	$checked   = isset( $current_args[ $filter['query_var'] ] ) && $option_id == $current_args[ $filter['query_var'] ];

	echo '<li class="wpshortlist-filter-list-item">';

	printf( '<input type="radio" id="%1$s" name="%2$s" value="%3$s" title="%3$s" %4$s/>', 
		$unique_id,
		$filter['id'],
		$option_id, 
		$checked ? 'checked="checked"' : '',
	);

	printf( '<label for="%s">%s</label>', $unique_id, $option_name );

	echo '</li>';

}

echo '</ul>';
echo '</fieldset>';
echo '</div><!-- .wpshortlist-filter -->';

}
echo '</div><!-- .wpshortlist-filterset -->';
}

echo '</form>';
