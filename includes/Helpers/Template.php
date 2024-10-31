<?php
/**
 * File responsible for defining helper methods for creating print templates.
 *
 * Author:          Uriahs Victor
 * Created on:      06/08/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.1.4
 * @package package
 */

namespace Printus\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus\Compatibility\ProductAddonPlugins as ProductAddonPluginsCompatibility;
use Printus\Models\PluginSettings\LocalizationSettingsModel;
use Printus\Models\PluginSettings\TemplateSettingsModel;
use WC_Order;
use WC_Order_Item;

/**
 * Helper Template Class.
 *
 * Helper methods for template creation.
 *
 * @package Printus\Helpers
 * @since 1.1.4
 */
class Template {

	/**
	 * Create table headings.
	 *
	 * @param array $headings The table headings.
	 * @param array $colspans Colspans that should be applied on Headings if any.
	 * @return string
	 * @since 1.1.0
	 */
	public static function createHeadings( array $headings, array $colspans = array() ): string {
		$table_headings = array();

		foreach ( $headings as $heading ) {
			$localized_heading = LocalizationSettingsModel::getLocalizationStringSetting( $heading );
			/**
			 * If no localization translation if found for the heading column then leave heading as.
			 * This would most commonly happen in custom templates when creating custom columns.
			 */
			$table_headings[] = $localized_heading ?: $heading;
		}

		$heading_colspans = array();
		foreach ( $colspans as $column_name => $colspan_value ) {
			$localized_column_name                      = LocalizationSettingsModel::getLocalizationStringSetting( $column_name );
			$heading_colspans[ $localized_column_name ] = $colspan_value;
		}

		$table_headings_str = '';
		foreach ( $table_headings as $key => $heading ) {
			$colspan = $heading_colspans[ $heading ] ?? 1;
			if ( 0 === $key ) {
				$table_headings_str .= "<th class='text-left text-top heading' colspan='$colspan'>" . $heading . '</th>';
			} else {
				$table_headings_str .= "<th class='text-center text-top heading' colspan='$colspan'>" . $heading . '</th>';
			}
		}

		return $table_headings_str;
	}

	/**
	 * Format the item addons to be appended onto the item name.
	 *
	 * @param WC_Order_Item $item
	 * @param WC_Order      $order
	 * @return string
	 * @since 1.1.10
	 */
	private static function generateItemAddons( WC_Order_Item $item, WC_Order $order ): string {

		$order_id            = $order->get_id();
		$product_addon_class = ( new ProductAddonPluginsCompatibility() )->getCustomProductAddonsHelperClass();

		// Regular WC variations.
		$wc_variations               = '';
		$include_formatted_variation = apply_filters( 'printus_include_formatted_variation', true );
		if ( $item->get_variation_id() && $include_formatted_variation === true ) { // Sometimes the product title already has the variation included.
			/** @var \WC_Product $product WC_Product */
			$product = $item->get_product();

			$variations    = wc_get_formatted_variation( $product, true );
			$wc_variations = implode( '<br/>', explode( ',', $variations ) ) . '<br/>';
		}

		$addon_variations = '';
		if ( ! is_null( $product_addon_class ) ) {
			// Product Addon variations.
			$addon_variations = $product_addon_class::addProductOptions( $order_id, $item ) ?: '';
		}

		if ( empty( $wc_variations ) && empty( $addon_variations ) ) {
			return '';
		}

		return '<div style="margin-left: 8px; margin-top: 10px">' . $wc_variations . $addon_variations . '</div>';
	}

	/**
	 * Create rows given a list of columns.
	 *
	 * @param WC_Order $order The WooCommerce Order.
	 * @param array    $columns The array of columns to create rows for.
	 * @param array    $rows_colspans Colspan that should be applied to the row cell if any.
	 * @return string
	 * @since 1.1.0
	 */
	public static function createRows( WC_Order $order, array $columns, array $rows_colspans = array() ): string {

		$table_rows = array();

		$show_currency_symbol         = TemplateSettingsModel::getTemplateSetting( 'include-currency-symbol' ) ? '' : 'No';
		$include_tax_in_product_price = (bool) TemplateSettingsModel::getTemplateSetting( 'include-tax-in-product-price' );

		foreach ( $order->get_items() as $item_id => $item ) {

			$table_rows[] = '<tr>';

			// Append addons/variations to item name.
			$filtered_ordered_item = apply_filters( 'printus_template__order_item', '', $item, $order );
			$ordered_item          = $item->get_name() . $filtered_ordered_item . self::generateItemAddons( $item, $order );

			$price    = apply_filters( 'printus_template__item_price', $order->get_item_subtotal( $item, $include_tax_in_product_price ), $item, $order );
			$item_qty = (int) apply_filters( 'printus_template__item_qty', $item->get_quantity(), $item, $order );
			$total    = apply_filters( 'printus_template__item_total', $price * $item_qty, $item, $order );

			foreach ( $columns as $row_item ) {

				$colspan = $rows_colspans[ $row_item ] ?? 1;

				if ( 'item-text' === $row_item ) {
					$table_rows[] = "<td class='text-left text-top item-name' style='word-wrap: break-word' colspan='$colspan'>" . $ordered_item . '</td>';
				}

				if ( 'price-text' === $row_item ) {
					$item_price   = wc_price( $price, array( 'currency' => $show_currency_symbol ) );
					$table_rows[] = "<td class='text-center text-top item-price' colspan='$colspan'>" . $item_price . '</td>';
				}

				if ( 'qty-text' === $row_item ) {
					$table_rows[] = "<td class='text-top item-quantity' colspan='$colspan'>" . $item_qty . '</td>';
				}

				if ( 'total-text' === $row_item ) {
					$item_total   = wc_price( $total, array( 'currency' => $show_currency_symbol ) );
					$table_rows[] = "<td class='text-center text-top item-total' colspan='$colspan'>" . $item_total . '</td>';
				}

				$table_rows = apply_filters( 'printus_template__rows', $table_rows, $row_item, $item, $order );
			}

			$table_rows[] = '</tr>';
		}

		return implode( '', $table_rows );
	}
}
