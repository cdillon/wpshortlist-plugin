<?php
/**
 * Test
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
 * Class Test
 *
 * @since 1.0.0
 */
class Test {

	/**
	 * Constructor
	 */
	public function __construct() {
	}

	/**
	 * Init
	 */
	public function init() {
		add_action(
			'kadence_before_header',
			function() {
				global $wp_query;
				$q = $wp_query->query;
				echo '<pre>';
				print_r( $q );
				echo '</pre>';
			}
		);
	}

}
