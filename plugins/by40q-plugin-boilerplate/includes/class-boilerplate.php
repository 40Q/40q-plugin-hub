<?php

/**
 * Main Boilerplate class file.
 *
 * @package By40Q\Boilerplate
 *
 * @since 1.0.0
 */

namespace By40Q\Boilerplate;

defined( 'ABSPATH' ) || exit;

final class Boilerplate {

	private static ?Boilerplate $instance = null;

	private function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function init(): void {
		add_action( 'enqueue_editor_scripts', array( $this, 'enqueue_editor_scripts' ) );
	}

	/**
	 * Enqueue the React settings bundle on any Global Settings admin page.
	 *
	 */
	public function enqueue_editor_scripts(): void {
		$asset_file = BY40Q_BOILERPLATE_PATH . 'build/scripts/index.asset.php';

		if ( ! file_exists( $asset_file ) ) {
			return;
		}

		$asset = require $asset_file;

		wp_enqueue_script(
			'by40q-global-settings',
			BY40Q_BOILERPLATE_URL . 'build/scripts/index.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);
	}
}
