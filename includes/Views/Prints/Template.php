<?php
/**
 * File that creates print templates.
 *
 * Author:          Uriahs Victor
 * Created on:      05/03/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Views
 */

namespace Printus\Views\Prints;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus\Helpers\Utilities as UtilitiesHelper;
use Printus\Helpers\Template as TemplateHelper;
use Printus\Models\PluginSettings\GeneralSettingsModel;
use Printus\Models\PluginSettings\TemplateSettingsModel;
use Printus\Models\PluginSettings\LocalizationSettingsModel;
use Printus\Models\PluginSettings\ToolsSettingsModel;
use WC_Order;

/**
 * Class for orchestrating our templates.
 *
 * @package Printus\Views\Prints
 * @since 1.0.0
 */
class Template {

	/**
	 * Available fonts.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private $fonts;

	/**
	 * Whether to show the currency symbol.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private $show_currency_symbol;

	/**
	 * Whether the user is making use of custom templates.
	 *
	 * @var bool
	 * @since 1.0.0
	 */
	private $using_custom_templates = false;

	/**
	 * The currrent template name being acted upon.
	 *
	 * @var bool
	 * @since 1.1.0
	 */
	private $template_name = '';

	/**
	 * Template class constructor.
	 *
	 * This class orchestrates our templates logic.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->using_custom_templates = UtilitiesHelper::usingCustomTemplates();
		$this->fonts                  = apply_filters( 'printus_template__fonts', '<link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;700&family=Titillium+Web:wght@400;700&display=swap" rel="stylesheet" type="text/css">' );
		$this->show_currency_symbol   = TemplateSettingsModel::getTemplateSetting( 'include-currency-symbol' ) ? '' : 'No';
	}

	/**
	 * Prepare our template with data to be printed.
	 *
	 * @param int    $order_id The order ID.
	 * @param int    $printer_id The printer ID we're retrieving the template for.
	 * @param string $template_name The name of the template to populate. This param is passed when printer mappings are used.
	 * @return string|false
	 * @since 1.0.0
	 */
	public function prepareTemplate( int $order_id, int $printer_id, string $template_name = '' ) {
		$order               = \wc_get_order( $order_id );
		$selected_template   = $template_name ?: TemplateSettingsModel::getSelectedTemplateName();
		$this->template_name = $selected_template;
		return apply_filters( 'printus_template__content', $this->get_template_contents( $selected_template, $order ), $order_id, $printer_id, $this->prepareReplacements( $order ), $selected_template );
	}

	/**
	 * Get the contens for a template given it's name.
	 *
	 * @param string $template_name
	 * @param mixed  $order
	 * @return string|false|void
	 * @since 1.0.0
	 */
	private function get_template_contents( string $template_name, WC_Order $order ) {

		$template_content     = '';
		$template_filter_name = strtolower( $template_name );

		switch ( $template_name ) {
			case 'Nimbus':
				$template_content = $this->nimbusTemplate( $order );
				break;
			case 'Cumulus':
				$template_content = $this->cumulusTemplate( $order );
				break;
			default:
				// If the selected template is not satisfied by one of our cases then its most likely a custom template.
				if ( $this->using_custom_templates ) {
					$custom_template = PRINTUS_CUSTOM_TEMPLATES_PATH . $template_name . '.php';
					if ( file_exists( $custom_template ) ) {
						/**
						 * Get the contents of our custom template.
						 */
						ob_start();
						include $custom_template;
						$template_content = ob_get_clean();
						$replacements     = $this->prepareReplacements( $order );
						$replacements     = apply_filters( "printus_template__{$template_filter_name}_replacements", $replacements, $order, $template_name );
						$template_content = str_replace( array_keys( $replacements ), array_values( $replacements ), $template_content );
					}
				} elseif ( empty( $template_content ) ) {
					$template_content = $this->nimbusTemplate( $order );
				} else {
					$template_content = $this->nimbusTemplate( $order );
				}
				break;
		}

		// Make the template content filterable in case the entire template needs to be recreated.
		return apply_filters( "printus_template__{$template_filter_name}_content", $template_content, $order, $template_name );
	}

