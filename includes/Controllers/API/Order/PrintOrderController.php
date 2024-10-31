<?php
/**
 * File responsible for printing orders.
 *
 * Author:          Uriahs Victor
 * Created on:      02/03/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Controllers
 */

namespace Printus\Controllers\API\Order;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Automattic\WooCommerce\Utilities\OrderUtil;
use Printus\Dompdf\Dompdf;
use Printus\Controllers\API\RequestsController;
use Printus\Controllers\Settings\PaperController;
use Printus\Helpers\Functions;
use Printus\Helpers\Utilities as UtilitiesHelper;
use Printus\Models\PluginSettings\GeneralSettingsModel;
use Printus\Models\PluginSettings\TemplateSettingsModel;
use Printus\Models\PluginSettings\ToolsSettingsModel;
use Printus\Views\Prints\Template;

/**
 * Class responsible for printing new orders.
 *
 * @package Printus\Controllers\Print
 * @since 1.0.0
 */
class PrintOrderController extends RequestsController {

	/**
	 * DomPdf instance.
	 *
	 * @var Dompdf
	 */
	private $dompdf;

	/**
	 * Class constructor.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();
		$this->dompdf = new Dompdf();
	}

	/**
	 * Whether to save the PDF version of the print.
	 *
	 * @return bool
	 * @since 1.2.0
	 */
	private function savePDF(): bool {
		return apply_filters( 'printus_save_pdf', false );
	}

	/**
	 * Apply autoheight to our PDF.
	 *
	 * @param Dompdf $_dompdf The DomPDF instance.
	 * @param string $html The HTML to be turned into a PDF.
	 * @return Dompdf
	 * @since 1.0.0
	 */
	public static function autoHeightPdf( Dompdf $_dompdf, string $html ): Dompdf {

		try {
			$_dompdf_options           = $_dompdf->getOptions();
			$_dompdf_paper_size        = $_dompdf->getPaperSize();
			$_dompdf_paper_orientation = $_dompdf->getPaperOrientation();

			$GLOBALS['printus_dompdf_body_height'] = 0;
			$_dompdf->setCallbacks(
				array(
					'myCallbacks' => array(
						'event' => 'end_frame',
						'f'     => function ( \Printus\Dompdf\Frame $frame ) {
							if ( strtolower( $frame->get_node()->nodeName ) === 'body' ) {
								$padding_box                              = $frame->get_padding_box();
								$GLOBALS['printus_dompdf_body_height'] += $padding_box['h'];
							}
						},
					),
				)
			);

			$_dompdf->loadHtml( $html );
			$_dompdf->render();
			unset( $_dompdf );

			if ( ! empty( $_dompdf_paper_size ) ) {
				$extra = (int) apply_filters( 'printus_dompdf__body_height_pad', 50 );
				// Be sure to update the correct array element if the page orientation is landscape.
				$index                        = ( $_dompdf_paper_orientation === 'landscape' ) ? 2 : 3;
				$_dompdf_paper_size[ $index ] = $GLOBALS['printus_dompdf_body_height'] + $extra;
			}

			$dompdf = new Dompdf( $_dompdf_options );
			$dompdf->loadHtml( $html );
			$dompdf->setPaper( $_dompdf_paper_size, $_dompdf_paper_orientation );

		} catch ( \Throwable $th ) {
			$dompdf = $_dompdf;
		}
		return $dompdf;
	}


	/**
	 * Get the print's PDF template stream to send to the API.
	 *
	 * Must be a base64_encoded PDF.
	 *
	 * @param string $template_contents The contents to add to the PDF.
	 * @param bool   $preview Whether we should just preview the template instead of printing.
	 * @param string $paper_size The paper size for the print. Whether A4, Letter etc.
	 * @return null|string
	 * @since 1.0.0
	 */
	private function getPrintTemplateStream( string $template_contents, bool $preview = false, string $paper_size = '' ): ?string {

		$this->dompdf->loadHtml( $template_contents );

		$orientation         = apply_filters( 'printus_dompdf__orientation', 'portrait' );
		$selected_paper_size = ( empty( $paper_size ) ) ? TemplateSettingsModel::getSelectedPaperSize() : $paper_size;

		if ( empty( $selected_paper_size ) ) {
			self::$logger->logWarning( 'No paper type selected.' );
			return null;
		}

		$autoheight = false;

		/**
		 * If $size is an array it means that the "Enter manually" paper type option was selected.
		 * Otherwise, it means the "Default options" paper type option was selected.
		 */
		if ( is_array( $selected_paper_size ) ) {
			$width          = ! empty( $selected_paper_size['width'] ) ? $selected_paper_size['width'] : 80;
			$height         = ! empty( $selected_paper_size['height'] ) ? $selected_paper_size['height'] : 80;
			$size_converted = UtilitiesHelper::convertMmToPt( (float) $width, (float) $height );
			$this->dompdf->setPaper( array( 0, 0, $size_converted['width'], $size_converted['height'] ), $orientation );
			$autoheight = (bool) $selected_paper_size['autoheight'];
		} else {
			$paper_sizes      = PaperController::getAvailablePaperSizes();
			$paper_dimensions = $paper_sizes[ $selected_paper_size ]['size'] ?? '';

			if ( empty( $paper_dimensions ) ) {
				self::$logger->logError( 'Could not retrieve the dimensions for the default paper type selected' );
				return null;
			}
			if ( '80mm-continuous' === $selected_paper_size ) { // Always try to autoheight continuous 80mm papers which are most likely receipts
				$autoheight = true;
			}

			$this->dompdf->setPaper( array( 0, 0, $paper_dimensions[2], $paper_dimensions[3] ), $orientation );
		}

		if ( $autoheight ) {
			$this->dompdf = self::autoHeightPdf( $this->dompdf, $template_contents );
		}

		$options = $this->dompdf->getOptions();
		$options = $options->setIsRemoteEnabled( true );
		$this->dompdf->setOptions( $options );
		$this->dompdf->render();
		if ( $preview ) {
			$this->dompdf->stream( 'test.pdf', array( 'Attachment' => false ) );
			exit;
		}

		return base64_encode( $this->dompdf->output() );
	}

