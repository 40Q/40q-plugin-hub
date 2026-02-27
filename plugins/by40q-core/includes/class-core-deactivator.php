<?php

/**
 * Fired during plugin deactivation.
 *
 * @package By40Q\Core
 * @since   1.0.0
 */

namespace By40Q\Core;

defined( 'ABSPATH' ) || exit;

class Core_Deactivator {

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