	/**
	 * Prepare our replacements for our template magic tags.
	 *
	 * @param WC_Order $order
	 * @param string   $table_headings
	 * @param string   $table_rows
	 * @return array
	 * @since 1.0.0
	 */
	private function prepareReplacements( WC_Order $order, string $table_headings = '', string $table_rows = '' ): array {

		$date_time_ob = $order->get_date_created();
		$date         = wc_format_datetime( $date_time_ob, LocalizationSettingsModel::getPreferredDateFormat() );
		$time         = wc_format_datetime( $date_time_ob, LocalizationSettingsModel::getPreferredTimeFormat() );

		$replacements = array(
			// Misc
			'{order_number}'              => apply_filters( 'printus_template__order_number', '#' . $order->get_order_number() ),
			'{time_now}'                  => $time,
			'{date}'                      => $date,
			'{logo}'                      => '',
			'{extra}'                     => apply_filters( 'printus_template__extra_data', '', $order, $this->template_name ),
			'{powered_by}'                => "<div style='text-align: center !important; width: 100% !important;'><p style='font-size: 12px !important; color: #000 !important'>Powered by Printus<br/>https://printus.cloud</p></div>",
			'{fonts}'                     => $this->fonts,
			'{extra_styles}'              => apply_filters( 'printus_template__extra_styles', '', $order, $this->template_name ),
			'{store_name}'                => apply_filters( 'printus_template__store_name', GeneralSettingsModel::getStoreName(), $order ),
			'{store_address}'             => apply_filters( 'printus_template__store_address', get_option( 'woocommerce_store_address', '' ), $order ),
			'{store_address_2}'           => apply_filters( 'printus_template__store_address_2', get_option( 'woocommerce_store_address_2', '' ), $order ),
			'{store_city}'                => apply_filters( 'printus_template__store_city', get_option( 'woocommerce_store_city', '' ), $order ),
			'{store_postcode}'            => apply_filters( 'printus_template__store_postcode', get_option( 'woocommerce_store_postcode', '' ), $order ),
			'{customer_name}'             => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
			'{customer_address_1}'        => apply_filters( 'printus_template__customer_address_1', $order->get_billing_address_1(), $order ),
			'{customer_address_2}'        => apply_filters( 'printus_template__customer_address_2', $order->get_billing_address_2(), $order ),
			'{customer_city}'             => apply_filters( 'printus_template__customer_city', $order->get_billing_city(), $order ),
			'{customer_postcode}'         => apply_filters( 'printus_template__customer_postcode', $order->get_billing_postcode(), $order ),
			// Table Data and Styles
			'{table_headings}'            => $table_headings,
			'{table_rows}'                => $table_rows,
			'{table_heading_font_family}' => apply_filters( 'printus_template__table_heading_font_family', TemplateSettingsModel::getTemplateTableSetting( 'table-headings-font' ), $order ),
			'{table_heading_font_size}'   => apply_filters( 'printus_template__table_heading_font_size', TemplateSettingsModel::getTemplateTableSetting( 'table-headings-font-size' ) . 'px', $order ),
			'{table_heading_font_weight}' => apply_filters( 'printus_template__table_heading_font_weight', '', $order ),
			'{table_body_font_family}'    => apply_filters( 'printus_template__table_body_font_family', TemplateSettingsModel::getTemplateTableSetting( 'table-body-font' ), $order ),
			'{table_body_font_size}'      => apply_filters( 'printus_template__table_body_font_size', TemplateSettingsModel::getTemplateTableSetting( 'table-body-font-size' ) . 'px', $order ),
			'{table_body_font_weight}'    => apply_filters( 'printus_template__table_body_font_weight', '', $order ),
			'{misc_heading_font_family}'  => apply_filters( 'printus_template__misc_heading_font_family', TemplateSettingsModel::getTemplateMiscSetting( 'misc-headings-font' ), $order ),
			'{misc_heading_font_size}'    => apply_filters( 'printus_template__misc_heading_font_size', TemplateSettingsModel::getTemplateMiscSetting( 'misc-headings-font-size' ) . 'px', $order ),
			'{misc_heading_font_weight}'  => apply_filters( 'printus_template__misc_heading_font_weight', '', $order ),
			'{misc_body_font_family}'     => apply_filters( 'printus_template__misc_body_font_family', TemplateSettingsModel::getTemplateMiscSetting( 'misc-body-font' ), $order ),
			'{misc_body_font_size}'       => apply_filters( 'printus_template__misc_body_font_size', TemplateSettingsModel::getTemplateMiscSetting( 'misc-body-font-size' ) . 'px', $order ),
			'{misc_body_font_weight}'     => apply_filters( 'printus_template__misc_body_font_weight', '', $order ),
			// Values
			'{order_shipping}'            => apply_filters( 'printus_template__shipping_total', wc_price( $order->get_shipping_total(), array( 'currency' => $this->show_currency_symbol ) ), $order ),
			'{order_total}'               => apply_filters( 'printus_template__order_total', wc_price( $order->get_total(), array( 'currency' => $this->show_currency_symbol ) ), $order ),
			'{order_subtotal}'            => apply_filters( 'printus_template__order_subtotal', wc_price( $order->get_subtotal(), array( 'currency' => $this->show_currency_symbol ) ), $order ),
			'{order_tax}'                 => apply_filters( 'printus_template__order_tax', wc_price( $order->get_total_tax(), array( 'currency' => $this->show_currency_symbol ), $order ) ),
			'{order_shipping_method}'     => apply_filters( 'printus_template__shipping_method', $order->get_shipping_method(), $order ),
			'{order_payment_method}'      => apply_filters( 'printus_template__payment_method', $order->get_payment_method_title(), $order ),
			'{store_phone_number}'        => GeneralSettingsModel::getStorePhoneNumber(),
			'{customer_phone_number}'     => apply_filters( 'printus_template__customer_phone_number', $order->get_billing_phone(), $order ),
			// Strings
			'{shipping_text}'             => LocalizationSettingsModel::getLocalizationStringSetting( 'shipping-text' ),
			'{invoice_text}'              => LocalizationSettingsModel::getLocalizationStringSetting( 'invoice-text' ),
			'{order_text}'                => LocalizationSettingsModel::getLocalizationStringSetting( 'order-text' ),
			'{date_text}'                 => LocalizationSettingsModel::getLocalizationStringSetting( 'date-text' ),
			'{summary_text}'              => LocalizationSettingsModel::getLocalizationStringSetting( 'summary-text' ),
			'{subtotal_text}'             => LocalizationSettingsModel::getLocalizationStringSetting( 'subtotal-text' ),
			'{total_text}'                => LocalizationSettingsModel::getLocalizationStringSetting( 'total-text' ),
			'{order_total_text}'          => LocalizationSettingsModel::getLocalizationStringSetting( 'order-total-text' ),
			'{tax_text}'                  => LocalizationSettingsModel::getLocalizationStringSetting( 'tax-text' ),
			'{shipping_method_text}'      => LocalizationSettingsModel::getLocalizationStringSetting( 'shipping-method-text' ),
			'{payment_method_text}'       => LocalizationSettingsModel::getLocalizationStringSetting( 'payment-method-text' ),
		);

		if ( ! empty( $discount_total = $order->get_total_discount() ) ) {

			$discount_text   = LocalizationSettingsModel::getLocalizationStringSetting( 'discount-text' );
			$discount_amount = apply_filters( 'printus_template__order_discount', '-' . wc_price( $discount_total, array( 'currency' => $this->show_currency_symbol ) ), $order );

			$replacements['{order_discount_markup_nimbus}'] = "
                <tr>
					<td class='text-bold text-left'><span class='heading'>{$discount_text}</span></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class='text-bold text-center'><span class='summary'>{$discount_amount}</span></td>
				</tr>
            ";

			$replacements['{order_discount_markup_cumulus}'] = "
                <tr>
                    <td>{$discount_text}</td>
                    <td style='text-align: right'>{$discount_amount}</td>
				</tr>
            ";

		} else {
			$replacements['{order_discount_markup_nimbus}']  = '';
			$replacements['{order_discount_markup_cumulus}'] = '';
		}

		if ( ! empty( $order_note = $order->get_customer_note() ) ) {

			$order_note_text              = LocalizationSettingsModel::getLocalizationStringSetting( 'order-note-text' );
			$replacements['{order_note}'] = "
                <p><span style='font-weight: 700'>{$order_note_text}</span><br/><br/>{$order_note}</p>
            ";

		} else {
			$replacements['{order_note}'] = '';
		}

		// Applying the page length fix cuts the template off on the right. With this code
		// we're pushing it a bit to the left by setting a right margin.
		if ( ToolsSettingsModel::applyPageLengthFix() === true ) {
			$replacements['{nimbus_page_margins}'] = '
                @page {
                    margin: 0 25px 0 0 !important;
                    padding: 0 !important;
                    width: 80% !important;
                }
            ';
		} else {
			$replacements['{nimbus_page_margins}'] = '
               @page {
			        margin: 0 10px !important;
			        padding: 0 !important;
		        }
            ';
		}

		if ( pcpfw_fs()->can_use_premium_code() ) {
			$logo_url = \Printus\Pro\Models\PluginSettings\General::getStoreLogo();

			switch ( $this->template_name ) {
				case 'Nimbus':
					$logo_size = array(
						'width'  => '100px',
						'height' => '50px',
					);
					break;
				case 'Cumulus':
					$logo_size = array(
						'width'  => '250px',
						'height' => '120px',
					);
					break;
				default:
					$logo_size = array(
						'width'  => '100px',
						'height' => '50px',
					);
					break;
			}

			$size = apply_filters(
				'printus_template__logo_size',
				$logo_size,
				$this->template_name
			);

			$replacements['{logo}']       = "<img src='" . esc_attr( $logo_url ) . "' width='" . esc_attr( $size['width'] ) . "' height='" . esc_attr( $size['height'] ) . "'/>";
			$replacements['{powered_by}'] = '';
		}

		return apply_filters( 'printus_template__replacements', $replacements, $order );
	}

