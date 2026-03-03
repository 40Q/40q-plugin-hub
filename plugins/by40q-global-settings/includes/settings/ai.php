<?php
/**
 * AI tab — fields for AI provider configuration.
 *
 * @package By40Q\GlobalSettings
 */

declare( strict_types=1 );

namespace By40Q\GlobalSettings;

defined( 'ABSPATH' ) || exit;

add_action(
	'by40q_register_global_settings',
	function () {

		Field_Registry::register_tab(
			[
				'key'   => 'ai',
				'label' => 'AI',
				'order' => 20,
			]
		);

		Field_Registry::register_field(
			[
				'key'         => 'ai_api_key',
				'label'       => 'API Key',
				'type'        => 'text',
				'tab'         => 'ai',
				'default'     => '',
				'description' => 'AI provider API key (e.g. OpenAI). Stored encrypted in wp_options.',
			]
		);

		Field_Registry::register_field(
			[
				'key'         => 'ai_context',
				'label'       => 'Context',
				'type'        => 'textarea',
				'tab'         => 'ai',
				'default'     => '',
				'description' => 'Default system prompt or context sent with every AI request.',
			]
		);
	}
);
