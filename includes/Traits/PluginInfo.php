<?php
/**
 * Trait which holds information about the PRINTUS plugin.
 *
 * Author:          Uriahs Victor
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Notices
 */

namespace Printus\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use DateTime;

/**
* Trait PluginInfo.
*/
trait PluginInfo {

	/**
	 * Returns the number of days since PRINTUS has been installed.
	 *
	 * If the printus_first_install_date option is not found. We return 2 days.
	 *
	 * @since    1.0.0
	 * @return int Days since plugin has been installed.
	 */
	private function get_days_since_installed() {

		// Get the installed date.
		// If option does not exist then set installed date as two days ago.
		$installed_date = get_option( 'printus_first_install_date' );

		if ( ! empty( $installed_date ) ) {
			$installed_date = '@' . $installed_date;
		} else {
			$installed_date = '@' . mktime( 0, 0, 0, date( 'm' ), date( 'd' ) - 2, date( 'Y' ) );
		}

		$installed_date       = new DateTime( $installed_date );
		$today                = new DateTime( 'today' );
		$date_difference      = $installed_date->diff( $today );
		$days_since_installed = $date_difference->format( '%a' );
		return (int) $days_since_installed;
	}

	/**
	 * Get the version PRINTUS was installed at.
	 *
	 * @return mixed
	 */
	private function get_installed_at_version() {
		return get_option( 'printus_installed_at_version' );
	}
}