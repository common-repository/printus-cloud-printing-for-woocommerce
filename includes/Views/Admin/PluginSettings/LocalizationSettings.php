<?php
/**
 * File responsible for methods to do with Localization Settings.
 *
 * Author:          Uriahs Victor
 * Created on:      13/03/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Views
 */

namespace Printus\Views\Admin\PluginSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use DateTime;

/**
 * Localization Settings class for allowing user to set their own strings.
 *
 * @package Printus\Views\Admin\PluginSettings
 * @since 1.0.0
 */
class LocalizationSettings extends AbstractSettings {

	/**
	 * Create settings tab.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function createTab(): array {
		return array(
			'id'    => 'localization_settings',
			'title' => esc_html__( 'Localization Settings', 'printus-cloud-printing-for-woocommerce' ),
		);
	}


	/**
	 * Create our sections.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function createSections(): array {
		return array(
			array(
				'tab_id'        => 'localization_settings',
				'section_id'    => 'plugin_datetime_format_section',
				'section_title' => __( 'Date and Time Format', 'printus-cloud-printing-for-woocommerce' ),
				'section_order' => 10,
				'fields'        => $this->create_localization_date_time_fields(),
			),
			array(
				'tab_id'        => 'localization_settings',
				'section_id'    => 'plugin_strings_section',
				'section_title' => __( 'Strings', 'printus-cloud-printing-for-woocommerce' ),
				'section_order' => 10,
				'fields'        => $this->create_localization_strings_settings_fields(),
			),
		);
	}

	/**
	 * Create our section data for date and time format settings.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	private function create_localization_date_time_fields(): array {
		$datetime     = new DateTime( 'now', wp_timezone() );
		$date_formats = apply_filters(
			'printus_template__date_formats',
			array(
				'd M Y'  => $datetime->format( 'd M Y' ),
				'dS F Y' => $datetime->format( 'dS F Y' ),
				'd/m/y'  => $datetime->format( 'd/m/y' ) . ' (d/m/y)',
				'd.m.y'  => $datetime->format( 'd.m.y' ),
				'm/d/y'  => $datetime->format( 'm/d/y' ) . ' (m/d/y)',
				'm.d.y'  => $datetime->format( 'm.d.y' ),
			),
			$datetime
		);

		$time_formats = apply_filters(
			'printus_template__time_formats',
			array(
				'h:i A' => $datetime->format( 'h:i A' ),
				'H:i'   => $datetime->format( 'H:i' ),
			),
			$datetime
		);

		return array(
			array(
				'id'       => 'date-format',
				'title'    => __( 'Date format', 'printus-cloud-printing-for-woocommerce' ),
				'subtitle' => __( 'Printus uses the timezone you have set in the your WordPress general settings (Settings->General)', 'printus-cloud-printing-for-woocommerce' ),
				'type'     => 'select',
				'choices'  => $date_formats,
			),
			array(
				'id'      => 'time-format',
				'title'   => __( 'Time format', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'select',
				'choices' => $time_formats,
			),
		);
	}

	/**
	 * Create localization strings fields.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	private function create_localization_strings_settings_fields(): array {

		return array(
			array(
				'id'      => 'item-text',
				'title'   => __( 'Item', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'text',
				'default' => __( 'Item', 'printus-cloud-printing-for-woocommerce' ),
			),
			array(
				'id'      => 'price-text',
				'title'   => __( 'Price', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'text',
				'default' => __( 'Price', 'printus-cloud-printing-for-woocommerce' ),
			),
			array(
				'id'      => 'qty-text',
				'title'   => __( 'Qty', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'text',
				'default' => __( 'Qty', 'printus-cloud-printing-for-woocommerce' ),
			),
			array(
				'id'      => 'invoice-text',
				'title'   => __( 'Invoice', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'text',
				'default' => __( 'Invoice', 'printus-cloud-printing-for-woocommerce' ),
			),
			array(
				'id'      => 'order-text',
				'title'   => __( 'Order', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'text',
				'default' => __( 'Order', 'printus-cloud-printing-for-woocommerce' ),
			),
			array(
				'id'      => 'date-text',
				'title'   => __( 'Date', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'text',
				'default' => __( 'Date', 'printus-cloud-printing-for-woocommerce' ),
			),
			array(
				'id'      => 'summary-text',
				'title'   => __( 'Summary', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'text',
				'default' => __( 'Summary', 'printus-cloud-printing-for-woocommerce' ),
			),
			array(
				'id'      => 'subtotal-text',
				'title'   => __( 'Subtotal', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'text',
				'default' => __( 'Subtotal', 'printus-cloud-printing-for-woocommerce' ),
			),
			array(
				'id'      => 'discount-text',
				'title'   => __( 'Discount', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'text',
				'default' => __( 'Discount', 'printus-cloud-printing-for-woocommerce' ),
			),
			array(
				'id'      => 'total-text',
				'title'   => __( 'Total', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'text',
				'default' => __( 'Total', 'printus-cloud-printing-for-woocommerce' ),
			),
			array(
				'id'      => 'order-total-text',
				'title'   => __( 'Order Total', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'text',
				'default' => __( 'Order Total', 'printus-cloud-printing-for-woocommerce' ),
			),
			array(
				'id'      => 'tax-text',
				'title'   => __( 'Tax', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'text',
				'default' => __( 'Tax', 'printus-cloud-printing-for-woocommerce' ),
			),
			array(
				'id'      => 'shipping-text',
				'title'   => __( 'Shipping', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'text',
				'default' => __( 'Shipping', 'printus-cloud-printing-for-woocommerce' ),
			),
			array(
				'id'      => 'shipping-method-text',
				'title'   => __( 'Shipping Method', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'text',
				'default' => __( 'Shipping Method', 'printus-cloud-printing-for-woocommerce' ),
			),
			array(
				'id'      => 'payment-method-text',
				'title'   => __( 'Payment Method', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'text',
				'default' => __( 'Payment Method', 'printus-cloud-printing-for-woocommerce' ),
			),
			array(
				'id'      => 'order-note-text',
				'title'   => __( 'Order Note', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'text',
				'default' => __( 'Order Note', 'printus-cloud-printing-for-woocommerce' ),
			),
		);
	}
}
