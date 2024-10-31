<?php
/**
 * Fired by freemius when the plugin is uninstalled.
 *
 * @link       https://uriahsvictor.com
 * @since      1.0.0
 * @package Printus
 */

/**
 * Uninstall class.
 *
 * @since 1.0.0
 */
class Printus_Uninstall {

	/**
	 * Remove plugin settings.
	 *
	 * @since    1.0.0
	 */
	public static function remove_plugin_settings() {

		if ( ! function_exists( 'get_plugins' ) ) {
			include ABSPATH . '/wp-admin/includes/plugin.php';
		}

		$should_delete_settings = get_option( 'printus_config_settings' )['tools_settings_tools_general_section_housekeeping'] ?? '';
		if ( empty( $should_delete_settings ) ) {
			return;
		}

		$option_keys = array(
			'printus_config_settings',
			'printus_detached_settings',
			'printus_first_install_date',
			'printus_installed_at_version',
		);

		foreach ( $option_keys as $key ) {
			delete_option( $key );
		}
	}
}
