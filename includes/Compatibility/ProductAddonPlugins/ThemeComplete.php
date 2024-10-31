<?php
/**
 * File responsible for defining compatibility methods for Extra Product Options plugin by ThemeComplete.
 * https://codecanyon.net/item/woocommerce-extra-product-options/7908619
 *
 * Author:          Uriahs Victor
 * Created on:      11/09/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.1.7
 * @package Compatibility
 */

namespace Printus\Compatibility\ProductAddonPlugins;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus\Interfaces\ProductAddonPlugins;
use WC_Order_Item;

/**
 * Class responsible for defining compatibility methods for Extra Product Options plugin by Theme Complete.
 *
 * @link https://codecanyon.net/item/woocommerce-extra-product-options/7908619
 * @package Printus\Compatibility\ProductAddonPlugins
 * @since 1.1.7
 */
class ThemeComplete implements ProductAddonPlugins {

	/**
	 * Get the product options for a line item.
	 *
	 * @param int           $order_id
	 * @param WC_Order_Item $item
	 * @return null|array
	 * @since 1.1.7
	 */
	private static function getProductSelectedAddons( int $order_id, WC_Order_Item $item ): ?array {

		if ( ! function_exists( 'THEMECOMPLETE_EPO_API' ) ) {
			return null;
		}

		$extra_product_options = THEMECOMPLETE_EPO_API()->get_all_options( $order_id );

		return $extra_product_options[ $item->get_id() ] ?? null;
	}

	/**
	 * Add the custom product options to the line item name.
	 *
	 * @param int           $order_id
	 * @param WC_Order_Item $item
	 * @return null|string
	 * @since 1.1.7
	 */
	public static function addProductOptions( int $order_id, WC_Order_Item $item ): ?string {

		$product_selected_addons = self::getProductSelectedAddons( $order_id, $item );

		if ( ! is_array( $product_selected_addons ) || empty( $product_selected_addons ) ) {
			return null;
		}

		$options_concat = '';
		foreach ( $product_selected_addons as $option_key => $option_details ) {
			$addon_name = $option_details['name'] ?? '';
			$value      = $option_details['value'] ?? '';

			$options_concat .= '<span style="font-weight: 700">' . $addon_name . ':</span> ' . $value . '<br/>';
		}

		return apply_filters( 'printus_compatibility__custom_product_options_text', $options_concat, $product_selected_addons, $order_id, $item );
	}
}
