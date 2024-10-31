<?php
/**
 * Base Controller file.
 *
 * Author:          Uriahs Victor
 * Created on:      02/03/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Controllers
 */

namespace Printus\Controllers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus\Helpers\Logger;

/**
 * Base Controller class.
 *
 * @package Printus\Controllers
 * @since 1.0.0
 */
class BaseController {

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 * @since 1.0.0
	 */
	protected static $logger;

	/**
	 * Base controller class responsible for defining commonly used Controller methods.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function __construct() {
		self::$logger = new Logger();
	}

	/**
	 * Sanitize a piece of data.
	 *
	 * @param mixed $data The data to sanitize.
	 * @return string
	 * @since 1.0.0
	 */
	protected function sanitize( $data ): string {
		return sanitize_text_field( $data );
	}
}
