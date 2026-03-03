<?php
/**
 * Field Registry — stores tab and field definitions registered by plugins.
 *
 * Usage (from any plugin, hooked on `by40q_register_global_settings`):
 *
 *   add_action( 'by40q_register_global_settings', function() {
 *       Field_Registry::register_tab( [ 'key' => 'general', 'label' => 'General', 'order' => 10 ] );
 *       Field_Registry::register_field( [
 *           'key'     => 'site_slogan',
 *           'label'   => 'Site Slogan',
 *           'type'    => 'text',
 *           'tab'     => 'general',
 *           'default' => '',
 *       ] );
 *   } );
 *
 * @package By40Q\GlobalSettings
 */

declare( strict_types=1 );

namespace By40Q\GlobalSettings;

defined( 'ABSPATH' ) || exit;

/**
 * Stores and retrieves registered tabs and fields.
 */
class Field_Registry {

	/** @var array<string, array<string, mixed>> Tabs keyed by tab key. */
	private static array $tabs = array();

	/** @var array<string, array<string, mixed>> Fields keyed by field key. */
	private static array $fields = array();

	/**
	 * Register a tab.
	 *
	 * @param array{key: string, label: string, order?: int} $tab Tab definition.
	 */
	public static function register_tab( array $tab ): void {
		$key = sanitize_key( $tab['key'] ?? '' );
		if ( empty( $key ) ) {
			_doing_it_wrong( __METHOD__, 'Tab must have a non-empty "key".', '1.0.0' );
			return;
		}

		self::$tabs[ $key ] = array(
			'key'   => $key,
			'label' => sanitize_text_field( $tab['label'] ?? $key ),
			'order' => (int) ( $tab['order'] ?? 10 ),
		);
	}

	/**
	 * Register a field.
	 *
	 * @param array<string, mixed> $field Field definition.
	 *   Required keys: key (string), label (string), type (string), tab (string).
	 *   Optional: default (mixed), choices (array, for "select"), description (string).
	 */
	public static function register_field( array $field ): void {
		$key = sanitize_key( $field['key'] ?? '' );
		if ( empty( $key ) ) {
			_doing_it_wrong( __METHOD__, 'Field must have a non-empty "key".', '1.0.0' );
			return;
		}

		$valid_types = array( 'text', 'textarea', 'richtext', 'toggle', 'image', 'url', 'select' );
		$type        = $field['type'] ?? 'text';
		if ( ! in_array( $type, $valid_types, true ) ) {
			_doing_it_wrong(
				__METHOD__,
				sprintf( 'Field type "%s" is not valid. Allowed: %s.', $type, implode( ', ', $valid_types ) ),
				'1.0.0'
			);
			return;
		}

		self::$fields[ $key ] = array(
			'key'         => $key,
			'label'       => sanitize_text_field( $field['label'] ?? $key ),
			'type'        => $type,
			'tab'         => sanitize_key( $field['tab'] ?? 'general' ),
			'default'     => $field['default'] ?? null,
			'description' => sanitize_text_field( $field['description'] ?? '' ),
			'choices'     => is_array( $field['choices'] ?? null ) ? $field['choices'] : array(),
		);
	}

	/**
	 * Return the full schema: tabs (sorted) each containing their fields, with saved values merged in.
	 *
	 * @return array<string, mixed>
	 */
	public static function get_schema(): array {
		$saved_values = self::get_saved_values();

		// Sort tabs by order.
		$tabs = self::$tabs;
		uasort( $tabs, fn( $a, $b ) => $a['order'] <=> $b['order'] );

		$schema = array();
		foreach ( $tabs as $tab_key => $tab ) {
			$tab_fields = array();
			foreach ( self::$fields as $field_key => $field ) {
				if ( $field['tab'] !== $tab_key ) {
					continue;
				}
				$field['value'] = array_key_exists( $field_key, $saved_values )
					? $saved_values[ $field_key ]
					: $field['default'];
				$tab_fields[]   = $field;
			}
			$tab['fields'] = $tab_fields;
			$schema[]      = $tab;
		}

		// Append fields with no matching tab under a virtual "General" tab.
		$orphan_fields = array();
		foreach ( self::$fields as $field_key => $field ) {
			if ( ! isset( self::$tabs[ $field['tab'] ] ) ) {
				$field['value'] = array_key_exists( $field_key, $saved_values )
					? $saved_values[ $field_key ]
					: $field['default'];
				$orphan_fields[] = $field;
			}
		}
		if ( ! empty( $orphan_fields ) ) {
			array_unshift(
				$schema,
				array(
					'key'    => 'general',
					'label'  => 'General',
					'order'  => 0,
					'fields' => $orphan_fields,
				)
			);
		}

		return $schema;
	}

	/**
	 * Return registered fields as a flat associative array keyed by field key (no values).
	 *
	 * @return array<string, array<string, mixed>>
	 */
	public static function get_fields(): array {
		return self::$fields;
	}

	/**
	 * Get raw saved values from wp_options.
	 *
	 * @return array<string, mixed>
	 */
	public static function get_saved_values(): array {
		$option = get_option( 'by40q_global_settings', array() );
		return is_array( $option ) ? $option : array();
	}

	/**
	 * Persist values to wp_options after sanitizing each against its registered type.
	 *
	 * @param array<string, mixed> $values Raw values from REST or form submission.
	 * @return array<string, mixed> Sanitized and saved values.
	 */
	public static function save_values( array $values ): array {
		$sanitized = array();
		foreach ( self::$fields as $field_key => $field ) {
			if ( ! array_key_exists( $field_key, $values ) ) {
				continue;
			}
			$sanitized[ $field_key ] = self::sanitize_value( $values[ $field_key ], $field['type'] );
		}
		update_option( 'by40q_global_settings', $sanitized );
		return $sanitized;
	}

	/**
	 * Sanitize a single value according to field type.
	 *
	 * @param mixed  $value Raw value.
	 * @param string $type  Field type.
	 * @return mixed Sanitized value.
	 */
	private static function sanitize_value( mixed $value, string $type ): mixed {
		return match ( $type ) {
			'text'      => sanitize_text_field( (string) $value ),
			'textarea'  => sanitize_textarea_field( (string) $value ),
			'richtext'  => wp_kses_post( (string) $value ),
			'toggle'    => (bool) $value,
			'image'     => (int) $value, // attachment ID.
			'url'       => esc_url_raw( (string) $value ),
			'select'    => sanitize_text_field( (string) $value ),
			default     => sanitize_text_field( (string) $value ),
		};
	}
}
