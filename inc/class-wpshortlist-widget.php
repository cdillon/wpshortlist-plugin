<?php
/**
 * Widget
 *
 * @package wpshortlist
 */
class Wpshortlist_Widget extends WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'wpshortlist_widget', // Base ID.
			__( 'Shortlist Widget', 'wpshortlist' ), // Name.
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
		if ( ! is_tax( 'wp_feature' ) ) {
			return;
		}

		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		// @todo Pass current taxonomy to filters? Or just make the check above for any of our CT?
		echo wpshortlist_filters();

		echo $args['after_widget'];
	}

	/**
	 *
	 */
	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'New title', 'wpshortlist' );
		}
		// Widget admin form
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
				name="<?php echo $this->get_field_name( 'title' ); ?>"
				type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
	}

	/**
	 *
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}
}

/**
 * Register and load the widget.
 */
function wpshortlist_load_widget() {
	register_widget( 'wpshortlist_widget' );
}

add_action( 'widgets_init', 'wpshortlist_load_widget' );
