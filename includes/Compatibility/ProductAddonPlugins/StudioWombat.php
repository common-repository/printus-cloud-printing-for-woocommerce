<?php
/**
 * File responsible for defining compatibility methods for Advanced Product Fields for WooCommerce by Studio Wombat.
 * https://wordpress.org/plugins/advanced-product-fields-for-woocommerce/
 *
 * Author:          Uriahs Victor
 * Created on:      16/11/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.1.9
 * @package Compatibility
 */

namespace Printus\Compatibility\ProductAddonPlugins;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus\Interfaces\ProductAddonPlugins;
use WC_Order_Item;

/**
 * Class responsible for defining compatibility methods for Advanced Product Fields for WooCommerce by Studio Wombat.
 *
 * @link https://wordpress.org/plugins/advanced-product-fields-for-woocommerce/
 * @package Printus\Compatibility\ProductAddonPlugins
 * @since 1.1.9
 */
class StudioWombat implements ProductAddonPlugins {

	/**
	 * Get the product options for a line item.
	 *
	 * @param int           $order_id
	 * @param WC_Order_Item $item
	 * @return null|array
	 * @since 1.1.9
	 */
	private static function getProductSelectedAddons( int $order_id, WC_Order_Item $item ): ?array {

		// Sometimes the Studio Wombat meta comes with a fields array key, and sometimes it doesn't.
		$addons_meta = $item->get_meta( '_wapf_meta' )['fields'] ?? '';

		if ( empty( $addons_meta ) ) {
			$addons_meta = $item->get_meta( '_wapf_meta' );
		}

		if ( ! is_array( $addons_meta ) && empty( $addons_meta ) ) {
			return null;
		}

		$addons = array();
		foreach ( $addons_meta as $field_key => $addon_data ) {
			$label = $addon_data['label'] ?? '';
			// Sometimes the display key is available.
			$value = $addon_data['display'] ?? ( $addon_data['value'] ?? '' );

			$addons[ $label ] = array(
				'label' => $label,
				'value' => $value,
			);
		}

		return $addons;
	}

	/**
	 * Add the custom product options to the line item name.
	 *
	 * @param int           $order_id
	 * @param WC_Order_Item $item
	 * @return null|string
	 * @since 1.1.9
	 */
	public static function addProductOptions( int $order_id, WC_Order_Item $item ): ?string {

		$product_selected_addons = self::getProductSelectedAddons( $order_id, $item );

		if ( ! is_array( $product_selected_addons ) || empty( $product_selected_addons ) ) {
			return null;
		}

		$options_concat = '';

		foreach ( $product_selected_addons as $key => $addon_details ) {
			$addon_name = $addon_details['label'] ?? '';
			$value      = $addon_details['value'] ?? '';

			$options_concat .= '<span style="font-weight: 700">' . $addon_name . ':</span> ' . $value . '<br/>';
		}

		return apply_filters( 'printus_compatibility__custom_product_options_text', $options_concat, $product_selected_addons, $order_id, $item );
	}
}
