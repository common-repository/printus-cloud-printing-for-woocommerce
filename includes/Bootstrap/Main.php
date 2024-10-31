<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://uriahsvictor.com
 * @since      1.0.0
 *
 * @package    Printus
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Printus
 * @subpackage Printus/includes
 * @author_name     Uriahs Victor <info@soaringleads.com>
 */
namespace Printus\Bootstrap;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Printus\Controllers\Ajax_Handlers\PluginSettingsController as PluginSettingsAjaxHandler;
use Printus\Controllers\Ajax_Handlers\PrintOnDemandController;
use Printus\Controllers\API\Order\PrintOrderController;
use Printus\Controllers\WooCommerce\OrderStatusController;
use Printus\Models\PluginSettings\GeneralSettingsModel;
use Printus\Views\Admin\PluginSettings\GeneralSettings;
use Printus\Views\Admin\PluginSettings\Setup\BootstrapSettings;

use Printus\Notices\Loader as Notices_Loader;
use Printus\Notices\Notice;
use Printus\Views\Admin\Metabox\Metabox;
use Printus\Views\Admin\PluginSettings\ToolsSettings;

/**
 * Class Main.
 *
 * Class responsible for firing public and admin hooks.
 */
class Main {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Plugin instance
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Gets an instance of our plugin.
	 *
	 * @return Main()
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	private function __construct() {
		$this->version = PRINTUS_VERSION;

		$this->plugin_name = PRINTUS_PLUGIN_NAME;

		$this->load_dependencies();
		$this->set_locale();
		$this->defineAdminHooks();
		$this->definePublicHooks();
		$this->defineMutualHooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		$this->loader = new Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @return void
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale(): void {
		$plugin_i18n = new I18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Orchestrate print trigger so it can be fired both in admin and front end.
	 *
	 * @return void
	 * @since 1.1.9
	 */
	private function setupPrintJobTrigger(): void {

		$print_trigger_hook = (array) $this->getPrintTriggerHook();

		$controller_print_order = new PrintOrderController();

		if ( ! empty( $print_trigger_hook ) ) {

			foreach ( $print_trigger_hook as $hook_name ) {
				if ( pcpfw_fs()->can_use_premium_code() === false ) {
					// Fire print job.
					$this->loader->add_action( $hook_name, $controller_print_order, 'sendPrintJob', 100 );
				} else {
					// Fire PRO print job.
					$controller_pro_print_order = new \Printus\Pro\Controllers\API\Order\PrintOrderController();
					$this->loader->add_action( $hook_name, $controller_pro_print_order, 'preparePrintJob', 100 );
				}
			}
		}
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @return void
	 * @access   private
	 * @since    1.0.0
	 */
	private function defineAdminHooks(): void {
		if ( ! is_admin() && ! wp_doing_cron() ) {
			return; // Bail if not admin request and not doing cron.
		}

		$plugin_admin                        = new Admin_Enqueues();
		$bootstrap_cron_setup                = new Setup_Cron();
		$controller_ajax_settings_handlers   = new PluginSettingsAjaxHandler();
		$controller_print_on_demand_handlers = new PrintOnDemandController();
		$view_general_settings               = new GeneralSettings();
		$view_tools_settings                 = new ToolsSettings();
		$controller_print_order              = new PrintOrderController();
		$view_metabox                        = new Metabox();

		$notice         = new Notice();
		$notices_loader = new Notices_Loader();

		$this->setupPrintJobTrigger();

		// Menu Item.
		$this->loader->add_action( 'admin_menu', $this, 'create_admin_menu' );
		// Enqueue Scripts
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Cron tasks.
		$this->loader->add_action( 'admin_init', $bootstrap_cron_setup, 'set_cron_tasks' );

		// Custom settings page content.
		$this->loader->add_action( 'wpsf_after_field_printus_config_general_settings_printer_settings_section_select-printer', $view_general_settings, 'createRefreshPrintersButton', 100 );
		$this->loader->add_action( 'wp_ajax_printus_refresh_printers', $controller_ajax_settings_handlers, 'refresh_printers_btn_handler', 100 );

		// WP Adjax calls
		$this->loader->add_action( 'wpsf_after_field_printus_config_tools_settings_tools_general_section_clear-fonts-cache', $view_tools_settings, 'createClearFontsCacheButton', 100 );
		$this->loader->add_action( 'wp_ajax_printus_clear_fonts_cache', $controller_ajax_settings_handlers, 'printusClearFontsCacheBtnHandler', 100 );
		$this->loader->add_action( 'wp_ajax_printus_print_order_from_admin', $controller_print_on_demand_handlers, 'printusPrintOrderFromAdminBtnHandler', 100 );

		// Notices Loader.
		$this->loader->add_action( 'admin_notices', $notices_loader, 'load_notices' );
		// Notices Ajax dismiss method.
		$this->loader->add_action( 'wp_ajax_printus_dismiss_notice', $notice, 'dismiss_notice' );

		// THESE LINES ARE ONLY FOR DEBUGGING THE PRINTING AND TEMPLATES, IF ANY OF THE CONSTANTS ARE LEFT ON THEN THEY WOULD PREVENT THE CHECKOUT PROCESS.
		if ( defined( 'PRINTUS_DEBUG_PRINT_ADMIN_JOB' ) ) {
			$this->loader->add_action( 'woocommerce_update_order', $controller_print_order, 'sendPrintJobAdminDebug', 10, 2 );
			// $this->loader->add_action( 'post_updated', $controller_print_order, 'sendPrintJobAdminDebug', 10, 3 );
			// $this->loader->add_action( 'post_updated', $controller_pro_print_order, 'preparePrintJob', 10, 3 );
		}

		// Misc
		$this->loader->add_action( 'add_meta_boxes', $view_metabox, 'createMetabox' );
		$this->loader->add_filter( 'plugin_action_links', $this, 'add_plugin_action_links', 100, 2 );

		// Plugin dashboard settings.
		new BootstrapSettings();
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @access   private
	 * @return void
	 * @since    1.0.0
	 */
	private function definePublicHooks(): void {
		if ( is_admin() && ! wp_doing_ajax() ) {
			return; // Bail if is admin request and not doing ajax.
		}
		$plugin_public = new Frontend_Enqueues();

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->setupPrintJobTrigger();
	}

	/**
	 * Hooks that should fire regardless whether its the frontend or backend.
	 *
	 * @return void
	 * @since 1.2.3
	 */
	private function defineMutualHooks() {
		$controller_order_statuses = new OrderStatusController();
		$this->loader->add_filter( 'woocommerce_valid_order_statuses_for_payment_complete', $controller_order_statuses, 'alterPaymentCompleteStatuses' );
	}

	/**
	 * The hook that fires a print job.
	 *
	 * Filtering the hook can allow for returning an array which will be handled by Printus.
	 *
	 * @return string|array
	 * @since 1.0.0
	 */
	private function getPrintTriggerHook() {

		$print_trigger = GeneralSettingsModel::getPrintTriggerHook();

		if ( 'checkout_complete' === $print_trigger ) {
			$hook = 'woocommerce_checkout_order_processed';
		} elseif ( 'order_complete' === $print_trigger ) {
			$hook = 'woocommerce_order_status_completed';
		} elseif ( 'payment_complete' === $print_trigger ) {
			$hook = 'woocommerce_payment_complete';
		} else {
			$hook = $print_trigger; // In case user adds their own hook. Take the setting that was saved.
		}

		return apply_filters( 'printus_settings__print_trigger_hook', $hook );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Add action Links for plugin
	 *
	 * @param array  $plugin_actions Available actions for the plugin.
	 * @param string $plugin_file The plugin file name.
	 * @return array
	 */
	public function add_plugin_action_links( $plugin_actions, $plugin_file ) {
		return $plugin_actions;
	}

	/**
	 * Create our SoaringLeads menu item.
	 *
	 * @return void
	 * @since 1.0.3
	 */
	public function create_admin_menu(): void {

		$icon = file_get_contents( PRINTUS_PLUGIN_ASSETS_DIR . 'admin/img/menu-icon.svg' );
		$icon = 'data:image/svg+xml;base64,' . base64_encode( $icon );

		$main_menu = menu_page_url( 'sl-plugins-menu', false );

		if ( ! empty( $main_menu ) ) {
			return; // Menu already added by another SoaringLeads plugin.
		}

		add_menu_page(
			__( 'SoaringLeads Plugins', 'map-location-picker-at-checkout-for-woocommerce' ),
			'SoaringLeads',
			'manage_options',
			'sl-plugins-menu',
			array( $this, 'output_root_submenu_upsells' ),
			$icon,
			'57.10'
		);
	}

	/**
	 * HTML for root SoaringLeads page.
	 *
	 * Populate with upsell content.
	 *
	 * @since 1.1.1
	 */
	public function output_root_submenu_upsells() {
		?>
		<h1><?php esc_html_e( 'Check out our available plugins', 'printus-cloud-printing-for-woocommerce' ); ?></h1>
		<hr style='margin-bottom: 40px'/>
		
		<div style='margin-bottom: 40px'>
		<a href='https://chwazidatetime.com/?utm_source=wpadmin&utm_medium=sl-plugins-page&utm_campaign=plugins-upsell' rel="noreferrer" target='_blank'><img alt='Chwazi banner image' src='<?php echo esc_attr( PRINTUS_PLUGIN_ASSETS_PATH_URL . 'admin/img/delivery-and-pickup-scheduling.png' ); ?>' /></a>
		<p style='font-size: 18px; font-weight: 700;'><?php esc_html_e( 'Allow customers to set their delivery/pickup date and time during order checkout.', 'printus-cloud-printing-for-woocommerce' ); ?></p>
		<a href='https://chwazidatetime.com/?utm_source=wpadmin&utm_medium=sl-plugins-page&utm_campaign=plugins-upsell' rel="noreferrer" target='_blank' class='button-primary'><?php esc_html_e( 'Learn More', 'printus-cloud-printing-for-woocommerce' ); ?></a>
		</div>

		<div style='margin-bottom: 40px'>
		<a href='https://lpacwp.com/?utm_source=wpadmin&utm_medium=sl-plugins-page&utm_campaign=plugins-upsell' rel="noreferrer" target='_blank'><img alt='Kikote banner image' src='<?php echo esc_attr( PRINTUS_PLUGIN_ASSETS_PATH_URL . 'admin/img/lpac.png' ); ?>' /></a>
		<p style='font-size: 18px; font-weight: 700;'><?php esc_html_e( 'Let customers choose their shipping or pickup location using a map during checkout.', 'printus-cloud-printing-for-woocommerce' ); ?></p>
		<a href='https://lpacwp.com/?utm_source=wpadmin&utm_medium=sl-plugins-page&utm_campaign=plugins-upsell' rel="noreferrer" target='_blank' class='button-primary'><?php esc_html_e( 'Learn More', 'printus-cloud-printing-for-woocommerce' ); ?></a>
		</div>

		<div style='margin-bottom: 40px'>
		<a href='https://printus.cloud/?utm_source=wpadmin&utm_medium=sl-plugins-page&utm_campaign=plugins-upsell' rel="noreferrer" target='_blank'><img alt='Printus banner image' src='<?php echo esc_attr( PRINTUS_PLUGIN_ASSETS_PATH_URL . 'admin/img/printus.png' ); ?>' /></a>
		<p style='font-size: 18px; font-weight: 700;'><?php esc_html_e( 'Automatically print order invoices, receipts, package slips and labels to your local printer.', 'printus-cloud-printing-for-woocommerce' ); ?></p>
		<a href='https://printus.cloud/?utm_source=wpadmin&utm_medium=sl-plugins-page&utm_campaign=plugins-upsell' rel="noreferrer" target='_blank' class='button-primary'><?php esc_html_e( 'Learn More', 'printus-cloud-printing-for-woocommerce' ); ?></a>
		</div>
		
		<?php
	}
}
