<?php

/**
 * Review Notices.
 *
 * Notices to review the plugin.
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
class ReviewNotices extends Notice {

	use PluginInfo;

	/**
	 * Class constructor
	 *
	 * @return void
	 */
	public function __construct() {
		$this->create_review_plugin_notice();
	}

	/**
	 * Create leave review for plugin notice.
	 *
	 * @return void
	 */
	public function create_review_plugin_notice() {

		$days_since_installed = $this->get_days_since_installed();

		// Show notice after 3 weeks
		if ( $days_since_installed < 21 ) {
			return;
		}

		$content = array(
			'title' => __( 'Has Printus Helped You?', 'printus-cloud-printing-for-woocommerce' ),
			'body'  => __( 'Hey! its Uriahs, Sole Developer working on Printus - Cloud Printing for Woocommerce. Has the plugin benefited your business? If yes, then would you mind taking a few seconds to leave a kind review? Reviews go a long way and they really help keep me motivated to continue working on the plugin and making it better.', 'printus-cloud-printing-for-woocommerce' ),
			'cta'   => __( 'Sure!', 'printus-cloud-printing-for-woocommerce' ),
			'link'  => 'https://wordpress.org/support/plugin/printus-cloud-printing-for-woocommerce/reviews/#new-post',
		);

		$this->create_notice_markup( 'leave_review_notice_1', $content );
	}
}
