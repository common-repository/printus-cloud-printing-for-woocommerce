<?php
/**
 * File responsible for methods to do with Paper settings.
 *
 * Author:          Uriahs Victor
 * Created on:      08/03/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Controllers
 */

namespace Printus\Controllers\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus\Dompdf\Adapter\CPDF;

/**
 * Class responsible for different plugin paper settings.
 *
 * @package Printus\Controllers\Settings
 * @since 1.0.0
 */
class PaperController {

	/**
	 * Get the possible paper sizes.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function getAvailablePaperSizes(): array {
		$sizes = array(
			'80mm-continuous' => array(
				'name' => __( '80mm Continuous Receipt Paper', 'printus-cloud-printing-for-woocommerce' ),
				'size' => array( 0.0, 0.0, apply_filters( 'printus_template__80mm_width', 226.77 ), apply_filters( 'printus_dompdf__safe_continuous_height', 80 ) ),
			),
			'a4'              => array(
				'name' => 'A4',
				'size' => CPDF::$PAPER_SIZES['a4'],
			),
			'legal'           => array(
				'name' => 'Legal',
				'size' => CPDF::$PAPER_SIZES['legal'],
			),
			'letter'          => array(
				'name' => 'Letter',
				'size' => CPDF::$PAPER_SIZES['letter'],
			),
		);

		/**
		 * Use this filter to add custom paper sizes to the plugin.
		 */
		return apply_filters( 'printus_dompdf__paper_sizes', $sizes );
	}
}