	/**
	 * Send a print job to the api.
	 *
	 * @param mixed  $order_id The Order ID.
	 * @param string $printer_id The ID of the printer to print to. This value is passed when on demand printing is used or printer mappings are in use.
	 * @param string $template_name The template to populate. This value is passed when on demand printing is used or printer mappings are in use.
	 * @param string $paper_size The paper size for the print. Whether A4, Letter etc.
	 * @return mixed
	 * @since 1.0.0
	 */
	public function sendPrintJob( $order_id, string $printer_id = '', string $template_name = '', string $paper_size = '' ) {
		// When running this method on 'woocommerce_checkout_order_created' hook, it would result in the order id being an object.
		if ( is_object( $order_id ) ) {
			$order_id = $order_id->get_id();
		}

		if ( 'shop_order' !== OrderUtil::get_order_type( $order_id ) ) {
			return false;
		}

		if ( empty( $printer_id ) ) { // A normal print job where no mappings are taking place.
			$printer_id = GeneralSettingsModel::getSelectedPrinterId();
			if ( empty( $printer_id ) ) {
				return false;
			}
		}

		if ( empty( $template_name ) ) {
			$template_contents = ( new Template() )->prepareTemplate( $order_id, $printer_id ); // A normal print job where no mappings are taking place.
		} else {
			$template_contents = ( new Template() )->prepareTemplate( $order_id, $printer_id, $template_name );
		}

		if ( empty( $template_contents ) ) {
			self::$logger->logWarning( 'Empty print template contents received. Order ID: ' . $order_id );
			return false;
		}

		$content = $this->getPrintTemplateStream( $template_contents, false, $paper_size );
		if ( empty( $content ) ) {
			self::$logger->logError( 'Print template stream return empty. Order ID: ' . $order_id );
			return false;
		}

		$job_data = array(
			'order_id'    => $order_id,
			'printerId'   => $printer_id,
			'contentType' => 'pdf_base64',
			'content'     => $content,
			'title'       => __( 'Order', 'printus-cloud-printing-for-woocommerce' ) . ' #' . $order_id . ' - Printus.cloud',
			'source'      => 'printus',
		);

		if ( ToolsSettingsModel::applyPageLengthFix() ) {
			$job_data['options'] = array(
				'fit_to_page' => 'false',
				'rotate'      => 0,
			);
		}

		$job_data = apply_filters( 'printus__printnode_job_data', $job_data );

		if ( empty( $job_data ) ) { // allow short-circuiting of print job
			return false;
		}

		if ( $this->savePDF() ) {
			Functions::savePDF( $content, $order_id );
		}

		return $this->makePostRequest( 'POST', 'printjobs', $job_data );
	}

	/**
	 * Debugging: display a print job sample print from order edit screen.
	 *
	 * @param int       $order_id The Order ID.
	 * @param \WC_Order $order_object The order object.
	 * @return void
	 * @since 1.0.0
	 */
	public function sendPrintJobAdminDebug( int $order_id, \WC_Order $order_object ): void {

		if ( defined( 'SOARINGSTORES_CORE_DEBUG' ) && SOARINGSTORES_CORE_DEBUG === true ) {
			return;
		}

		if ( 'shop_order' !== OrderUtil::get_order_type( $order_id ) ) {
			return;
		}

		$printer_id = GeneralSettingsModel::getSelectedPrinterId();
		$template   = ( new Template() )->prepareTemplate( $order_id, $printer_id );

		$this->getPrintTemplateStream( $template, true );
	}
}
