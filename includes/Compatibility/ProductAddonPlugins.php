<?php
/**
 * File responsible for defining compatibility logic for product option plugins.
 *
 * Author:          Uriahs Victor
 * Created on:      21/09/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.1.7
 * @package Compatibility
 */

namespace Printus\Compatibility;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus\Compatibility\ProductAddonPlugins\Acowebs;
use Printus\Compatibility\ProductAddonPlugins\PluginRepublic;
use Printus\Compatibility\ProductAddonPlugins\StudioWombat;
use Printus\Compatibility\ProductAddonPlugins\ThemeComplete;
use Printus\Compatibility\ProductAddonPlugins\ThemeHigh;
use Printus\Compatibility\ProductAddonPlugins\ThemeIsle;
use Printus\Compatibility\ProductAddonPlugins\WooCommerce;
use Printus\Compatibility\ProductAddonPlugins\Yith;

/**
 * Class responsible for creating factory method to direct custom product options plugins.
 *
 * @package Printus\Compatibility
 * @since 1.1.7
 */
class ProductAddonPlugins {

	/**
	 * Check if a custom product options plugin is active.
	 *
	 * @param string $init_file
	 * @return bool
	 * @since 1.1.7
	 */
	private function isPluginActive( string $init_file ): bool {
		return is_plugin_active( $init_file );
	}

	/**
	 * Factory method for getting product custom options created by various plugins.
	 *
	 * @return null|object
	 * @since 1.1.7
	 */
	public function getCustomProductAddonsHelperClass(): ?object {

		if ( $this->isPluginActive( 'woocommerce-tm-extra-product-options/tm-woo-extra-product-options.php' ) ) {
			$product_addon_class = new ThemeComplete();
		} elseif ( $this->isPluginActive( 'woo-extra-product-options/woo-extra-product-options.php' ) ) {
			$product_addon_class = new ThemeHigh();
		} elseif (
			$this->isPluginActive( 'woo-custom-product-addons/start.php' ) ||
			$this->isPluginActive( 'woo-custom-product-addons-pro/start.php' )
		) {
			$product_addon_class = new Acowebs();
		} elseif ( $this->isPluginActive( 'woocommerce-product-addons/woocommerce-product-addons.php' ) ) {
			$product_addon_class = new WooCommerce();
		} elseif ( $this->isPluginActive( 'woocommerce-product-addon/woocommerce-product-addon.php' ) ) {
			$product_addon_class = new ThemeIsle();
		} elseif ( $this->isPluginActive( 'product-extras-for-woocommerce/product-extras-for-woocommerce.php' ) ) {
			$product_addon_class = new PluginRepublic();
		} elseif (
			$this->isPluginActive( 'advanced-product-fields-for-woocommerce/advanced-product-fields-for-woocommerce.php' ) ||
			$this->isPluginActive( 'advanced-product-fields-for-woocommerce-pro/advanced-product-fields-for-woocommerce-pro.php' )
		) {
			$product_addon_class = new StudioWombat();
		} elseif (
			$this->isPluginActive( 'yith-woocommerce-product-add-ons/init.php' ) ||
			$this->isPluginActive( 'yith-woocommerce-advanced-product-options-premium/init.php' )
		) {
			$product_addon_class = new Yith();
		}

		if ( empty( $product_addon_class ) ) {
			return null;
		}

		return $product_addon_class;
	}
}
