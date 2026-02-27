<?php

/**
 * Main Core class file.
 *
 * @package By40Q\Core
 *
 * @since 1.0.0
 */

namespace By40Q\Core;

defined( 'ABSPATH' ) || exit;

final class Core {

	private static ?Core $instance = null;

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
		// Initialization code here
	}
}
