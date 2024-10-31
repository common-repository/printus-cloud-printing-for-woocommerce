<?php
/**
 * Fired during plugin activation
 *
 * @link       https://uriahsvictor.com
 * @since      1.0.0
 *
 * @package    Printus
 */

namespace Printus;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Printus
 * @subpackage Printus/includes
 * @author_name     Uriahs Victor <info@soaringleads.com>
 */
class PrintusActivator {

	/**
	 * Method fired on plugin activation.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		self::printus_add_default_settings();
	}

	/**
	 * Add our default settings to the site DB.
	 *
	 * @return void
	 */
	private static function printus_add_default_settings() {

		$installed_at = get_option( 'printus_installed_at_version' );
		$install_date = get_option( 'printus_first_install_date' );

		// Create date timestamp when plugin was first installed.
		if ( empty( $install_date ) ) {
			add_option( 'printus_first_install_date', time(), '', 'yes' );
		}

		// Create entry for plugin first install version.
		if ( empty( $installed_at ) ) {
			add_option( 'printus_installed_at_version', PRINTUS_VERSION, '', false );
		}
	}
}
