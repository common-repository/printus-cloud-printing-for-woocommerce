<?php
/**
 * Load Notices to admin notices hook.
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

use Printus\Notices\UpsellsNotices;
use Printus\Notices\ReviewNotices;

/**
 * The Loader class.
 */
class Loader {

	/**
	 * Load our notices.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function load_notices() {
		if ( get_current_user_id() !== 1 ) { // Show only to main admin.
			return;
		}
		( new UpsellsNotices() );
		( new ReviewNotices() );
		( new GeneralNotices() );
	}
}
