<?php
/**
 * Template Settings.
 *
 * Author:          Uriahs Victor
 * Created on:      14/04/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.1.0
 * @package Views
 */

namespace Printus\Views\Admin\PluginSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus\Helpers\Functions as FunctionsHelper;

/**
 * Class to create template settings.
 *
 * @package Printus\Views\Admin\PluginSettings
 * @since 1.1.0
 */
class TemplateSettings extends AbstractSettings {

	private $available_fonts = array();

	/**
	 * Class constructor.
	 *
	 * @return void
	 * @since 1.1.0
	 */
	public function __construct() {
		$available_fonts = array(
			'Courier'       => 'Courier',
			'Helvetica'     => 'Helvetica',
			'Noto Sans'     => 'Noto Sans',
			'Dejavu Sans'   => 'Deja Vu Sans',
			'Titillium Web' => 'Titillium Web',
		);

		$this->available_fonts = apply_filters( 'printus_available_fonts', $available_fonts );
	}

	/**
	 * Create settings tab.
	 *
	 * @return array
	 * @since 1.1.0
	 */
	public function createTab(): array {
		return array(
			'id'    => 'template_settings',
			'title' => esc_html__( 'Template Settings', 'printus-cloud-printing-for-woocommerce' ),
		);
	}

	/**
	 * Create our sections.
	 *
	 * @return array
	 * @since 1.1.0
	 */
	public function createSections(): array {

		return array(
			array(
				'tab_id'        => 'template_settings',
				'section_id'    => 'template_settings_section',
				'section_title' => __( 'Template Settings', 'printus-cloud-printing-for-woocommerce' ),
				'section_order' => 10,
				'fields'        => $this->createPrintTemplateSettingsSectionFields(),
			),
			array(
				'tab_id'        => 'template_settings',
				'section_id'    => 'template_table_settings_section',
				'section_title' => __( 'Product Table Settings', 'printus-cloud-printing-for-woocommerce' ),
				'section_order' => 10,
				'fields'        => $this->createTableTemplateSettingsSectionFields(),
			),
			array(
				'tab_id'        => 'template_settings',
				'section_id'    => 'template_misc_text_settings_section',
				'section_title' => __( 'Misc Text Settings', 'printus-cloud-printing-for-woocommerce' ),
				'section_order' => 10,
				'fields'        => $this->createMiscTextSettingsSectionFields(),
			),
		);
	}

