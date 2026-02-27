<?php

/**
 * Fired during plugin deactivation.
 *
 * @package By40Q\Boilerplate
 * @since   1.0.0
 */

namespace By40Q\Boilerplate;

defined( 'ABSPATH' ) || exit;

class Boilerplate_Deactivator {

	/**
	 * Runs on plugin deactivation.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function deactivate(): void {
		/**
		 * This only required if custom post type has rewrite!
		 */
		flush_rewrite_rules();
	}
}
