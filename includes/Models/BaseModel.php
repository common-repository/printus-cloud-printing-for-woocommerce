<?php
/**
 * File responsible for Base Model methods..
 *
 * Author:          Uriahs Victor
 * Created on:      05/03/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Model
 */

namespace Printus\Models;

use Printus\Helpers\Functions as FunctionsHelper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Base model class.
 *
 * @package Printus\Models
 * @since 1.0.0
 */
class BaseModel {

	/**
	 * The option group that holds our settings.
	 *
	 * @see Printus\Views\Admin\Plugin_Settings\Setup\BootstrapSettings::__construct();
	 * @var string
	 */
	private static $option_group = 'printus_config';

	/**
	 * Get a setting
	 *
	 * @param string $tab_id The ID of the settings tab the option belongs to.
	 * @param string $section_id The ID of the section the option falls inside.
	 * @param string $field_id The field to retrieve.
	 * @return mixed
	 * @since 1.0.0
	 */
	public static function get_setting( string $tab_id, string $section_id, string $field_id ) {
		// Autoload the class if it's not present, this is needed for frontend calls to the wpsf_get_setting function.
		class_exists( 'Printus_WordPressSettingsFramework' );
		return \wpsf_get_setting( self::$option_group, $tab_id . '_' . $section_id, $field_id );
	}

	/**
	 * Get all our settings.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	protected function get_all_settings(): array {
		return get_option( 'printus_config_settings', array() );
	}

	/**
	 * Method to save settings that are not part of our main settings group created by WordPress Settings Framework.
	 *
	 * @param mixed $setting The setting name to save.
	 * @return void
	 * @since 1.0.0
	 */
	protected static function save_detached_setting( $setting ) {
		$saved   = FunctionsHelper::getDetachedSettings();
		$updated = array_merge( $saved, $setting );
		update_option( 'printus_detached_settings', $updated );
	}
}
