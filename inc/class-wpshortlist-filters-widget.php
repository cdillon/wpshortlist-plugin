<?php
/**
 *  Filters Widget
 *
 * @package wpshortlist
 */

/**
 * Class WPShortlist_Filters_Widget
 *
 * @since 1.0
 *
 * @see WP_Widget
 */
class WPShortlist_Filters_Widget extends WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'wpshortlist_widget', // Base ID.
			__( 'Shortlist Filters Widget', 'wpshortlist' ), // Name.
			array( 'description' => __( 'Make your shortlist.', 'wpshortlist' ) )
		);
	}

	/**
	 * Widget.
	 *
	 * @param array $args      Widget args.
	 * @param array $instance  Widget instance.
	 *
	 * @return void
	 */
	public function widget( $args, $instance ) {
		// @todo Make conditional. Check if current query has filters.

		$title = apply_filters( 'widget_title', $instance['title'] );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $args['before_title'] . $title . $args['after_title'];
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo wpshortlist_filters();

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['after_widget'];
	}

	/**
	 * Admin form.
	 *
	 * @param WP_Widget $instance  Widget instance.
	 */
	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'Filters', 'wpshortlist' );
		}
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_attr_e( 'Title:' ); ?>
				<input class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
					type="text"
					value="<?php echo esc_attr( $title ); ?>" />
			</label>
		</p>
		<?php
	}

	/**
	 * Update settings.
	 *
	 * @param WP_Widget $new_instance  New instance.
	 * @param WP_Widget $old_instance  Old instance.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'], null ) : '';

		return $instance;
	}

}
