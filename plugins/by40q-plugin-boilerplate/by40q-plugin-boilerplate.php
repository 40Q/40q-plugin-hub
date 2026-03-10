<?php

/**
 * Plugin Name:       40Q Boilerplate Plugin
 * Plugin URI:        https://40q.agency
 * Description:       Boilerplate plugin for 40Q plugins. Requires 40Q Core Plugin to be active.
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

namespace By40Q\Boilerplate;

defined( 'ABSPATH' ) || exit;

// Constants.
define( 'BY40Q_BOILERPLATE_VERSION', '1.0.0' );
define( 'BY40Q_BOILERPLATE_FILE', __FILE__ );
define( 'BY40Q_BOILERPLATE_PATH', plugin_dir_path( __FILE__ ) );
define( 'BY40Q_BOILERPLATE_URL', plugin_dir_url( __FILE__ ) );

// Check if 40Q Core Plugin is active.
if ( ! defined( 'BY40Q_AUTONOMY_AI_HUB_VERSION' ) ) {
	add_action(
		'admin_notices',
		function () {
			echo '<div class="notice notice-error"><p><strong>40Q Boilerplate Plugin</strong> requires the <strong>40Q Core Plugin</strong> to be active. Please install and activate it.</p></div>';
		}
	);
	return;
}

// Composer autoloader.
if ( file_exists( BY40Q_BOILERPLATE_PATH . 'vendor/autoload.php' ) ) {
	require_once BY40Q_BOILERPLATE_PATH . 'vendor/autoload.php';
}

require_once BY40Q_BOILERPLATE_PATH . 'includes/class-boilerplate-activator.php';
require_once BY40Q_BOILERPLATE_PATH . 'includes/class-boilerplate-deactivator.php';

register_activation_hook( __FILE__, array( 'By40Q\Boilerplate\Boilerplate_Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'By40Q\Boilerplate\Boilerplate_Deactivator', 'deactivate' ) );

require_once BY40Q_BOILERPLATE_PATH . 'includes/class-boilerplate.php';

Boilerplate::instance();
