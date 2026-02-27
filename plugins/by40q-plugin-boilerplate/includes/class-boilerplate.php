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
		// Initialization code here
	}
}
