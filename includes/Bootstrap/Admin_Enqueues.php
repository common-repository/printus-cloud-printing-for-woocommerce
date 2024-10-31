<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://uriahsvictor.com
 * @since      1.0.0
 *
 * @package    Printus
 * @author_name     Uriahs Victor <info@soaringleads.com>
 */

namespace Printus\Bootstrap;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class responsible for enqueuing scripts and styles on the admin dashboard.
 *
 * @package Printus\Bootstrap
 * @since 1.0.0
 */
class Admin_Enqueues {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->plugin_name = PRINTUS_PLUGIN_NAME;
		$this->version     = PRINTUS_VERSION;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, PRINTUS_PLUGIN_ASSETS_PATH_URL . 'admin/css/printus-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-notices', PRINTUS_PLUGIN_ASSETS_PATH_URL . 'admin/css/notices.css', array(), $this->version, 'all' );
		wp_add_inline_style( $this->plugin_name, $this->add_inline_styles() );

		/** selectWoo */
		if ( defined( 'WC_PLUGIN_FILE' ) ) {
			$css_path = plugins_url( 'assets/css/select2.css', WC_PLUGIN_FILE );
			wp_enqueue_style( 'select2', $css_path );
		} else {
			$wc_path  = WP_PLUGIN_DIR . '/woocommerce/woocommerce.php';
			$css_path = plugins_url( 'assets/css/select2.css', $wc_path );
			wp_enqueue_style( 'select2', $css_path );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/** selectWoo */
		wp_enqueue_script( 'selectWoo' );
		wp_enqueue_script( $this->plugin_name, PRINTUS_PLUGIN_ASSETS_PATH_URL . 'admin/js/build/printus-admin.js', array( 'jquery', 'wp-util' ), $this->version, false );

		/** Media uploading */
		wp_enqueue_media(); // Fixes media uploading with WPSF
	}

	/**
	 * Add inline styles for admin dashboard.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	private function add_inline_styles(): string {
		$current_color = get_user_option( 'admin_color' );
		global $_wp_admin_css_colors;
		$color = $_wp_admin_css_colors[ $current_color ]->colors[1] ?? '#3858e9';

		$css = ".wpsf-settings--printus_config .wpsf-tab .postbox h2{
			background: $color !important;
		}";

		return $css;
	}
}
