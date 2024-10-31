<?php
/**
 * File responsible for model methods to do plugin General Settings.
 *
 * Author:          Uriahs Victor
 * Created on:      05/03/2023 (d/m/y)
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
 * Class responsible for getting API related settings.
 *
 * @package Printus\Models\API
 */
class GeneralSettingsModel extends BaseModel {

	/**
	 * The tab id of General settings.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private static $tab_id = 'general_settings';

	/**
	 * Save the user connected printers.
	 *
	 * @param array $printers The prints to save.
	 * @return void
	 * @since 1.0.0
	 */
	public static function savePrinters( array $printers ) {
		self::save_detached_setting( $printers );
	}

	/**
	 * Get the printer selected to send prints to.
	 *
	 * @return int
	 * @since 1.0.0
	 */
	public static function getSelectedPrinterId(): int {
		return (int) self::get_setting( self::$tab_id, 'printer_settings_section', 'select-printer' );
	}

	/**
	 * Get the hook the user chose that starts print jobs.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public static function getPrintTriggerHook() {
		return self::get_setting( self::$tab_id, 'plugin_settings_section', 'print-trigger' );
	}

	/**
	 * Name of the store as set by the admin.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public static function getStoreName() {
		return self::get_setting( self::$tab_id, 'plugin_settings_section', 'store-name' );
	}

	/**
	 * Name of the store as set by the admin.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public static function getStorePhoneNumber() {
		return self::get_setting( self::$tab_id, 'plugin_settings_section', 'store-phone-number' );
	}

	/**
	 * Get the setting that determines if the Payment Complete trigger should fire only for successful payments.
	 *
	 * I.e when the status is 'on-hold' or 'pending', and not 'failed' or 'cancelled'.
	 *
	 * @return bool
	 * @since 1.2.3
	 */
	public static function getPaymentsCompleteStatusSetting(): bool {
		$value = self::get_setting( self::$tab_id, 'plugin_settings_section', 'trigger-only-successful-payments' );
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}
}
