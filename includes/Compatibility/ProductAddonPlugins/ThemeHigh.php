<?php
/**
 * File responsible for defining compatibility methods for Extra product options For WooCommerce plugin by ThemeHigh.
 * https://wordpress.org/plugins/woo-extra-product-options/
 *
 * Author:          Uriahs Victor
 * Created on:      23/09/2023 (d/m/y)
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
 * Class responsible for defining compatibility methods for Extra product options For WooCommerce plugin by ThemeHigh.
 *
 * @link https://wordpress.org/plugins/woo-extra-product-options/
 * @package Printus\Compatibility\ProductAddonPlugins
 * @since 1.1.7
 */
class ThemeHigh implements ProductAddonPlugins {

	/**
	 * Get the product options for a line item.
	 *
	 * @param int           $order_id
	 * @param WC_Order_Item $item
	 * @return null|array
	 * @since 1.1.7
	 */
	private static function getProductSelectedAddons( int $order_id, WC_Order_Item $item ): ?array {

		if ( ! class_exists( 'THWEPOF_Utils' ) ) {
			return null;
		}

		$all_fields = \THWEPOF_Utils::get_product_fields_full();
		if ( ! is_array( $all_fields ) ) {
			return null;
		}

		$selected_options = array();
		foreach ( $all_fields as $field_id => $field_options ) {

			$product_option = wc_get_order_item_meta( $item->get_id(), $field_id );
			if ( empty( $product_option ) ) {
				continue;
			}

			$product_options                        = array_merge( array( 'title' => $field_options->title ), (array) $product_option );
			$selected_options[ $field_options->id ] = $product_options;
		}

		return $selected_options;
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

			$option_name = $option_details['title'] ?? '';
			unset( $option_details['title'] ); // Remove the title so we can implode.

			$options_concat .= '<span style="font-weight: 700">' . $option_name . ':</span> ' . implode( ', ', $option_details ) . '<br/>';
		}

		return apply_filters( 'printus_compatibility__custom_product_options_text', $options_concat, $product_selected_addons, $order_id, $item );
	}
}
