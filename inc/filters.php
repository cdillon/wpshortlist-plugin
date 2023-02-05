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

	// Filter sets.
	$config = wpshortlist_get_config();

	$template       = wpshortlist_get_current_filter_template();
	$template_found = get_template_part( 'template-parts/wpshortlist/' . $template );

	if ( false === $template_found ) {
		// Load default.
		include WPSHORTLIST_TPL_DIR . 'default.php';
	}
}

/**
 * Get the current URL.
 */
function wpshortlist_get_current_url() {
	global $wp;
	return trailingslashit( home_url( add_query_arg( array(), $wp->request ) ) );
}

/**
 * Print filter list.
 *
 * @param array $filter  A filter.
 *
 * @return void
 */
function wpshortlist_print_filter_list( $filter ) {
	?>
	<ul class="wpshortlist-filter-list">
	<?php
	foreach ( $filter['options'] as $option_id => $option_name ) :

		// Build a unique ID like 'supports-display-term-list-tags'.
		$input_id = $filter['query_var'] . '-' . $option_id;

		$checked = get_query_var( $filter['query_var'] === $option_id );

		$args = array(
			'type'    => $filter['input_type'],
			'id'      => $input_id,
			'name'    => $filter['query_var'],
			'value'   => $option_id,
			'title'   => $option_id,
			'label'   => $option_name,
			'checked' => $checked,
		);
		wpshortlist_filter_list_item( $args );
		?>

	<?php endforeach; ?>
	</ul>
	<?php
}

/**
 * Print filter list item (radio or checkbox).
 *
 * Using `echo esc_attr` instead of `esc_attr_e` to please PHPCS.
 *
 * @param array $args  HTML input attributes.
 *
 * @return void
 */
function wpshortlist_filter_list_item( $args ) {
	?>
	<li class="wpshortlist-filter-list-item">
		<input type="<?php echo esc_attr( $args['type'] ); ?>"
			id="<?php echo esc_attr( $args['id'] ); ?>"
			name="<?php echo esc_attr( $args['name'] ); ?>"
			value="<?php echo esc_attr( $args['value'] ); ?>"
			title="<?php echo esc_attr( $args['title'] ); ?>"
			<?php checked( $args['checked'] ); ?> />
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<?php echo esc_html( $args['label'] ); ?>
		</label>
	</li>
	<?php
}
