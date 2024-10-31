<?php
/**
 * Class responsible for orchestrating settings.
 *
 * Author:          Uriahs Victor
 * Created on:      06/02/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Views
 */
namespace Printus\Views\Admin\PluginSettings\Setup;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus_WordPressSettingsFramework;

/**
 * Class responsible for initializing framework.
 *
 * @package Printus\Views\Admin\PluginSettings\Setup
 * @since 1.0.0
 */
class BootstrapSettings {

	/**
	 * @var Printus_WordPressSettingsFramework
	 */
	private $wpsf;

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_filter( 'wpsf_register_settings_printus_config', array( new RenderSettings(), 'render_settings' ) );

		$this->wpsf = new Printus_WordPressSettingsFramework( plugin_dir_path( __FILE__ ) . 'RenderSettings.php', 'printus_config' );
		// Add admin menu
		add_action( 'admin_menu', array( $this, 'addSettingsPage' ), 20 );

		// Add an optional settings validation filter (recommended)
		add_filter( $this->wpsf->get_option_group() . '_settings_validate', array( &$this, 'validateSettings' ) );
	}

	/**
	 * Add settings page.
	 */
	public function addSettingsPage() {
		$this->wpsf->add_settings_page(
			array(
				'parent_slug' => 'sl-plugins-menu',
				'page_title'  => __( 'Configure Settings', 'printus-cloud-printing-for-woocommerce' ),
				'menu_title'  => __( 'Printus Cloud Printing', 'printus-cloud-printing-for-woocommerce' ),
				'capability'  => 'manage_options',
			)
		);
	}

	/**
	 * Validate settings.
	 *
	 * @param $input
	 *
	 * @return array
	 */
	public function validateSettings( $input ): array {
		// Do your settings validation here
		// Same as $sanitize_callback from http://codex.wordpress.org/Function_Reference/register_setting
		$input = $this->sanitizeSettings( $input );
		return $input;
	}

	/**
	 * Sanitize text fields before saving to DB.
	 *
	 * @param array $input
	 * @return array
	 * @since 1.1.0
	 */
	private function sanitizeSettings( array $input ): array {
		array_walk_recursive(
			$input,
			function ( &$value, $key ) {
				$value = sanitize_text_field( $value );
			}
		);
		return $input;
	}
}
