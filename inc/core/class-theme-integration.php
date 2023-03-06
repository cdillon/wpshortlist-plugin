<?php
/**
 * Theme Integration
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
 * Class Theme_Integration
 *
 * @since 1.0.0
 */
class Theme_Integration {

	/**
	 * Init
	 */
	public function init() {
		add_filter( 'get_the_archive_title', array( $this, 'archive_title' ), 20, 3 );
		add_filter( 'get_the_archive_description', array( $this, 'archive_description' ), 999 );

		add_action( 'kadence_loop_entry_header', array( $this, 'loop_entry_type' ) );
		add_action( 'kadence_entry_header', array( $this, 'loop_entry_type' ) );
	}

	/**
	 * Loop entry type
	 */
	public function loop_entry_type() {
		// @todo Replace this logic with a common function.
		if ( ! ( is_post_type_archive( array( 'tool', 'feature_proxy' ) )
				|| is_tax( array( 'feature', 'tool_type' ) )
				|| is_singular( 'tool' ) ) ) {
			return;
		}
		?>
		<div class="entry-label">
			<?php echo esc_attr( get_post_type_primary_label() ); ?>
		</div>
		<?php
	}

	/**
	 * Modify the archive title.
	 *
	 * @param string $title The title.
	 * @param string $original_title The original title.
	 * @param string $prefix The prefix.
	 *
	 * @todo How to store these configurations somewhere?
	 *
	 * @return string
	 */
	public function archive_title( $title, $original_title, $prefix ) {
		if ( is_post_type_archive( 'tool' ) ) {

			$obj    = get_post_type_object( 'tool' );
			$labels = get_post_type_labels( $obj );
			if ( isset( $labels->archive_title ) && $labels->archive_title ) {
				$title = $labels->archive_title;
			}

			// Remove default WordPress prefix.
			$prefix = '';

		} elseif ( is_post_type_archive( 'feature_proxy' ) ) {

			$obj    = get_post_type_object( 'feature_proxy' );
			$labels = get_post_type_labels( $obj );
			if ( isset( $labels->archive_title ) && $labels->archive_title ) {
				$title = $labels->archive_title;
			}

			// Remove default WordPress prefix.
			$prefix = '';

		} elseif ( is_tax() ) {

			// Find the primary tax.
			$current_query = get_current_query_type();
			if ( $current_query ) {
				$tax    = get_taxonomy( $current_query['tax'] );
				$term   = get_term_by( 'slug', $current_query['term'], $current_query['tax'] );
				$title  = $term->name;
				$prefix = sprintf(
				/* translators: %s: Taxonomy singular name. */
					_x( '%s:', 'taxonomy term archive title prefix' ),
					$tax->labels->singular_name
				);
				$prefix = '';
			}
		}

		if ( $prefix ) {
			$title = sprintf(
			/* translators: 1: Title prefix. 2: Title. */
				_x( '%1$s %2$s', 'archive title' ),
				$prefix,
				'<span>' . $title . '</span>'
			);
		}

		return $title;
	}

	/**
	 * Modify the archive description.
	 *
	 * *** This overrides Discovery plugin. ***
	 *
	 * @param string $description The archive description.
	 */
	public function archive_description( $description ) {
		$current_query = get_current_query_type();

		// On Feature Directory, if tool type filter is active, the queried object is that tool type.
		// We need our primary tax Feature instead.
		if ( 'tax_archive' === $current_query['type'] ) {
			$posts = false;

			if ( 'feature' === $current_query['tax'] ) {
				// Find the proxy post.
				// @todo Store this post ID in options table instead of fetching every time.
				$posts = get_posts(
					array(
						'name'           => $current_query['term'],
						'posts_per_page' => 1,
						'post_type'      => 'feature_proxy', // @todo can this be tied to our proxy config?
						'post_status'    => 'publish',
					)
				);
			}

			// May be necessary to add similar logic when adding more filters to Tool Directory.

			if ( $posts ) {
				return $posts[0]->post_content;
			}
		}

		return $description;
	}

}
