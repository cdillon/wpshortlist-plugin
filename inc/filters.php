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
	$filter_sets = wpshortlist_get_current_filter_set();
	if ( ! $filter_sets ) {
		return;
	}

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
	$current_query = wpshortlist_get_current_query_type();
	?>
	<ul class="wpshortlist-filter-list">
	<?php
	foreach ( $filter['options'] as $option_id => $option_name ) :
		$checked = false;

		// Build a unique ID like 'supports-display-term-list-tags'.
		$input_id = $filter['query_var'] . '-' . $option_id;

		// @todo Convert these to a switch?

		// Post meta are query string parameters (a.k.a. search args)
		// and are available through `get_query_var()`.
		// May be present on tax archives or post type archives.
		if ( 'post_meta' === $filter['type'] ) {
			$q_value = get_query_var( $filter['query_var'] );
			$checked = in_array( $option_id, explode( '|', $q_value ), true );
		}

		// Primary taxonomy (using rewrites) are available through `$current_query`.
		// Must check if we are on a tax archive because the filter may be
		// present on post type archives too.
		if ( 'tax' === $filter['type'] && 'tax_archive' === $current_query['type'] ) {
			$checked = $option_id === $current_query['term'];
		}

		// Secondary taxonomies are query string parameters like post meta above.
		if ( 'tax_query_var' === $filter['type'] ) {
			$q_value = get_query_var( $filter['query_var'] );
			$checked = in_array( $option_id, explode( '|', $q_value ), true );
		}

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
 * @param array $filter  A filter.
 *
 * @return void
 */
function wpshortlist_print_filter_actions( $filter ) {
	// [RESET] link.
	wpshortlist_print_filter_reset( $filter );

	// [CHECK ALL] link for checkboxes.
	if ( 'checkbox' === $filter['input_type'] ) {
		wpshortlist_print_filter_check_all();
	}
}

/**
 * Print a reset link for a single filter.
 *
 * @return void
 */
function wpshortlist_print_filter_reset() {
	?>
	<div class="filter-action action-reset">
		<div class="action-enabled" style="display: none;">
			<a href="#" title="reset this filter" data-action="reset">
				<?php esc_html_e( 'reset', 'wpshortlist' ); ?>
			</a>
		</div>
		<div class="action-disabled" style="display: none;">
			<?php esc_html_e( 'reset', 'wpshortlist' ); ?>
		</div>
	</div>
	<?php
}

/**
 * Print "check all" link.
 *
 * @return void
 */
function wpshortlist_print_filter_check_all() {
	?>
	<div class="filter-action action-check-all">
		<div class="action-enabled" style="display: none;">
			<a href="#" title="select every option" data-action="check-all">
				<?php esc_html_e( 'check all', 'wpshortlist' ); ?>
			</a>
		</div>
		<div class="action-disabled" style="display: none;">
			<?php esc_html_e( 'check all', 'wpshortlist' ); ?>
		</div>
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
	<div class="form-action action-reset">
		<div class="action-enabled" style="display: none;">
			<a href="#" title="reset all filters" data-action="reset-form">
				<?php esc_html_e( 'reset all', 'wpshortlist' ); ?>
			</a>
		</div>
		<div class="action-disabled" style="display: none;">
			<?php esc_html_e( 'reset all', 'wpshortlist' ); ?>
		</div>
	</div>
	<?php
}

/**
 * Print 'relation' explainer.
 *
 * @param array $filter  A filter.
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

/**
 * Print filter content.
 *
 * @param array $filter_set  A filter set.
 *
 * @return void
 */
function wpshortlist_print_filter_set_content( $filter_set ) {
	if ( isset( $filter_set['content'] ) && $filter_set['content'] ) {
		?>
		<div class="wpshortlist-filter-set-content">
			<?php echo esc_html( $filter_set['content'] ); ?>
		</div>
		<?php
	}
}