	/**
	 * Field settings for Template sections.
	 *
	 * @return array
	 * @since 1.1.0
	 */
	private function createPrintTemplateSettingsSectionFields(): array {

		$available_print_templates = FunctionsHelper::getAvailableTemplates();
		$available_paper_sizes     = FunctionsHelper::getAvailablePaperSizes();

		return array(
			array(
				'id'       => 'print-template',
				'title'    => __( 'Select Print Template', 'printus-cloud-printing-for-woocommerce' ),
				'desc'     => sprintf( __( 'Get a custom designed template %1$shere%2$s%3$s ', 'printus-cloud-printing-for-woocommerce' ), '<a href="https://printus.cloud/custom-template-service/" rel="noreferrer" target="_blank">', '<span class="dashicons dashicons-external"></span>', '</a>' ),
				'subtitle' => __( 'Nimbus - 80mm size paper, Cumulus - A4 and above', 'printus-cloud-printing-for-woocommerce' ),
				'type'     => 'select',
				'choices'  => $available_print_templates,
			),
			array(
				'id'       => 'paper-type',
				'title'    => 'Paper Type',
				'subtitle' => 'Choose your paper type',
				'type'     => 'select',
				'choices'  => array(
					''        => __( 'Select', 'printus-cloud-printing-for-woocommerce' ),
					'default' => __( 'Default options', 'printus-cloud-printing-for-woocommerce' ),
					'manual'  => __( 'Enter manually', 'printus-cloud-printing-for-woocommerce' ),
				),
			),
			array(
				'id'      => 'paper-type-default',
				'title'   => __( 'Select Size', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'select',
				'choices' => $available_paper_sizes,
				'show_if' => array(
					array(
						'field' => 'template_settings_template_settings_section_paper-type',
						'value' => array( 'default' ),
					),
				),
			),
			array(
				'id'       => 'paper-type-manual-width',
				'title'    => __( 'Paper Width', 'printus-cloud-printing-for-woocommerce' ),
				'subtitle' => __( 'In millimeters' ),
				'type'     => 'text',
				'show_if'  => array(
					array(
						'field' => 'template_settings_template_settings_section_paper-type',
						'value' => array( 'manual' ),
					),
				),
			),
			array(
				'id'       => 'paper-type-manual-height',
				'title'    => __( 'Paper Height', 'printus-cloud-printing-for-woocommerce' ),
				'subtitle' => __( 'In millimeters' ),
				'type'     => 'text',
				'show_if'  => array(
					array(
						'field' => 'template_settings_template_settings_section_paper-type',
						'value' => array( 'manual' ),
					),
				),
			),
			array(
				'id'       => 'paper-type-manual-autoheight',
				'title'    => __( 'Auto-height', 'printus-cloud-printing-for-woocommerce' ),
				'subtitle' => __( 'Attempt to automatically determine the height of the document. Turning on this option will ignore the value in the height field.', 'printus-cloud-printing-for-woocommerce' ),
				'type'     => 'toggle',
				'show_if'  => array(
					array(
						'field' => 'template_settings_template_settings_section_paper-type',
						'value' => array( 'manual' ),
					),
				),
			),
			array(
				'id'    => 'include-currency-symbol',
				'title' => __( 'Include Currency Symbol', 'printus-cloud-printing-for-woocommerce' ),
				'type'  => 'toggle',
			),
			array(
				'id'       => 'include-tax-in-product-price',
				'title'    => __( 'Include Tax in Product Price', 'printus-cloud-printing-for-woocommerce' ),
				'subtitle' => __( 'Enabling this option will show product prices inclusive of tax when printing', 'printus-cloud-printing-for-woocommerce' ),
				'type'     => 'toggle',
			),
		);
	}

	/**
	 * Field settings for miscellaneous text in the template that are not directly linked to the product table.
	 *
	 * @return array
	 * @since 1.1.0
	 */
	private function createMiscTextSettingsSectionFields(): array {
		return array(
			array(
				'id'      => 'misc-headings-font',
				'title'   => __( 'Headings Font', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'select',
				'choices' => $this->available_fonts,
				'default' => 'Helvetica',
			),
			array(
				'id'      => 'misc-headings-font-size',
				'title'   => __( 'Headings Font Size', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'number',
				'default' => '18',
			),
			array(
				'id'      => 'misc-body-font',
				'title'   => __( 'Body Font', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'select',
				'choices' => $this->available_fonts,
				'default' => 'Helvetica',
			),
			array(
				'id'      => 'misc-body-font-size',
				'title'   => __( 'Body Font Size', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'number',
				'default' => '16',
			),
		);
	}

	/**
	 * Field settings for Template sections.
	 *
	 * @return array
	 * @since 1.1.0
	 */
	private function createTableTemplateSettingsSectionFields(): array {

		return array(
			array(
				'id'      => 'table-headings-font',
				'title'   => __( 'Table Headings Font', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'select',
				'choices' => $this->available_fonts,
				'default' => 'Helvetica',
			),
			array(
				'id'      => 'table-headings-font-size',
				'title'   => __( 'Table Headings Font Size', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'number',
				'default' => '18',
			),
			array(
				'id'      => 'table-body-font',
				'title'   => __( 'Table Body Font', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'select',
				'choices' => $this->available_fonts,
				'default' => 'Helvetica',
			),
			array(
				'id'      => 'table-body-font-size',
				'title'   => __( 'Body Font Size', 'printus-cloud-printing-for-woocommerce' ),
				'type'    => 'number',
				'default' => '16',
			),
		);
	}
}
