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
	// phpcs:ignore
	// q2($current_url,__FUNCTION__.': current_url');

	// Get query args (our post meta).
	$current_args = wpshortlist_get_current_query_args();

	// Remove CPT/CT.
	unset( $current_args['feature'] );
	// phpcs:ignore
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

/**
 * Print filter list.
 *
 * @param Array $filter  A filter.
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

		$checked = isset( $current_args[ $filter['query_var'] ] )
			&& $option_id === $current_args[ $filter['query_var'] ];

		$args = array(
			'id'      => $input_id,
			'name'    => $filter['id'],
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
 * Print filter list item.
 *
 * @param Array $args  HTML input attributes.
 *
 * @return void
 */
function wpshortlist_filter_list_item( $args ) {
	?>
	<li class="wpshortlist-filter-list-item">
		<input type="radio"
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
