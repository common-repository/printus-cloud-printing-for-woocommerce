<?php
/**
 * File responsible for defining compatibility methods for Product Add-Ons by WooCommerce.
 * https://woocommerce.com/products/product-add-ons/
 *
 * Author:          Uriahs Victor
 * Created on:      26/09/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.1.7
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
 * Class responsible for defining compatibility methods for WooCommerce Product Add-ons.
 *
 * @link https://woocommerce.com/products/product-add-ons/
 * @package Printus\Compatibility\ProductAddonPlugins
 * @since 1.1.7
 */
class WooCommerce implements ProductAddonPlugins {

	/**
	 * Get the product options for a line item.
	 *
	 * @param int           $order_id
	 * @param WC_Order_Item $item
	 * @return null|array
	 * @since 1.1.7
	 */
	private static function getProductSelectedAddons( int $order_id, WC_Order_Item $item ): ?array {

		if ( ! class_exists( 'WC_Product_Addons_Helper' ) ) {
			$class_file = WP_PLUGIN_DIR . '/woocommerce-product-addons/includes/class-wc-product-addons-helper.php';
			if ( file_exists( $class_file ) ) {
				require_once $class_file;
			} else {
				return null;
			}
		}

		$wc_product_addons_helper = new \WC_Product_Addons_Helper();

		if ( ! method_exists( $wc_product_addons_helper, 'get_product_addons' ) ) {
			( new Logger() )->logError( 'get_product_addons() method not found inside WC_Product_Addons_Helper Class.' );
			return null;
		}

		$product_id     = $item->get_product_id();
		$product_addons = $wc_product_addons_helper::get_product_addons( $product_id );

		$selected_options = array();
		foreach ( $product_addons as $key => $product_addon_data ) {

			$name      = $product_addon_data['name'];
			$id        = $product_addon_data['id'];
			$item_meta = $item->get_meta( $name, false );

			if ( empty( $item_meta ) ) {
				continue;
			}
			$title = array( 'title' => $name );
			if ( count( $item_meta ) > 1 ) {
				$multi_option = array();
				foreach ( $item_meta as $item_key => $value ) {
					$multi_option[] = $value->get_data( $name )['value'];
				}
				$product_option = array_merge( $title, $multi_option );
			} else {
				$product_option = array_merge( $title, (array) $item->get_meta( $name ) );
			}
			$selected_options[ $id ] = $product_option;
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

			$options_concat .= '<span style="font-weight: 700">' . $option_name . '</span>: ' . implode( ', ', $option_details ) . '<br/>';
		}

		return apply_filters( 'printus_compatibility__custom_product_options_text', $options_concat, $product_selected_addons, $order_id, $item );
	}
}
