<?php
/**
 * File responsible for model methods to do plugin API settings.
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
class ApiSettingsModel extends BaseModel {

	/**
	 * The tab id of API settings.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private $tab_id = 'api_settings';

	/**
	 * Get saved API Key.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function get_api_key() {
		return self::get_setting( $this->tab_id, 'api_credentials', 'api_key' );
	}
}
