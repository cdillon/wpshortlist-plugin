<?php
/**
 * Meta Box Functions
 *
 * @package wpshortlist
 */

/**
 * Add meta boxes for feature-specific post meta.
 *
 * @param array $meta_boxes Meta boxes.
 */
function wpshortlist_meta_boxes( $meta_boxes ) {
	if ( ! is_admin() ) {
		return $meta_boxes;
	}

	// Get filter sets.
	$filter_sets = get_option( 'wpshortlist_filter_sets' );
	if ( ! $filter_sets ) {
		return $meta_boxes;
	}

	// Iterate filter sets that have filters.
	$has_filters = wpshortlist_get_filter_sets_with( $filter_sets, 'filters' );
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

add_filter( 'rwmb_meta_boxes', 'wpshortlist_meta_boxes' );

/**
 * Only print meta box if the Tool has the Feature.
 *
 * @param bool  $show      Whether to show the meta box.
 * @param array $meta_box  The meta box.
 */
function wpshortlist_rwmb_show( $show, $meta_box ) {
	// phpcs:disable
	if ( is_admin() && isset( $_GET['post'] ) ) {
		if ( ! has_term( $meta_box['id'], 'feature', $_GET['post'] ) ) {
			return false;
		}
	}
	// phpcs: enable

	return $show;
}

add_filter( 'rwmb_show', 'wpshortlist_rwmb_show', 10, 2 );
