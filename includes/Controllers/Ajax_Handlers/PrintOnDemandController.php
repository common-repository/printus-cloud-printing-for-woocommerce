<?php
/**
 * File responsible for handing ajax requests from JS.
 *
 * Author:          Uriahs Victor
 * Created on:      27/04/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.1.0
 * @package Controllers
 */

namespace Printus\Controllers\Ajax_Handlers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus\Controllers\API\Order\PrintOrderController;

/**
 * Class responsible for Print on Demand methods.
 *
 * @package Printus\Controllers\Ajax_Handlers
 * @since 1.1.0
 */
class PrintOnDemandController {

	/**
	 * Handles Ajax request when the Print order button is clicked from an admin page.
	 *
	 * @return void
	 * @since 1.1.0
	 */
	public function printusPrintOrderFromAdminBtnHandler() {

		$post_id    = sanitize_text_field( wp_unslash( $_REQUEST['details']['postID'] ?? '' ) );
		$nonce      = sanitize_text_field( wp_unslash( $_REQUEST['details']['nonce'] ?? '' ) );
		$template   = sanitize_text_field( wp_unslash( $_REQUEST['details']['template'] ?? '' ) );
		$paper_size = sanitize_text_field( wp_unslash( $_REQUEST['details']['paperSize'] ?? '' ) );
		$printer_id = (int) sanitize_text_field( wp_unslash( $_REQUEST['details']['printerID'] ?? '' ) );

		if ( empty( $template ) || empty( $paper_size ) || empty( $printer_id ) ) {
			wp_send_json_error( 'Please set all required options to print.', 400 );
		}

		$verified = wp_verify_nonce( $nonce, 'printus_print_order_admin' );

		if ( ! $verified ) {
			wp_send_json_error( 'Invalid nonce', 400 );
		}

		$print_order = new PrintOrderController();
		$print_job   = $print_order->sendPrintJob( $post_id, $printer_id, $template, $paper_size );

		if ( empty( $print_job ) && ! is_integer( $print_job ) ) {
			wp_send_json_error( 'Printjob API request returned a non-integer, job might have not submitted successfully.', 400 );
		}
		wp_send_json_success( true );
	}
}
