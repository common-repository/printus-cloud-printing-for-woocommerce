<?php
/**
 * File responsible for defining Template interface.
 *
 * Author:          Uriahs Victor
 * Created on:      13/09/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.1.7
 * @package Interfaces
 */

namespace Printus\Interfaces;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WC_Order_Item;

/**
 * Interface for creating a template.
 *
 * @package Printus\Interfaces
 */
interface ProductAddonPlugins {
	/**
	 * Add the custom product options to the line item name.
	 *
	 * @param int           $order_id The order id.
	 * @param WC_Order_Item $item The order item to work on.
	 * @return null|string
	 * @since 1.1.7
	 */
	public static function addProductOptions( int $order_id, WC_Order_Item $item ): ?string;
}
