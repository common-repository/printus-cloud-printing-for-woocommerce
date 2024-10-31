<?php
/**
 * File responsible for defining up log methods.
 *
 * Author:          Uriahs Victor
 * Created on:      01/10/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.1.8
 * @package Helpers
 */

namespace Printus\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WC_Logger;

/**
 * Log Class.
 *
 * Extendings WC_Logger to add context for plugin.
 *
 * @package Printus\Helpers
 * @since 1.0.0
 */
class Logger extends WC_Logger {

	const CONTEXT = array( 'source' => 'printus' );

	/**
	 * Save a Critical log.
	 *
	 * @param string $msg The error message.
	 * @return void
	 * @since 1.0.0
	 */
	public function logCritical( string $msg ): void {
		$this->critical( $msg, self::CONTEXT );
	}

	/**
	 * Save an Error log.
	 *
	 * @param string $msg The error message.
	 * @return void
	 * @since 1.0.0
	 */
	public function logError( string $msg ): void {
		$this->error( $msg, self::CONTEXT );
	}

	/**
	 * Save a Warning log.
	 *
	 * @param string $msg The error message.
	 * @return void
	 * @since 1.0.0
	 */
	public function logWarning( string $msg ): void {
		$this->warning( $msg, self::CONTEXT );
	}

	/**
	 * Save an Info log.
	 *
	 * @param string $msg The error message.
	 * @return void
	 * @since 1.0.0
	 */
	public function logInfo( string $msg ): void {
		$this->info( $msg, self::CONTEXT );
	}
}
