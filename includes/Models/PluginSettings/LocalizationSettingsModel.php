<?php
/**
 * File responsible for model methods to do plugin Localization Settings.
 *
 * Author:          Uriahs Victor
 * Created on:      13/03/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Models
 */

namespace Printus\Models\PluginSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus\Models\BaseModel;

/**
 * Model class for retrieving Localization settings of the plugin.
 *
 * @package Printus\Models\PluginSettings
 * @since 1.0.0
 */
class LocalizationSettingsModel extends BaseModel {

	/**
	 * The tab id of Localization settings.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private static $tab_id = 'localization_settings';

	/**
	 * Get a localization setting option based on it's name.
	 *
	 * @param string $setting_name The setting name to retrieve.
	 * @return mixed
	 * @since 1.0.0
	 */
	public static function getLocalizationStringSetting( string $setting_name ) {
		return self::get_setting( self::$tab_id, 'plugin_strings_section', $setting_name );
	}

	/**
	 * Get the preferred date format.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public static function getPreferredDateFormat() {
		return self::get_setting( self::$tab_id, 'plugin_datetime_format_section', 'date-format' );
	}

	/**
	 * Get the preferred time format.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public static function getPreferredTimeFormat() {
		return self::get_setting( self::$tab_id, 'plugin_datetime_format_section', 'time-format' );
	}
}
