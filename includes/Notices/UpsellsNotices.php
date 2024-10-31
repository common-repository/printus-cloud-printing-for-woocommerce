<?php
/**
 * Class responsible for upsell notices.
 *
 * Author:          Uriahs Victor
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Notices
 */

namespace Printus\Notices;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus\Notices\Notice;
use Printus\Traits\PluginInfo;

/**
 * Class Upsells_Notices.
 */
class UpsellsNotices extends Notice {

	use PluginInfo;

	/**
	 * Class constructor
	 *
	 * @return void
	 */
	public function __construct() {
	}

	/**
	 * Create initial pro released notice.
	 *
	 * @return void
	 */
	public function create_pro_notice() {

		$days_since_installed = $this->get_days_since_installed();

		// Show notice after 4 days.
		if ( $days_since_installed < 3 ) {
			return;
		}

		$content = array(
			'title' => __( 'Get PRO', 'printus-cloud-printing-for-woocommerce' ),
			'body'  => __( 'Get the PRO version of Printus for extended capabilities.', 'printus-cloud-printing-for-woocommerce' ),
			'link'  => 'https://printus.cloud',
		);

		$this->create_notice_markup( 'initial_pro_launch_notice', $content );
	}
}
