<?php
/**
 * WP Shortlist
 *
 * @package     wpshortlist
 * @author      Chris Dillon
 * @copyright   2023 Chris Dillon
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: WP Shortlist
 * Plugin URI:
 * Description:
 * Version: 0.1
 * Author: Chris Dillon
 * Author URI: https://chrisdillon.dev
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Constants.
 */
define( 'WPSHORTLIST_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPSHORTLIST_URL', plugin_dir_url( __FILE__ ) );
define( 'WPSHORTLIST_DATA_DIR', plugin_dir_path( __FILE__ ) . 'data/' );
define( 'WPSHORTLIST_TPL_DIR', plugin_dir_path( __FILE__ ) . 'template-parts/' );

/**
 * Functions.
 */
require_once WPSHORTLIST_DIR . 'inc/core/functions.php';

/**
 * Autoloader.
 *
 * @param string $class_name The class name.
 *
 * Example: from `Shortlist\Core\Run` to `inc\core\class-run.php'.
 */
function wpshortlist_autoloader( $class_name ) {
	$namespace = 'Shortlist';

	if ( 0 === strpos( $class_name, $namespace ) ) {
		// Get our source directory.
		$classes_dir = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR;

		// Remove namespace and trailing slash from class name.
		$class_name = str_replace( $namespace . DIRECTORY_SEPARATOR, '', $class_name );

		// Convert to lower case and replace underscores with dashes.
		$class_name = str_replace( '_', '-', strtolower( $class_name ) );

		// Separate by subdirs.
		$class_parts = explode( DIRECTORY_SEPARATOR, $class_name );

		// Construct file name.
		$file_name = 'class-' . end( $class_parts ) . '.php';

		// Replace file name.
		$class_parts[ key( $class_parts ) ] = $file_name;

		// Assemble relative path.
		$class_file = implode( DIRECTORY_SEPARATOR, $class_parts );

		// Assemble absolute path and verify existence.
		$path = $classes_dir . $class_file;
		if ( file_exists( $path ) ) {
			require_once $path;
		}
	}
}
spl_autoload_register( 'wpshortlist_autoloader' );

/**
 * On plugin activation.
 */
function activate_wpshortlist() {
	Shortlist\Core\Activator::activate();
}
register_activation_hook( __FILE__, 'activate_wpshortlist' );

/**
 * On plugin deactivation.
 */
function deactivate_wpshortlist() {
	Shortlist\Core\Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_wpshortlist' );

/**
 * Execute.
 */
function run_wpshortlist() {
	$plugin = new Shortlist\Core\Run();
}

run_wpshortlist();
