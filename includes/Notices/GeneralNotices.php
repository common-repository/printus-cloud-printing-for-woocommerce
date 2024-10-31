<?php
/**
 * Holds general notices for user.
 *
 * Author:          Uriahs Victor
 * Created on:      03/05/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.1.0
 * @package Printus/Notices
 */

namespace Printus\Notices;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Printus\Traits\PluginInfo;

/**
 * General Notices Class.
 */
class GeneralNotices extends Notice {

	/**
	 * Class constructor.
	 *
	 * @since 1.2.1
	 */
	public function __construct() {
		$this->createWCBlocksIncompatibilityNotice();
	}

	private function createWCBlocksIncompatibilityNotice() {

		$page_id = wc_get_page_id( 'checkout' );

		if ( has_block( 'woocommerce/checkout', $page_id ) === false ) {
			return;
		}

		$content = array(
			'title' => __( 'Printus - WooCommerce Blocks Checkout Not Supported', 'printus-cloud-printing-for-woocommerce' ),
			'body'  => __( 'Hey! It looks like you are making use of the WooCommerce Blocks Checkout. Unfortunately, its not fully compatible with Printus. You need to switch to the classic checkout to use the plugin features.', 'printus-cloud-printing-for-woocommerce' ),
			'cta'   => __( 'Show me how', 'printus-cloud-printing-for-woocommerce' ),
			'link'  => 'https://printus.cloud/docs/switching-to-classic-checkout/',
		);

		$this->create_notice_markup( 'wc_blocks_incompatible', $content );
	}
}