	/**
	 * Basic template.
	 *
	 * @param WC_Order $order
	 * @return string
	 */
	private function nimbusTemplate( WC_Order $order ): string {

		$template = __DIR__ . '/Templates/Nimbus.php';

		if ( $this->using_custom_templates ) {
			$custom_template = PRINTUS_CUSTOM_TEMPLATES_PATH . 'Nimbus.php';
			if ( file_exists( $custom_template ) ) {
				$template = $custom_template;
			}
		}

		ob_start();
		include $template;
		$contents = ob_get_clean();

		$columns = array(
			'item-text',
			'price-text',
			'qty-text',
			'total-text',
		);

		$headings_colspans = array(
			'item-text' => 3,
		);

		$rows_colspans = array(
			'item-text' => 3,
		);

		$headings     = TemplateHelper::createHeadings( $columns, $headings_colspans );
		$rows         = TemplateHelper::createRows( $order, $columns, $rows_colspans );
		$replacements = $this->prepareReplacements( $order, $headings, $rows );
		$replacements = apply_filters( 'printus_template__nimbus_replacements', $replacements, $order );

		return str_replace( array_keys( $replacements ), array_values( $replacements ), $contents );
	}

	/**
	 * Cumulus template.
	 *
	 * @param WC_Order $order
	 * @return string
	 * @since 1.1.0
	 */
	private function cumulusTemplate( WC_Order $order ): string {

		$template = __DIR__ . '/Templates/Cumulus.php';

		if ( $this->using_custom_templates ) {
			$custom_template = PRINTUS_CUSTOM_TEMPLATES_PATH . 'Cumulus.php';
			if ( file_exists( $custom_template ) ) {
				$template = $custom_template;
			}
		}

		ob_start();
		include $template;
		$contents = ob_get_clean();

		$columns = array(
			'item-text',
			'price-text',
			'qty-text',
			'total-text',
		);

		$headings_colspans = array(
			'item-text' => 3,
		);

		$rows_colspans = array(
			'item-text' => 3,
		);

		$headings     = TemplateHelper::createHeadings( $columns, $headings_colspans );
		$rows         = TemplateHelper::createRows( $order, $columns, $rows_colspans );
		$replacements = $this->prepareReplacements( $order, $headings, $rows );
		$replacements = apply_filters( 'printus_template__cumulus_replacements', $replacements, $order );

		return str_replace( array_keys( $replacements ), array_values( $replacements ), $contents );
	}
}
