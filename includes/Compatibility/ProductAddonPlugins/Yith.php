<?php
/**
 * File responsible for defining compatibility methods for YITH WooCommerce Product Add-Ons.
 * https://wordpress.org/plugins/yith-woocommerce-product-add-ons/
 *
 * Author:          Uriahs Victor
 * Created on:      04/03/2024 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.2.0
 * @package Compatibility
 */

namespace Printus\Compatibility\ProductAddonPlugins;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus\Helpers\Logger;
use Printus\Interfaces\ProductAddonPlugins;
use WC_Order_Item;

/**
 * Class responsible for defining compatibility methods for Advanced Product Fields for WooCommerce by Studio Wombat.
 *
 * @link https://wordpress.org/plugins/yith-woocommerce-product-add-ons/
 * @package Printus\Compatibility\ProductAddonPlugins
 * @since 1.2.0
 */
class Yith implements ProductAddonPlugins {

	/**
	 * Remove label text that sometimes appears in value text.
	 *
	 *  The metadata from Yith can sometimes look like this:
	 *  ```
	 *  "addon_value" => "Extra Option: My extra option test"
	 *  "display_label" => "Extra Option"
	 *  "display_value" => "Extra Option: My extra option test"
	 *  ```
	 *  So based on our logic below, we would have an outcome like this:
	 *
	 * ```
	 *  "Extra Option" => [
	 *  "label" => "Extra Option"
	 *  "value" => "Extra Option: My extra option test"
	 *  ]
	 * ```
	 *  Setting the `printus_compatibility__remove_yith_label_from_value` filter to true will allow us to have an outcome like this:
	 * ```
	 *  "Extra Option" => [
	 *   "label" => "Extra Option"
	 *   "value" => "My extra option test"
	 *   ]
	 * ```
	 *
	 * @param string $label
	 * @param string $value
	 * @return string
	 * @since 1.2.0
	 */
	private static function removeLabelFromValue( string $label, string $value ): string {
		return ltrim( str_replace( $label, '', $value ), ': ' );
	}

	/**
	 * Get the product options for a line item.
	 *
	 * @param int           $order_id
	 * @param WC_Order_Item $item
	 * @return null|array
	 * @since 1.2.0
	 */
	private static function getProductSelectedAddons( int $order_id, WC_Order_Item $item ): ?array {

		$addons_meta = $item->get_meta( '_ywapo_meta_data' );

		if ( ! is_array( $addons_meta ) && empty( $addons_meta ) ) {
			return null;
		}

		$logger = new Logger();
		// We might not need this code as our previous code might be just fine.
		if ( ! class_exists( 'YITH_WAPO' ) ) {
			$logger->logError( 'YITH_WAPO class not found.' );
			return null;
		}

		if ( ! function_exists( 'yith_wapo_get_option_info' ) ) {
			$logger->logError( 'yith_wapo_get_option_info function not found.' );
			return null;
		}

		$remove_label_from_value = apply_filters( 'printus_compatibility__remove_yith_label_from_value', true );

		$addons = array();
		foreach ( $addons_meta as $index => $addon_index ) {
			foreach ( $addon_index as $addon_data ) {

				$label = $addon_data['display_label'] ?? '';
				$value = $addon_data['display_value'] ?? '';
				$value = ( $remove_label_from_value ) ? self::removeLabelFromValue( $label, $value ) : $value;

				if ( isset( $addons[ $label ] ) ) {
					$addons[ $label ]['value'] .= ", $value";
				} else {
					$addons[ $label ] = array(
						'label' => $label,
						'value' => $value,
					);
				}
			}
		}

		return $addons;
	}

	/**
	 * Add the custom product options to the line item name.
	 *
	 * @param int           $order_id
	 * @param WC_Order_Item $item
	 * @return null|string
	 * @since 1.2.0
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
