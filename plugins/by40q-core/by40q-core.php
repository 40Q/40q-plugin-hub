<?php
/**
 * Plugin Name:       40Q Core Plugin
 * Plugin URI:        https://40q.agency
 * Description:       Shared library for all 40Q plugins. Must be active for other 40Q plugins to work.
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      8.2
 * Author:            40Q Agency
 * Author URI:        https://40q.agency
 * License:           GPL-2.0-or-later
 * Text Domain:       by40q
 * Domain Path:       /languages
 *
 * @package By40Q\Core
 */

declare( strict_types=1 );

namespace By40Q\Core;

defined( 'ABSPATH' ) || exit;

// Constants
define( 'BY40Q_CORE_VERSION', '1.0.0' );
define( 'BY40Q_CORE_FILE', __FILE__ );
define( 'BY40Q_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'BY40Q_CORE_URL', plugin_dir_url( __FILE__ ) );

// Composer autoloader
if ( file_exists( BY40Q_CORE_PATH . 'vendor/autoload.php' ) ) {
	require_once BY40Q_CORE_PATH . 'vendor/autoload.php';
}

require_once BY40Q_CORE_PATH . 'includes/class-core-activator.php';
require_once BY40Q_CORE_PATH . 'includes/class-core-deactivator.php';

register_activation_hook( __FILE__, array( 'By40Q\Core\Core_Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'By40Q\Core\Core_Deactivator', 'deactivate' ) );

require_once BY40Q_CORE_PATH . 'includes/class-core.php';

Core::instance();
