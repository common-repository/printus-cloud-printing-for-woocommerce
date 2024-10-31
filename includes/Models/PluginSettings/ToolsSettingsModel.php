<?php
/**
 * File responsible for model methods to do plugin Tools Settings..
 *
 * Author:          Uriahs Victor
 * Created on:      28/11/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.1.9
 * @package Models
 */

namespace Printus\Models\PluginSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus\Models\BaseModel;

/**
 * Class responsible for retrieving tools related settings.
 *
 * @package Printus\Models\PluginSettings
 * @since 1.0.0
 */
class ToolsSettingsModel extends BaseModel {

	/**
	 * The tab id of General settings.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private static $tab_id = 'tools_settings';

	/**
	 * Get the page length fix setting.
	 *
	 * If the option is turned on, add some extra job options to PrintNode API request to fix page length issues
	 * that occurs with some printer models.
	 *
	 * @return bool
	 * @since 1.1.9
	 */
	public static function applyPageLengthFix(): bool {
		$value = self::get_setting( self::$tab_id, 'tools_general_section', 'print-length-fix' );
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}
}
