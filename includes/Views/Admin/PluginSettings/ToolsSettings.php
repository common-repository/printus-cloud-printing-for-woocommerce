<?php
/**
 * File responsible for methods to do with Tools Settings..
 *
 * Author:          Uriahs Victor
 * Created on:      21/03/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Views
 */

namespace Printus\Views\Admin\PluginSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class responsible for creating tools settings.
 *
 * @package Printus\Views\Admin\PluginSettings
 * @since 1.0.0
 */
class ToolsSettings extends AbstractSettings {

	/**
	 * Create settings tab.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function createTab(): array {
		return array(
			'id'    => 'tools_settings',
			'title' => esc_html__( 'Tools', 'printus-cloud-printing-for-woocommerce' ),
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
				'tab_id'        => 'tools_settings',
				'section_id'    => 'tools_general_section',
				'section_title' => __( 'General', 'printus-cloud-printing-for-woocommerce' ),
				'section_order' => 10,
				'fields'        => $this->createGeneralToolsSettingsSectionFields(),
			),
		);
	}

	/**
	 * Field settings for tools General section.
	 *
	 * @return array
	 * @since 1.1.0
	 */
	private function createGeneralToolsSettingsSectionFields(): array {

		return array(
			array(
				'id'       => 'housekeeping',
				'title'    => __( 'Housekeeping', 'printus-cloud-printing-for-woocommerce' ),
				'subtitle' => __( 'Delete all plugin settings on uninstall.', 'printus-cloud-printing-for-woocommerce' ),
				'type'     => 'toggle',
			),
			array(
				'id'       => 'print-length-fix',
				'title'    => __( 'Print Length Fix', 'printus-cloud-printing-for-woocommerce' ),
				'subtitle' => sprintf( esc_html__( 'Turning on this option will attempt to fix an issue with certain model printers where the length of the print is too long. You need to have Engine 6 selected as the printing backend in the PrintNode client. %1$s Learn more %2$s', 'printus-cloud-printing-for-woocommerce' ), '<a href="https://printus.cloud/docs/tools-settings/#print-length-fix" rel="noreferrer" target="_blank">', '</a>' ),
				'type'     => 'toggle',
			),
			array(
				'id'       => 'clear-fonts-cache',
				'title'    => __( 'Clear Fonts Cache', 'printus-cloud-printing-for-woocommerce' ),
				'subtitle' => __( 'Delete all downloaded custom fonts from Printus cache.', 'printus-cloud-printing-for-woocommerce' ),
			),
		);
	}

	/**
	 * Create a refresh printers button for display.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function createClearFontsCacheButton() {
		?>
		<button style='margin-top: 10px;' class='button-secondary' id='printus-clear-fonts-cache'><?php esc_html_e( 'Clear Cache', 'printus-cloud-printing-for-woocommerce' ); ?></button>
		<?php
	}
}
