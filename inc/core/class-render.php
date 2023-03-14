<?php
/**
 * Render
 *
 * @since 1.0.0
 *
 * @package wpshortlist
 */

namespace Shortlist\Core;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Render
 *
 * @since 1.0.0
 */
class Render {

	/**
	 * Current filter sets
	 *
	 * @var array $current
	 */
	private $current;

	/**
	 * Constructor
	 *
	 * @param array $current The current filter sets.
	 */
	public function __construct( $current ) {
		$this->current = $current;
	}

	/**
	 * Print our filters. Called by widget.
	 */
	public function print_filters() {
		// Load default template.
		include WPSHORTLIST_TPL_DIR . 'default.php';
	}

	/**
	 * Print filter list.
	 *
	 * @param array $filter  A filter.
	 *
	 * @return void
	 */
	public function print_filter_list( $filter ) {
		?>
		<ul class="wpshortlist-filter-list">
		<?php
		foreach ( $filter['options'] as $option_id => $option_name ) {

			// Build a unique ID like 'supports-display-term-list-tags'.
			$input_id = $filter['query_var'] . '-' . $option_id;

			$checked = $this->is_checked( $option_id, $filter );

			$args = array(
				'type'    => $filter['input_type'],
				'id'      => $input_id,
				'name'    => $filter['query_var'],
				'value'   => $option_id,
				'title'   => $option_id,
				'label'   => $option_name,
				'checked' => $checked,
			);
			$this->print_filter_list_item( $args );
		}
		?>
		</ul>
		<?php
	}

	/**
	 * Return the input's `checked` value.
	 *
	 * @param string $option_id The option id.
	 * @param array  $filter    The filter.
	 *
	 * @return bool
	 */
	private function is_checked( $option_id, $filter ) {
		$qv = get_query_var( $filter['query_var'] );
		if ( ! is_array( $qv ) ) {
			$qv = explode( '|', $qv );
		}
		return in_array( $option_id, $qv, true );
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
	public function print_filter_list_item( $args ) {
		$grouped = 'checkbox' === $args['type'] ? '[]' : '';
		?>
		<li class="wpshortlist-filter-list-item">
			<input type="<?php echo esc_attr( $args['type'] ); ?>"
				id="<?php echo esc_attr( $args['id'] ); ?>"
				name="<?php echo esc_attr( $args['name'] ) . $grouped; ?>"
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
	public function print_filter_actions( $filter ) {
		// [RESET] link.
		$this->print_filter_reset( $filter );

		// [CHECK ALL] link for checkboxes.
		if ( 'checkbox' === $filter['input_type'] ) {
			$this->print_filter_check_all();
		}
	}

	/**
	 * Print a reset link for a single filter.
	 *
	 * @param array $filter The filter.
	 *
	 * @return void
	 */
	public function print_filter_reset( $filter ) {
		global $wp_query, $wp;
		$q = $wp_query->query;
		// Remove the current filter's query var and the archive query vars.
		unset( $q[ $filter['query_var'] ], $q['feature'], $q['post_type'], $q['tool'] );
		$reset = home_url( add_query_arg( $q, $wp->request ) );
		?>
		<div class="filter-action action-reset">
			<div class="action-enabled">
				<a href="<?php echo esc_url( $reset ); ?>" title="reset this filter" data-action="reset">
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
	 * Print a reset link for the entire form.
	 *
	 * @return void
	 */
	public function print_filter_reset_all() {
		global $wp_query;
		$q     = $wp_query->query;
		$reset = false;

		if ( isset( $q['post_type'] ) && 'feature_proxy' === $q['post_type'] ) {
			$reset = get_post_type_archive_link( 'feature_proxy' );
		}

		if ( isset( $q['post_type'] ) && 'tool' === $q['post_type'] ) {
			$reset = get_post_type_archive_link( 'tool' );
		}

		if ( isset( $q['feature'] ) ) {
			$reset = get_term_link( $q['feature'], 'feature' );
		}

		if ( ! $reset ) {
			return;
		}
		?>
		<div class="form-action action-reset">
			<div class="action-enabled">
				<a href="<?php echo esc_url( $reset ); ?>" title="reset all filters" data-action="reset-form">
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
	 * Print "check all" link.
	 *
	 * @return void
	 */
	public function print_filter_check_all() {
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
	 * Print 'relation' explainer.
	 *
	 * @param array $filter  A filter.
	 *
	 * @return void
	 */
	public function print_explainer( $filter ) {
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
	public function print_filter_set_content( $filter_set ) {
		if ( isset( $filter_set['content'] ) && $filter_set['content'] ) {
			?>
			<div class="wpshortlist-filter-set-content">
				<?php echo esc_html( $filter_set['content'] ); ?>
			</div>
			<?php
		}
	}

}
