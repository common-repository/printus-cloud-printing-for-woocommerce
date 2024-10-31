<?php
/**
 * File responsible for defining compatibility methods for for Product Addons & Fields for WooCommerce plugin by ThemeIsle.
 * https://wordpress.org/plugins/woocommerce-product-addon/
 *
 * Author:          Uriahs Victor
 * Created on:      01/10/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.1.8
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
 * Class responsible for defining compatibility methods for Product Addons & Fields for WooCommerce plugin by ThemeIsle.
 *
 * @link https://wordpress.org/plugins/woocommerce-product-addon/
 * @package Printus\Compatibility\ProductAddonPlugins
 * @since 1.1.8
 */
class ThemeIsle implements ProductAddonPlugins {

	/**
	 * Get created addons with their price.
	 *
	 * @param array $product_addons
	 * @param array $product_meta
	 * @return array
	 * @since 1.0.10
	 */
	private static function getPricedAddons( array $product_addons, array $product_meta ): array {

		$priced_addons = array();

		foreach ( $product_addons as $key => $addon_value ) {

			foreach ( $product_meta as $addons_key => $addons ) {
				if ( $addons['data_name'] !== $key ) {
					continue;
				}

				if ( ! empty( $addons['options'] ) && is_array( $addons['options'] ) ) {

					foreach ( $addons['options'] as $addon_key => $addon_details ) {
						if ( ! is_array( $addon_value ) && $addon_details['option'] !== $addon_value ) {
							continue;
						}
						if ( is_array( $addon_value ) && ! in_array( $addon_details['option'], $addon_value ) ) {
							continue;
						}

						$priced_addons[ $key ][] = $addon_details['option'] . ' [' . wc_price( $addon_details['price'] ) . ']';
					}
				} else {

					$priced_addons[ $key ][] = $addon_value . ' [' . wc_price( $addons['price'] ) . ']';
				}
			}
		}
		return $priced_addons;
	}

	/**
	 * Get the product options for a line item.
	 *
	 * @param int           $order_id
	 * @param WC_Order_Item $item
	 * @return null|array
	 * @since 1.1.8
	 */
	private static function getProductSelectedAddons( int $order_id, WC_Order_Item $item ): ?array {

		$logger = new Logger();

		if ( ! class_exists( 'PPOM_Meta' ) ) {
			$class_file = WP_PLUGIN_DIR . '/woocommerce-product-addon/classes/ppom.class.php';
			if ( file_exists( $class_file ) ) {
				require_once $class_file;
			} else {
				$logger->logError( 'Failed to include PPOM class' );
				return null;
			}
		}

		$ppom_meta = new \PPOM_Meta();

		if ( ! method_exists( $ppom_meta, 'get_settings_by_id' ) ) {
			$logger->logError( 'get_settings_by_id() method not found inside PPOM_Meta Class.' );
			return null;
		}

		// The product options selected by customer for the order.
		$product_options = wc_get_order_item_meta( $item->get_id(), '_ppom_fields' )['fields'] ?? '';

		if ( empty( $product_options ) ) {
			return null;
		}

		$product_options_meta_id = $product_options['id'];
		unset( $product_options['id'] ); // remove this id after...we won't need it again.

		// Get the custom product option settings attached to this product. We will then use it to get our appropriate labels(Title) for the product options.
		$settings     = $ppom_meta->get_settings_by_id( $product_options_meta_id );
		$product_meta = json_decode( ( $settings->the_meta ?? '' ), true );
		if ( empty( $product_meta ) ) {
			return null;
		}

		$available_product_options = array_column( $product_meta, 'title', 'data_name' );
		if ( empty( $available_product_options ) ) {
			return null;
		}

		// Remove any product options that were not actually used for the order.
		$available_product_options = array_intersect_key( $available_product_options, $product_options );

		$include_price = apply_filters( 'printus_template__include_addon_price', false );

		if ( $include_price ) {
			$product_options = self::getPricedAddons( $product_options, $product_meta );
		}

		$selected_options = array();

		// Alter our array to have the "Title" available inside the array.
		foreach ( $available_product_options as $product_option_key => $product_option_title ) {
			$option                                  = (array) ( $product_options[ $product_option_key ] ?? array() );
			$option                                  = array_merge( array( 'title' => $product_option_title ), $option );
			$selected_options[ $product_option_key ] = $option;
		}

		return $selected_options;
	}

	/**
	 * Add the custom product options to the line item name.
	 *
	 * @param int           $order_id
	 * @param WC_Order_Item $item
	 * @return null|string
	 * @since 1.1.8
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
