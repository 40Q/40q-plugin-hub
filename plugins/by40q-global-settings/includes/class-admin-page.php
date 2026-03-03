<?php
/**
 * Registers the Global Settings admin menu page and enqueues assets.
 *
 * @package By40Q\GlobalSettings
 */

declare( strict_types=1 );

namespace By40Q\GlobalSettings;

defined( 'ABSPATH' ) || exit;

/**
 * Admin Page.
 */
class Admin_Page {

	/**
	 * Register the top-level admin menu item.
	 */
	public function register_menu(): void {
		add_menu_page(
			__( 'Global Settings', 'by40q' ),
			__( 'Global Settings', 'by40q' ),
			'manage_options',
			'by40q-global-settings',
			array( $this, 'render_page' ),
			'dashicons-admin-site-alt3',
			80
		);
	}

	/**
	 * Render the admin page — just the React mount point.
	 */
	public function render_page(): void {
		?>
		<div class="wrap">
			<div id="by40q-global-settings-root"></div>
		</div>
		<?php
	}

	/**
	 * Enqueue the React settings bundle on the Global Settings admin page only.
	 *
	 * @param string $hook_suffix Current admin page hook suffix.
	 */
	public function enqueue_assets( string $hook_suffix ): void {
		if ( 'toplevel_page_by40q-global-settings' !== $hook_suffix ) {
			return;
		}

		$asset_file = BY40Q_GLOBAL_SETTINGS_PATH . 'build/scripts/settings.asset.php';
		if ( ! file_exists( $asset_file ) ) {
			return;
		}

		$asset = require $asset_file;

		wp_enqueue_script(
			'by40q-global-settings',
			BY40Q_GLOBAL_SETTINGS_URL . 'build/scripts/settings.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);

		// Pass REST URL and nonce so the React app can call the API.
		wp_localize_script(
			'by40q-global-settings',
			'by40qGlobalSettings',
			array(
				'restUrl' => rest_url( 'by40q/v1/global-settings' ),
				'nonce'   => wp_create_nonce( 'wp_rest' ),
			)
		);

		// Load the WP media library so ImageField can open the media modal.
		wp_enqueue_media();

		wp_enqueue_style( 'wp-components' );

		// Custom page styles — built from src/js/settings/settings.scss.
		$style_path = BY40Q_GLOBAL_SETTINGS_PATH . 'build/scripts/settings.css';
		if ( file_exists( $style_path ) ) {
			wp_enqueue_style(
				'by40q-global-settings',
				BY40Q_GLOBAL_SETTINGS_URL . 'build/scripts/settings.css',
				array( 'wp-components' ),
				$asset['version']
			);
		}
	}
}
