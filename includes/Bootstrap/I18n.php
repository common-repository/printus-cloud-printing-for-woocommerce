<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://uriahsvictor.com
 * @since      1.0.0
 *
 * @package    Printus
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Printus
 * @author_name     Uriahs Victor <info@soaringleads.com>
 */
namespace Printus\Bootstrap;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Internationalization class.
 *
 * @package Printus\Bootstrap
 * @since 1.0.0
 */
class I18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'printus-cloud-printing-for-woocommerce',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
