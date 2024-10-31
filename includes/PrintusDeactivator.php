<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://uriahsvictor.com
 * @since      1.0.0
 *
 * @package    Printus
 */

namespace Printus;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Printus
 * @subpackage Printus/includes
 * @author_name     Uriahs Victor <info@soaringleads.com>
 */
class PrintusDeactivator {

	/**
	 * Method fired on plugin deactivation.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
	}
}
