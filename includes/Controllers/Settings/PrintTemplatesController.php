<?php
/**
 * File responsible for creating methods to do with controlling template settings.
 *
 * Author:          Uriahs Victor
 * Created on:      09/03/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Controllers
 */

namespace Printus\Controllers\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus\Controllers\BaseController;
use Printus\Helpers\Utilities as UtilitiesHelper;

/**
 * Class responsible for methods to deal with print templates settings.
 *
 * @package Printus\Controllers\Settings
 * @since 1.0.0
 */
class PrintTemplatesController extends BaseController {

	/**
	 * Get all available templates.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function getAvailableTemplates(): array {

		if ( wp_doing_ajax() ) {
			return array();
		}

		$templates = scandir( PRINTUS_TEMPLATES_PATH );

		if ( UtilitiesHelper::usingCustomTemplates() ) {
			$custom_templates = scandir( PRINTUS_CUSTOM_TEMPLATES_PATH );
			if ( is_array( $custom_templates ) ) {
				$templates = array_unique( array_merge( $templates, $custom_templates ) );
			}
			sort( $templates );
		}

		if ( ! is_array( $templates ) ) {
			self::$logger->logWarning( "Templates path did not return an array. Return data: \n\n" . wp_json_encode( $templates ) );
			return array();
		}

		$templates          = array_values( array_diff( $templates, array( '.', '..' ) ) );
		$templates_sans_ext = array_map(
			function ( $template_name ) {
				return explode( '.', $template_name )[0] ?? array();
			},
			$templates
		);

		$template_normalized   = array_combine( $templates_sans_ext, $templates_sans_ext );
		$template_normalized[] = __( 'Select', 'printus-cloud-printing-for-woocommerce' );

		ksort( $template_normalized, SORT_NATURAL );
		return $template_normalized;
	}
}
