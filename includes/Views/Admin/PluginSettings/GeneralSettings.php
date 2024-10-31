<?php
/**
 * File responsible for creating General Settings tab and sections.
 *
 * Author:          Uriahs Victor
 * Created on:      07/02/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Views
 */

namespace Printus\Views\Admin\PluginSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus\Helpers\Functions as FunctionsHelper;

/**
 * General Settings view creation class.
 *
 * @package Printus\Views\Admin\PluginSettings
 * @since 1.0.0
 */
class GeneralSettings extends AbstractSettings {

	/**
	 * Create settings tab.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function createTab(): array {
		return array(
			'id'    => 'general_settings',
			'title' => esc_html__( 'General Settings', 'printus-cloud-printing-for-woocommerce' ),
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
				'tab_id'        => 'general_settings',
				'section_id'    => 'plugin_settings_section',
				'section_title' => __( 'Plugin Setup', 'printus-cloud-printing-for-woocommerce' ),
				'section_order' => 10,
				'fields'        => $this->create_plugin_setup_section_fields(),
			),
			array(
				'tab_id'        => 'general_settings',
				'section_id'    => 'printer_settings_section',
				'section_title' => __( 'Printer Setup', 'printus-cloud-printing-for-woocommerce' ),
				'section_order' => 10,
				'fields'        => $this->create_printer_setup_section_fields(),
			),
		);
	}

	/**
	 * Create our printer setup fields.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	private function create_plugin_setup_section_fields(): array {

		$print_triggers = array(
			'id'       => 'print-trigger',
			'title'    => 'Print Trigger',
			'desc'     => sprintf( __( 'Print jobs are fired automatically based on the option you select here. %1$sLearn more %2$s', 'printus-cloud-printing-for-woocommerce' ), '<a href="https://printus.cloud/docs/general-settings/#print-trigger" rel="noreferrer" target="_blank">', '</a>' ),
			'subtitle' => 'Choose when you\'d like the plugin to print.',
			'type'     => 'select',
			'choices'  => array(
				''                  => __( 'Select', 'printus-cloud-printing-for-woocommerce' ),
				'checkout_complete' => __( 'Checkout complete', 'printus-cloud-printing-for-woocommerce' ),
				'order_complete'    => __( 'Order complete', 'printus-cloud-printing-for-woocommerce' ),
				'payment_complete'  => __( 'Payment complete', 'printus-cloud-printing-for-woocommerce' ),
			),
			'default'  => 'text',
		);

		$print_triggers = apply_filters( 'printus_settings__print_trigger_options', $print_triggers );

		$fields = array(
			$print_triggers,
			array(
				'id'       => 'trigger-only-successful-payments',
				'title'    => __( 'Print Only For Successful Payments', 'printus-cloud-printing-for-woocommerce' ),
				'subtitle' => __( 'When this option is turned on, the plugin will not print if the payment failed or was cancelled.', 'printus-cloud-printing-for-woocommerce' ),
				'type'     => 'toggle',
				'show_if'  => array(
					array(
						'field' => 'general_settings_plugin_settings_section_print-trigger',
						'value' => array( 'payment_complete' ),
					),
				),
			),
			array(
				'id'       => 'store-name',
				'title'    => 'Store Name',
				'desc'     => __( 'Enter the name of your store', 'printus-cloud-printing-for-woocommerce' ),
				'subtitle' => 'This may be included on the print receipts.',
				'type'     => 'text',
				'default'  => get_bloginfo( 'name' ),
			),
			array(
				'id'       => 'store-phone-number',
				'title'    => 'Store Phone Number',
				'desc'     => __( 'Enter the phone number of your store', 'printus-cloud-printing-for-woocommerce' ),
				'subtitle' => 'This may be included on the print receipts.',
				'type'     => 'text',
			),
		);

		if ( pcpfw_fs()->can_use_premium_code() ) {
			$fields[] = array(
				'id'       => 'store-logo',
				'title'    => 'Store Logo',
				'desc'     => __( 'Add Store Logo', 'printus-cloud-printing-for-woocommerce' ),
				'subtitle' => 'This may be included on the print receipts.',
				'type'     => 'file',
			);
		}

		return $fields;
	}

	/**
	 * Create our printer setup fields.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	private function create_printer_setup_section_fields(): array {

		$printers = FunctionsHelper::getNormalizedSavedPrinters();

		if ( empty( $printers ) ) {
			$printers = array(
				'' => __( 'No printers found. Try refreshing.', 'printus-cloud-printing-for-woocommerce' ),
			);
		}

		return array(
			array(
				'id'      => 'select-printer',
				'title'   => __( 'Select Printer', 'printus-cloud-printing-for-woocommerce' ),
				'desc'    => sprintf( __( 'If no printers are found please ensure its connected to your computer then try clicking the %1$sRefresh Printers%2$s button.', 'printus-cloud-printing-for-woocommerce' ), '<strong>', '</strong>' ),
				'type'    => 'select',
				'default' => 'green',
				'choices' => $printers,
			),
		);
	}

	/**
	 * Create a refresh printers button for display.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function createRefreshPrintersButton() {
		?>
		<button style='margin-top: 10px;' class='button-secondary' id='printus-refresh-printers'><?php esc_html_e( 'Refresh Printers', 'printus-cloud-printing-for-woocommerce' ); ?></button>
		<?php
	}
}
