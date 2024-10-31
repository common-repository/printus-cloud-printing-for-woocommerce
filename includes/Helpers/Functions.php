<?php
/**
 * File responsible for creating commonly used helper functions.
 *
 * Author:          Uriahs Victor
 * Created on:      05/03/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Helpers
 */

namespace Printus\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus\Controllers\Settings\PaperController;
use Printus\Controllers\Settings\PrintTemplatesController;

/**
 * Class responsible for commonly used methods.
 *
 * @package Printus\Helpers
 * @since 1.0.0
 */
class Functions {

	/**
	 * Method to get settings that are not part of our main settings group created by WordPress Settings Framework.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function getDetachedSettings(): array {
		return get_option( 'printus_detached_settings', array() );
	}

	/**
	 * Get a saved detached setting by key.
	 *
	 * @param string $setting the setting to name retrieve.
	 * @return array
	 * @since 1.0.0
	 */
	public static function getDetachedSetting( string $setting ): array {
		$saved = self::getDetachedSettings();
		return $saved[ $setting ] ?? array();
	}

	/**
	 * Get the printers saved to the DB.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function getSavedPrinters(): array {
		return self::getDetachedSetting( 'printers' );
	}

	/**
	 * Normalize our printers for display in a dropdown.
	 *
	 * @param array $printers The printers to normalize.
	 * @return array
	 * @since 1.0.0
	 */
	public static function normalizePrinters( array $printers ): array {

		$normalized = array();
		foreach ( $printers as $key => $printer_details ) {
			$printer_id  = $printer_details['id'];
			$name        = trim( ( $printer_details['name'] ?? '' ) );
			$description = trim( ( $printer_details['description'] ?? '' ) );
			if ( function_exists( 'mb_strimwidth' ) ) {
				$description = mb_strimwidth( $description, 0, 25, '...' );
			}
			$normalized[ $printer_id ] = $name . ' - ' . $description;
		}

		return $normalized;
	}

	/**
	 * Get normalized saved printers.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function getNormalizedSavedPrinters(): array {
		return self::normalizePrinters( self::getSavedPrinters() );
	}

	/**
	 * Get available templates.
	 *
	 * @return array
	 * @since 1.1.0
	 */
	public static function getAvailableTemplates(): array {
		return PrintTemplatesController::getAvailableTemplates();
	}

	/**
	 * Get available paper sizes.
	 *
	 * @return array
	 * @since 1.1.0
	 */
	public static function getAvailablePaperSizes(): array {
		$available_sizes = PaperController::getAvailablePaperSizes();
		$keys            = array_keys( $available_sizes );
		$names           = array_column( $available_sizes, 'name' );
		$paper_sizes     = array_combine( $keys, $names );
		return $paper_sizes;
	}

	/**
	 * Save a PDF to the uploads directory.
	 *
	 * @param string $stream The PDF stream from DomPDF.
	 * @param int    $order_id The Order ID used in the file name.
	 * @return void
	 * @since 1.2.0
	 */
	public static function savePDF( string $stream, int $order_id ) {

		$label_contents = base64_decode( $stream );

		try {
			$timezone = new \DateTimeZone( wp_timezone_string() );
		} catch ( \Exception $e ) {
			( new Logger() )->logError( 'Failed to initialize timezone class. ' . wp_timezone_string() . ' is not a recognized timezone' );
			$timezone = new \DateTimeZone( 'America\St_Lucia' );
		}

		$month       = date_create( 'now', $timezone )->format( 'Y-M-d' );
		$output_path = WP_CONTENT_DIR . '/uploads/printus-pdfs/' . $month . '/';

		if ( ! is_dir( $output_path ) ) {
			wp_mkdir_p( $output_path );
		}

		if ( is_dir( $output_path ) ) {
			$filename = 'Order-' . $order_id . '.pdf';
			file_put_contents( $output_path . $filename, $label_contents );
		}
	}
}
