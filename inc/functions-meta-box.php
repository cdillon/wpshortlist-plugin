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

	if ( ! function_exists( 'wpshortlist_get_config' ) ) {
		return $meta_boxes;
	}

	$config = wpshortlist_get_config();

	foreach ( $config as $filter_set ) {
		// @todo Check filter type here.

		$meta_box = array();
		$fields   = array();

		$meta_box['id']         = $filter_set['term'];
		$meta_box['title']      = $filter_set['taxonomy_title'] . ': ' . $filter_set['name'];
		$meta_box['post_types'] = $filter_set['post_types'];

		foreach ( $filter_set['filters'] as $filter ) {
			$fields[] = array(
				'name'    => $filter['name'],
				'id'      => $filter['query_var'],
				'type'    => $filter['meta_box_type'],
				'options' => $filter['options'],
			);
		}

		$meta_box['fields'] = $fields;

		$meta_boxes[] = $meta_box;
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
		if ( ! has_term( $meta_box['id'], 'wp_feature', $_GET['post'] ) ) {
			return false;
		}
	}
	// phpcs: enable

	return $show;
}

add_filter( 'rwmb_show', 'wpshortlist_rwmb_show', 10, 2 );
