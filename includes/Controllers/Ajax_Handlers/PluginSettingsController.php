<?php
/**
 * File responsible for methods that handle Ajax calls.
 *
 * Author:          Uriahs Victor
 * Created on:      05/03/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Controllers
 */

namespace Printus\Controllers\Ajax_Handlers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus\Controllers\API\User\ResourcesController;
use Printus\Controllers\BaseController;
use Printus\Models\PluginSettings\GeneralSettingsModel;

/**
 * Ajax Handler methods for our custom implemented buttons on our settings page.
 *
 * These handlers are only for custom buttons we add to the page using the WordPress Settings Framework library hooks.
 *
 * @package Printus\Controllers\Ajax_Handlers
 * @since 1.0.0
 */
class PluginSettingsController extends BaseController {

	/**
	 * Handles Ajax request when the refresh printers button is clicked.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function refresh_printers_btn_handler() {

		$printers = ( new ResourcesController() )->get_printers();
		if ( empty( $printers ) ) {
			$msg = __( 'No connected Printers detected. Please make sure you have a printer connected to your PrintNode account.', 'printus-cloud-printing-for-woocommerce' );
			wp_send_json_error( esc_js( $msg ), '404' );
		}

		if ( ! empty( $printers['code'] ) && 'BadRequest' === $printers['code'] ) {
			wp_send_json_error( esc_js( $printers['message'] ), '404' );
		}

		$normalized_printers = array();

		// Allow for setting only the printer ids that should be loaded on the site.
		$restricted_printer_ids = apply_filters( 'printus_printnode_restrict_printer_ids', array() );

		foreach ( $printers as $key => $printer_details ) {
			// If restricted printers have been set and the current printer is not in the restricted list, skip it.
			if ( ! empty( $restricted_printer_ids ) && ! in_array( (int) $printer_details['id'], $restricted_printer_ids, true ) ) {
				continue;
			}
			$normalized_printers[ $key ] = array(
				'id'          => $printer_details['id'],
				'name'        => $printer_details['name'],
				'description' => $printer_details['description'],
			);
		}

		$setting = array(
			'printers' => $normalized_printers,
		);

		GeneralSettingsModel::savePrinters( $setting );
		wp_send_json_success( true );
	}

	/**
	 * Handles Ajax request when the clear fonts cache button is clicked.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function printusClearFontsCacheBtnHandler() {

		$file = PRINTUS_PLUGIN_DIR . 'vendor-prefixed/dompdf/dompdf/lib/fonts/installed-fonts.json';

		if ( file_exists( $file ) ) {
			wp_delete_file( $file );
		}

		wp_send_json_success( true );
	}
}
