<?php
/**
 * File responsible for API request methods.
 *
 * Author:          Uriahs Victor
 * Created on:      09/03/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Controllers
 */

namespace Printus\Controllers\API;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus\Controllers\BaseController;
use Printus\Models\PluginSettings\ApiSettingsModel as APISettings;

/**
 * Request class for creating methods to deal with API requests.
 *
 * @package Printus\Controllers\API
 * @since 1.0.0
 */
class RequestsController extends BaseController {

	/**
	 * Order API URL.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	private function getApiUrl(): string {
		return 'https://api.printnode.com/';
	}

	/**
	 * Make a POST request to our api.
	 *
	 * @param string $type The type of request to make.
	 * @param string $endpoint The API endpoint to call.
	 * @param array  $data The body data to pass to the API.
	 * @return mixed
	 * @since 1.0.0
	 */
	protected function makePostRequest( string $type, string $endpoint, array $data = array() ) {

		$request_info = compact( 'type', 'endpoint', 'data' );
		do_action( 'printus_before_external_request', $request_info );

		$api_key = ( new APISettings() )->get_api_key();

		if ( empty( $api_key ) ) {
			self::$logger->logError( 'No API key found. Job will be skipped.' );
			return false;
		}

		$url     = $this->getApiUrl() . $endpoint;
		$api_key = base64_encode( $api_key );

		$api_response = wp_remote_request(
			$url,
			array(
				'method'    => $type,
				'sslverify' => false,
				'headers'   => array(
					'Content-Type'  => 'application/x-www-form-urlencoded',
					'Authorization' => "Basic $api_key",
				),
				'body'      => $data,
				'timeout'   => 30,
			)
		);

		// Check to see if the template stream is set. This is what PrintNode uses for the actual print.
		$is_empty_template_stream = empty( $data['content'] );
		unset( $data['content'] ); // We don't need the base64 content in our logging...

		if ( is_wp_error( $api_response ) ) { // Log
			self::$logger->logError(
				'WP Error Request Log: ' . $api_response->get_error_message() . "\n\n" .
				print_r(
					array(
						'type'                     => $type,
						'endpoint'                 => $endpoint,
						'body'                     => $data,
						'is_empty_template_stream' => $is_empty_template_stream,
					),
					true
				)
			);
			return false;
		}

		$response_body         = wp_remote_retrieve_body( $api_response );
		$response_body_decoded = json_decode( $response_body, true );

		if ( apply_filters( 'printus_log_requests', false ) ) {
			self::$logger->logInfo(
				'Request Log: ' . $response_body . "\n\n" .
				print_r(
					array(
						'type'                     => $type,
						'endpoint'                 => $endpoint,
						'body'                     => $data,
						'is_empty_template_stream' => $is_empty_template_stream,
					),
					true
				)
			);
		}

		if ( ( $response_body_decoded['code'] ?? '' ) === 'BadRequest' ) {
			self::$logger->logError(
				$response_body_decoded['message'] ?? 'An error occurred.'
			);
		}

		do_action( 'printus_successful_external_request', $request_info );
		return json_decode( $response_body, true );
	}
}
