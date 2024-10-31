<?php
/**
 * File responsible for controller methods that handle order statuses.
 * Author:          Uriahs Victor
 * Created on:      22/07/2024 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @package \OrderStatusController
 * @since   1.2.3
 */

namespace Printus\Controllers\WooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus\Controllers\BaseController;
use Printus\Models\PluginSettings\GeneralSettingsModel;

/**
 * Class which defines methods that handle order statuses.
 *
 * @package \Printus\Controllers\WooCommerce\OrderStatusController
 * @since   1.2.3
 */
class OrderStatusController extends BaseController {

	/**
	 * Change the statuses that constitute a completed order.
	 *
	 * This prevents prints for failed or cancelled orders if the user turns on the feature.
	 *
	 * @param $statuses
	 *
	 * @return array
	 * @since 1.2.3
	 */
	public function alterPaymentCompleteStatuses( $statuses ): array {

		$general_settings_model   = new GeneralSettingsModel();
		$only_successful_payments = $general_settings_model::getPaymentsCompleteStatusSetting();

		if ( $only_successful_payments === true ) {
			return array( 'on-hold', 'pending' );
		}

		return $statuses;
	}
}
