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
				|| is_tax( array( 'feature', 'feature_category', 'tool_type' ) )
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
	 * @todo How to store these configurations somewhere? Filters?
	 *
	 * @return string
	 */
	public function archive_title( $title, $original_title, $prefix ) {
		$title = $this->get_archive_title();
		if ( $title ) {
			$prefix = '';
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
	 * New archive title.
	 *
	 * Get the archive title from the post_type or term properties.
	 *
	 * possible routes
	 * ---------------
	 * feature
	 * feature directory
	 * feature directory + feature category
	 * tool directory
	 * tool directory + tool type
	 */
	private function get_archive_title() {
		global $wp_query;
		$q = $wp_query->query;

		// Feature.
		if ( isset( $q['feature'] ) ) {
			$term = get_term_by( 'slug', $q['feature'], 'feature' );
			return $term->name;
		}

		// Feature Directory.
		if ( isset( $q['post_type'] ) && 'feature_proxy' === $q['post_type'] ) {
			// plus Feature Category.
			if ( isset( $q['feature-category'] ) ) {
				$term = get_term_by( 'slug', $q['feature-category'], 'feature_category' );
				return $term->name;
			} else {
				$obj    = get_post_type_object( 'feature_proxy' );
				$labels = get_post_type_labels( $obj );
				if ( isset( $labels->archive_title ) && $labels->archive_title ) {
					return $labels->archive_title;
				}
			}
		}

		// Tool Directory.
		if ( isset( $q['post_type'] ) && 'tool' === $q['post_type'] ) {
			// plus Tool Type.
			if ( isset( $q['tool-type'] ) ) {
				$term = get_term_by( 'slug', $q['tool-type'], 'tool_type' );
				return $term->name;
			} else {
				$obj    = get_post_type_object( 'tool' );
				$labels = get_post_type_labels( $obj );
				if ( isset( $labels->archive_title ) && $labels->archive_title ) {
					return $labels->archive_title;
				}
			}
		}

		return false;
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
