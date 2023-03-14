<?php
/**
 * Filter Set Manager
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
 * Class Filter_Set_Manager
 *
 * @since 1.0.0
 */
class Filter_Set_Manager {

	/**
	 * Init
	 */
	public function init() {
		if ( ! get_option( 'wpshortlist_filter_sets' ) ) {
			$this->build();
		}
	}

	/**
	 * Build
	 */
	public function build() {
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';

		$filter_sets = array();
		$taxonomies  = array();

		$wpfsd = new \WP_Filesystem_Direct( false );
		$files = array_filter(
			array_keys( $wpfsd->dirlist( WPSHORTLIST_DATA_DIR, false ) ),
			function( $f ) {
				return 'json' === pathinfo( $f, PATHINFO_EXTENSION );
			}
		);

		foreach ( $files as $file ) {
			$json = $wpfsd->get_contents( WPSHORTLIST_DATA_DIR . $file );

			if ( $json ) {
				$filter_set = json_decode( $json, true );
				if ( is_null( $filter_set ) ) {
					q2( $file, 'Error: invalid JSON' );
				} else {
					// Save filter set.
					$filter_sets[] = $filter_set;

					// Store taxonomy info.
					if ( isset( $filter_set['taxonomy'] ) ) {
						$taxonomy = get_taxonomy( $filter_set['taxonomy'] );
						if ( $taxonomy ) {
							$taxonomies [ $taxonomy->query_var ] = $filter_set['taxonomy'];
						}
					}
				}
			}
		}

		q1( $filter_sets, '', 'o', 'filter-sets.log' );
		update_option( 'wpshortlist_filter_sets', $filter_sets );
	}

	/**
	 * Erase
	 */
	public function erase() {
		delete_option( 'wpshortlist_filter_sets' );
	}

}
