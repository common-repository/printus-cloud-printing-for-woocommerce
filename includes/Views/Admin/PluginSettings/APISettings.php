<?php
/**
 * File responsible for creating API Settings tab and sections.
 *
 * Author:          Uriahs Victor
 * Created on:      07/02/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Views
 */

namespace Printus\Views\Admin\PluginSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * API Settings Class.
 *
 * @package Printus\Views\Admin\PluginSettings
 * @since 1.0.0
 */
class APISettings extends AbstractSettings {

	/**
	 * Create settings tab.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function createTab(): array {
		return array(
			'id'    => 'api_settings',
			'title' => esc_html__( 'API Settings', 'printus-cloud-printing-for-woocommerce' ),
		);
	}

	/**
	 * Create our sections.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function createSections(): array {
		return array(
			array(
				'tab_id'        => 'api_settings',
				'section_id'    => 'api_credentials',
				'section_title' => 'API Credentials',
				'section_order' => 10,
				'fields'        => array(
					array(
						'id'           => 'api_key',
						'title'        => 'API Key',
						'subtitle'     => sprintf( esc_html__( 'Enter the API Key from your PrintNode dashboard. %1$s Learn more %2$s', 'printus-cloud-printing-for-woocommerce' ), '<a href="https://printus.cloud/docs/getting-your-printnode-api-key/" rel="noreferrer" target="_blank">', '</a>' ),
						'type'         => 'password',
						'autocomplete' => 'new-password',
					),
				),
			),
		);
	}
}
