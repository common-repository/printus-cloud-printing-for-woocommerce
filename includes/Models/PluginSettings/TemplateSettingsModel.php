<?php
/**
 * File responsible for model methods to do plugin Template Settings.
 *
 * Author:          Uriahs Victor
 * Created on:      14/04/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.1.0
 * @package Models
 */

namespace Printus\Models\PluginSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus\Models\BaseModel;

/**
 * Class responsible for retrieving template related settings.
 *
 * @package Printus\Models\PluginSettings
 * @since 1.1.0
 */
class TemplateSettingsModel extends BaseModel {

	/**
	 * The tab id of General settings.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private static $tab_id = 'template_settings';

	/**
	 * Get the template that was selected by the user.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public static function getSelectedTemplateName() {
		return self::get_setting( self::$tab_id, 'template_settings_section', 'print-template' );
	}

	/**
	 * Get the option user selected for paper type selection (whether default or manual).
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public static function getPaperTypeOption() {
		return self::get_setting( self::$tab_id, 'template_settings_section', 'paper-type' );
	}

	/**
	 * Get the size of Paper that the user selected in settings.
	 *
	 * @return mixed
	 */
	public static function getSelectedPaperSize() {
		$paper_option = self::getPaperTypeOption();

		if ( empty( $paper_option ) ) {
			return;
		}

		if ( 'default' === $paper_option ) {
			return self::get_setting( self::$tab_id, 'template_settings_section', 'paper-type-' . $paper_option );
		}

		return array(
			'width'      => self::get_setting( self::$tab_id, 'template_settings_section', 'paper-type-' . $paper_option . '-width' ),
			'height'     => self::get_setting( self::$tab_id, 'template_settings_section', 'paper-type-' . $paper_option . '-height' ),
			'autoheight' => self::get_setting( self::$tab_id, 'template_settings_section', 'paper-type-' . $paper_option . '-autoheight' ),
		);
	}

	/**
	 * Get one of the settings that live in the Template Settings section of Printus configuration page.
	 *
	 * @param string $setting_name The setting name to retrieve.
	 * @return mixed
	 * @since 1.0.0
	 */
	public static function getTemplateSetting( string $setting_name ) {
		return self::get_setting( self::$tab_id, 'template_settings_section', $setting_name );
	}

	/**
	 * Get one of the settings that live in the Product Table Settings section of Printus configuration page.
	 *
	 * @param string $setting_name The setting name to retrieve.
	 * @return mixed
	 * @since 1.1.0
	 */
	public static function getTemplateTableSetting( string $setting_name ) {
		return self::get_setting( self::$tab_id, 'template_table_settings_section', $setting_name );
	}

	/**
	 * Get one of the settings that live in the Misc Text Settings section of Printus configuration page.
	 *
	 * @param string $setting_name The setting name to retrieve.
	 * @return mixed
	 * @since 1.1.0
	 */
	public static function getTemplateMiscSetting( string $setting_name ) {
		return self::get_setting( self::$tab_id, 'template_misc_text_settings_section', $setting_name );
	}
}
