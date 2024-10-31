<?php
/**
 * File responsible for defining compatibility methods for WooCommerce Product Addons Ultimate by Plugin Republic.
 * https://pluginrepublic.com/wordpress-plugins/woocommerce-product-add-ons-ultimate/
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
 * Class responsible for defining compatibility methods for WooCommerce Product Addons Ultimate by Plugin Republic.
 *
 * @link https://pluginrepublic.com/wordpress-plugins/woocommerce-product-add-ons-ultimate/
 * @package Printus\Compatibility\ProductAddonPlugins
 * @since 1.1.9
 */
class PluginRepublic implements ProductAddonPlugins {

	/**
	 * Get the product options for a line item.
	 *
	 * @param int           $order_id
	 * @param WC_Order_Item $item
	 * @return null|array
	 * @since 1.1.9
	 */
	private static function getProductSelectedAddons( int $order_id, WC_Order_Item $item ): ?array {

		$addons = $item->get_meta( 'product_extras' )['groups'] ?? '';

		if ( ! is_array( $addons ) && empty( $addons ) ) {
			return null;
		}

		return $addons;
	}

	/**
	 * Get attachments added to order.
	 *
	 * @param array|null $files
	 *
	 * @return string|null
	 * @since 1.2.1
	 */
	private static function getAttachment( ?array $files ): ?string {

		if ( is_null( $files ) ) {
			return null;
		}

		$images = '';
		$other  = '';
		foreach ( $files as $index => $file_details ) {

			if ( strpos( $file_details['type'], 'image' ) !== false ) {
				$images .= '<img src="' . $file_details['url'] . '" width="100px" height="100px" /><br/>';
			}

			if ( strpos( $file_details['type'], 'image' ) === false ) {
				$other .= $file_details['display'];
			}
		}

		return $images . '<br/>' . $other;
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

		foreach ( $product_selected_addons as $group_key => $group_fields ) {

			foreach ( $group_fields as $group_field_key => $group_field_details ) {

				$option_name = $group_field_details['label'] ?? '';
				$value       = $group_field_details['value'] ?? '';

				if ( '__checked__' === $value ) {
					$value = __( 'checked', 'printus-cloud-printing-for-woocommerce' );
				}

				if ( is_array( $value ) ) {
					$value = implode( ', ', $value );
				}

				if ( $group_field_details['type'] === 'upload' ) {
					$attachments = self::getAttachment( ( $group_field_details['files'] ?? null ) );

					if ( ! is_null( $attachments ) ) {
						$value = $attachments;
					}
				}

				$options_concat .= '<span style="font-weight: 700">' . $option_name . ':</span> ' . $value . '<br/>';
			}
		}

		return apply_filters( 'printus_compatibility__custom_product_options_text', $options_concat, $product_selected_addons, $order_id, $item );
	}
}
