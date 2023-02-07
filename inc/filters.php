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

		$q_value = get_query_var( $filter['query_var'] );
		$checked = in_array( $option_id, explode( '|', $q_value ), true );

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

/**
 * Print filter actions.
 *
 * @param  array $filter  A filter.
 *
 * @return void
 */
function wpshortlist_print_filter_actions( $filter ) {
	// [reset] link.
	wpshortlist_print_filter_reset( $filter );

	// [check all] link for checkboxes.
	if ( 'checkbox' === $filter['input_type'] ) {
		wpshortlist_print_filter_check_all( $filter );
	}
}

/**
 * Print "check all" link.
 *
 * @param  array $filter  A filter.
 *
 * @return void
 */
function wpshortlist_print_filter_check_all( $filter ) {
	?>
	<div>
		<span class="wpshortlist-filter-dependent-action">
			<a class="wpshortlist-filter-check-all-link"
				href="#"
				title="select every option"
				data-action="check-all"
				data-filter_name="<?php echo esc_attr( $filter['query_var'] ); ?>">
				<?php esc_html_e( 'check all', 'wpshortlist' ); ?>
			</a>
		</span>
	</div>
	<?php
}

/**
 * Print a reset link for a single filter.
 *
 * @param  array $filter  A filter.
 *
 * @return void
 */
function wpshortlist_print_filter_reset( $filter ) {
	?>
	<div>
		<a class="wpshortlist-reset-filter-link"
			href="#"
			title="reset this filter"
			data-action="reset"
			data-filter_name="<?php echo esc_attr( $filter['query_var'] ); ?>">
			<?php esc_html_e( 'reset', 'wpshortlist' ); ?>
		</a>
	</div>
	<?php
}

/**
 * Print a reset link for the entire form.
 *
 * @return void
 */
function wpshortlist_print_filter_reset_all() {
	?>
	<div>
		<a class="wpshortlist-reset-form-link"
			href="#"
			title="reset all filters"
			data-action="reset-form">
			<?php esc_html_e( 'reset all', 'wpshortlist' ); ?>
		</a>
	</div>
	<?php
}

/**
 * Print 'relation' explainer.
 *
 * @param  array $filter  A filter.
 *
 * @return void
 */
function wpshortlist_print_explainer( $filter ) {
	if ( isset( $filter['relation'] ) && isset( $filter['relation_desc'] ) ) {
		?>
		<div class="wpshortlist-explainer">
			<?php echo esc_html( $filter['relation_desc'] ); ?>
		</div>
		<?php
	}
}
