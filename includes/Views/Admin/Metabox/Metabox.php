<?php
/**
 * File responsible for methods to create and manipulate plugin metaboxes.
 *
 * Author:          Uriahs Victor
 * Created on:      23/04/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Views
 */

namespace Printus\Views\Admin\Metabox;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus\Helpers\Functions as FunctionsHelper;
use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;

/**
 * Class for methods that create plugin admin metaboxes.
 *
 * @package Printus\Views\Admin\Metabox
 * @since 1.1.0
 */
class Metabox {

	/**
	 * Create the metabox for holding the map view in admin order details.
	 *
	 * @since    1.1.0
	 */
	public function createMetabox() {

		$screen = wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled()
		? wc_get_page_screen_id( 'shop-order' )
		: 'shop_order';

		add_meta_box( 'printus_print_metabox', __( 'Printus - Cloud Printing', 'printus-cloud-printing-for-woocommerce' ), array( $this, 'outputCloudPrintMetabox' ), $screen, 'side', 'high' );
	}

	/**
	 * Output the metabox markup.
	 *
	 * @return void
	 * @since 1.1.0
	 */
	public function outputCloudPrintMetabox() {
		$templates             = FunctionsHelper::getAvailableTemplates();
		$available_paper_sizes = FunctionsHelper::getAvailablePaperSizes();
		$connected_printers    = FunctionsHelper::getNormalizedSavedPrinters();
		?>
		<div style='text-align: center'>
			<label for="printus-template"><strong><?php esc_html_e( 'Select Template', 'printus-cloud-printing-for-woocommerce' ); ?></strong></label>
			<br/>
			<select name="printus-template" id="printus-template">
				<?php
				foreach ( $templates as $template ) {
					echo "<option value='" . esc_attr( $template ) . "'>" . esc_html( $template ) . '</option>';
				}
				?>
			</select>
			<br />
			<br />
			<label for="printus-printer"><strong><?php esc_html_e( 'Select Printer', 'printus-cloud-printing-for-woocommerce' ); ?></strong></label>
			<br/>
			<select name="printus-printer" id="printus-printer">
				<?php
					echo "<option value=''>" . esc_html__( 'Select', 'printus-cloud-printing-for-woocommerce' ) . '</option>';
				foreach ( $connected_printers as $printer_id => $printer_name ) {
					echo "<option value='" . esc_attr( $printer_id ) . "'>" . esc_html( $printer_name ) . '</option>';
				}
				?>
			</select>
			<br />
			<br />
			<label for="printus-template"><strong><?php esc_html_e( 'Select Paper Size', 'printus-cloud-printing-for-woocommerce' ); ?></strong></label>
			<br/>
			<select name="printus-paper-size" id="printus-paper-size">
				<?php
					echo "<option value=''>" . esc_html__( 'Select', 'printus-cloud-printing-for-woocommerce' ) . '</option>';
				foreach ( $available_paper_sizes as $paper_size => $paper_size_name ) {
					echo "<option value='" . esc_attr( $paper_size ) . "'>" . esc_html( $paper_size_name ) . '</option>';
				}
				?>
			</select>
			<br />
			<br />
			<button class='button button-secondary' id='printus-print-order-admin-btn'><?php esc_html_e( 'Print Order', 'printus-cloud-printing-for-woocommerce' ); ?></button>
		</div>
		<?php
		wp_nonce_field( 'printus_print_order_admin', 'print_order_admin_nonce' );
	}
}
