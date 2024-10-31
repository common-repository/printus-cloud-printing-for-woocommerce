<?php
/**
 * Plugin Name: WP Settings Framework Example
 * Description: An example of the WP Settings Framework in action.
 * Version: 1.6.0
 * Author: Gilbert Pellegrom
 * Author URI: http://dev7studios.com
 *
 * @package wpsf
 *
 * @license GPL-2.0-or-later
 * Modified by __root__ on 28-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

/**
 * Printus_WPSFTest Class.
 */
class Printus_WPSFTest {
	/**
	 * Plugin path.
	 *
	 * @var string
	 */
	private $plugin_path;

	/**
	 * WordPress Settings Framework instance.
	 *
	 * @var Printus_WordPressSettingsFramework
	 */
	private $wpsf;

	/**
	 * Printus_WPSFTest constructor.
	 */
	public function __construct() {
		$this->plugin_path = plugin_dir_path( __FILE__ );

		// Include and create a new Printus_WordPressSettingsFramework.
		require_once $this->plugin_path . 'wp-settings-framework.php';
		$this->wpsf = new Printus_WordPressSettingsFramework( $this->plugin_path . 'settings/example-settings.php', 'my_example_settings' );

		// Add admin menu.
		add_action( 'admin_menu', array( $this, 'add_settings_page' ), 20 );

		// Add an optional settings validation filter (recommended).
		add_filter( $this->wpsf->get_option_group() . '_settings_validate', array( &$this, 'validate_settings' ) );
	}

	/**
	 * Add settings page.
	 */
	public function add_settings_page() {
		$this->wpsf->add_settings_page(
			array(
				'parent_slug' => 'woocommerce',
				'page_title'  => esc_html__( 'Page Title', 'text-domain' ),
				'menu_title'  => esc_html__( 'menu Title', 'text-domain' ),
				'capability'  => 'manage_woocommerce',
			)
		);
	}

	/**
	 * Validate settings.
	 *
	 * @param mixed $input Input data.
	 *
	 * @return mixed $input
	 */
	public function validate_settings( $input ) {
		// Do your settings validation here
		// Same as $sanitize_callback from http://codex.wordpress.org/Function_Reference/register_setting.
		return $input;
	}
}

$wpsf_test = new Printus_WPSFTest();
