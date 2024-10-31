<?php
/**
 * File responsible for methods to do with User Printers.
 *
 * Author:          Uriahs Victor
 * Created on:      05/03/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package package
 */

namespace Printus\Controllers\API\User;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus\Controllers\API\RequestsController;

/**
 * Class for methods to get different user resources;
 *
 * @package Printus\Controllers\API\User
 * @since 1.0.0
 */
class ResourcesController extends RequestsController {

	/**
	 * Get available printers for the user.
	 *
	 * @since 1.0.0
	 * @return mixed
	 */
	public function get_printers() {
		return $this->makePostRequest( 'GET', 'printers' );
	}
}
