<?php
/**
 * File responsible for defining compatibility methods for Product Addons for Woocommerce by Acowebs.
 * https://wordpress.org/plugins/woo-custom-product-addons/
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

use Printus\Interfaces\ProductAddonPlugins;
use WC_Order_Item;

/**
 * Class responsible for defining compatibility methods for Product Addons for Woocommerce by Acowebs.
 *
 * @link https://wordpress.org/plugins/woo-custom-product-addons/
 * @package Printus\Compatibility\ProductAddonPlugins
 * @since 1.1.7
 */
class Acowebs implements ProductAddonPlugins {

	/**
	 * Get the product options for a line item.
	 *
	 * @param int           $order_id
	 * @param WC_Order_Item $item
	 * @return null|array
	 * @since 1.1.7
	 */
	private static function getProductSelectedAddons( int $order_id, WC_Order_Item $item ): ?array {

		$item_meta = $item->get_meta( '_WCPA_order_meta_data' );
		if ( empty( $item_meta ) ) {
			return null;
		}

		$field_groups = array_column( $item_meta, 'fields' );
		if ( empty( $field_groups ) ) {
			return null;
		}

		$selected_options = array();
		foreach ( $field_groups as $field_group_key => $field_group_value ) {
			foreach ( $field_group_value as $field_key => $field_data ) {
				foreach ( $field_data as $field_data_key => $field_data_value ) {

					$name  = $field_data_value['name'];
					$label = $field_data_value['label'];
					$value = $field_data_value['value'];

					// Some field types come in as arrays while some don't.
					if ( ! is_array( $value ) ) {
						$product_options = array_merge( array( 'title' => $label ), (array) $value );
					} else {
						$product_options = array_column( $value, 'label' );
						$product_options = array_merge( array( 'title' => $label ), $product_options );
					}

					$selected_options[ $name ] = $product_options;
				}
			}
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
