<?php
/**
 * Meta Box Integration
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
 * Class Meta_Box_Integration
 *
 * @since 1.0.0
 */
class Meta_Box_Integration {

	/**
	 * Init
	 */
	public function init() {
		add_filter( 'rwmb_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_filter( 'rwmb_show', array( $this, 'show_meta_boxes' ), 10, 2 );
	}

	/**
	 * Add meta boxes for feature-specific post meta.
	 *
	 * @param array $meta_boxes Meta boxes.
	 */
	public function add_meta_boxes( $meta_boxes ) {
		if ( ! is_admin() ) {
			return $meta_boxes;
		}

		// Get filter sets.
		$filter_sets = get_option( 'wpshortlist_filter_sets' );
		if ( ! $filter_sets ) {
			return $meta_boxes;
		}

		// Iterate filter sets that have filters.
		$filter_sets = new Filter_Sets();
		$has_filters = $filter_sets->get_filter_sets_with( 'filters' );
		foreach ( $has_filters as $filter_set ) {
			$fields = array();

			foreach ( $filter_set['filters'] as $filter ) {
				if ( isset( $filter['meta_box_type'] ) && $filter['meta_box_type'] ) {
					$fields[] = array(
						'name'    => $filter['name'],
						'id'      => $filter['query_var'],
						'type'    => $filter['meta_box_type'],
						'options' => $filter['options'],
					);
				}
			}

			if ( $fields ) {
				$meta_box     = array(
					'id'         => $filter_set['term'],
					'title'      => $filter_set['taxonomy_title'] . ': ' . $filter_set['name'],
					'post_types' => $filter_set['post_types'],
					'fields'     => $fields,
				);
				$meta_boxes[] = $meta_box;
			}
		}

		return $meta_boxes;
	}


	/**
	 * Only print meta box if the Tool has the Feature.
	 *
	 * @param bool  $show      Whether to show the meta box.
	 * @param array $meta_box  The meta box.
	 */
	public function show_meta_boxes( $show, $meta_box ) {
		// phpcs:disable
		if ( is_admin() && isset( $_GET['post'] ) ) {
			if ( ! has_term( $meta_box['id'], 'feature', $_GET['post'] ) ) {
				return false;
			}
		}
		// phpcs: enable

		return $show;
	}

}
