<?php

/**
 * The plugin bootstrap file
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://uriahsvictor.com
 * @since             1.0.0
 * @package           Printus
 * @wordpress-plugin
 * Plugin Name:       Printus - Cloud Printing for WooCommerce
 * Plugin URI: https://printus.cloud
 * Description:       Print WooCommerce receipts, invoices and labels remotely using any printer.
 * Version:           1.2.6
 * Author:            Uriahs Victor
 * Author URI:        https://uriahsvictor.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * WC requires at least: 5.5
 * WC tested up to: 9.4
 * Requires Plugins: woocommerce
 * Requires PHP: 7.4
 * Text Domain:       printus-cloud-printing-for-woocommerce
 */
if ( !defined( 'WPINC' ) ) {
    die;
}
if ( !defined( 'PRINTUS_VERSION' ) ) {
    define( 'PRINTUS_VERSION', '1.2.6' );
}
/**
 * Check PHP version
 */
if ( function_exists( 'phpversion' ) ) {
    if ( version_compare( phpversion(), '7.4', '<' ) ) {
        add_action( 'admin_notices', function () {
            echo "<div class='notice notice-error is-dismissible'>";
            /* translators: 1: Opening <p> HTML element 2: Opening <strong> HTML element 3: Closing <strong> HTML element 4: Closing <p> HTML element  */
            printf(
                esc_html__( '%1$s%2$sPrintus - Cloud Printing for WooCommerce NOTICE:%3$s PHP version too low to use this plugin. Please change to at least PHP 7.4. You can contact your web host for assistance in updating your PHP version.%4$s', 'printus-cloud-printing-for-woocommerce' ),
                '<p>',
                '<strong>',
                '</strong>',
                '</p>'
            );
            echo '</div>';
        } );
        return;
    }
}
/**
 * Check PHP versions
 */
if ( defined( 'PHP_VERSION' ) ) {
    if ( version_compare( PHP_VERSION, '7.4', '<' ) ) {
        add_action( 'admin_notices', function () {
            echo "<div class='notice notice-error is-dismissible'>";
            /* translators: 1: Opening <p> HTML element 2: Opening <strong> HTML element 3: Closing <strong> HTML element 4: Closing <p> HTML element  */
            printf(
                esc_html__( '%1$s%2$sPrintus - Cloud Printing for WooCommerce NOTICE:%3$s PHP version too low to use this plugin. Please change to at least PHP 7.4. You can contact your web host for assistance in updating your PHP version.%4$s', 'printus-cloud-printing-for-woocommerce' ),
                '<p>',
                '<strong>',
                '</strong>',
                '</p>'
            );
            echo '</div>';
        } );
        return;
    }
}
/**
 * Check that WooCommerce is active.
 *
 * This needs to happen before freemius does any work.
 *
 * @since 1.0.0
 */
if ( !function_exists( 'sl_wc_active' ) ) {
    function sl_wc_active() {
        $active_plugins = (array) apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
        if ( is_multisite() ) {
            $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
        }
        return in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins ) || class_exists( 'WooCommerce' );
    }

}
if ( !sl_wc_active() ) {
    add_action( 'admin_notices', function () {
        echo "<div class='notice notice-error is-dismissible'>";
        /* translators: 1: Opening <p> HTML element 2: Opening <strong> HTML element 3: Closing <strong> HTML element 4: Closing <p> HTML element  */
        printf(
            esc_html__( '%1$s%2$sPrintus - Cloud Printing for WooCommerce NOTICE:%3$s WooCommerce is not activated, please activate it to use the plugin.%4$s', 'printus-cloud-printing-for-woocommerce' ),
            '<p>',
            '<strong>',
            '</strong>',
            '</p>'
        );
        echo '</div>';
    } );
    return;
}
if ( function_exists( 'pcpfw_fs' ) ) {
    pcpfw_fs()->set_basename( false, __FILE__ );
} else {
    if ( !function_exists( 'pcpfw_fs' ) ) {
        // Create a helper function for easy SDK access.
        function pcpfw_fs() {
            global $pcpfw_fs;
            if ( !isset( $pcpfw_fs ) ) {
                // Include Freemius SDK.
                require_once __DIR__ . '/vendor/freemius/wordpress-sdk/start.php';
                $pcpfw_fs = fs_dynamic_init( array(
                    'id'             => '12321',
                    'slug'           => 'printus-cloud-printing-for-woocommerce',
                    'premium_slug'   => 'printus-cloud-printing-for-woocommerce-pro',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_0ec27ef0b99e1f3e02e01f8f54352',
                    'is_premium'     => false,
                    'premium_suffix' => 'PRO',
                    'has_addons'     => false,
                    'has_paid_plans' => false,
                    'menu'           => array(
                        'slug'   => 'printus-config-settings',
                        'parent' => array(
                            'slug' => 'sl-plugins-menu',
                        ),
                    ),
                    'is_live'        => true,
                ) );
            }
            return $pcpfw_fs;
        }

        // Init Freemius.
        pcpfw_fs();
        // Signal that SDK was initiated.
        do_action( 'pcpfw_fs_loaded' );
    }
    // Autoload
    require_once __DIR__ . '/vendor-prefixed/autoload.php';
    require_once __DIR__ . '/vendor/autoload.php';
    /**
     * The code that runs during plugin activation.
     * This action is documented in includes/PrintusActivator.php
     */
    if ( !function_exists( 'activate_printus' ) ) {
        /**
         * Run logic on plugin activation.
         *
         * @return void
         * @since 1.0.0
         */
        function activate_printus() {
            require_once plugin_dir_path( __FILE__ ) . 'includes/PrintusActivator.php';
            \Printus\PrintusActivator::activate();
        }

    }
    /**
     * The code that runs during plugin deactivation.
     * This action is documented in includes/PrintusDeactivator.php.php
     */
    if ( !function_exists( 'deactivate_printus' ) ) {
        /**
         * Run logic on plugin deactivation.
         *
         * @return void
         * @since 1.0.0
         */
        function deactivate_printus() {
            require_once plugin_dir_path( __FILE__ ) . 'includes/PrintusDeactivator.php';
            \Printus\PrintusDeactivator::deactivate();
        }

    }
    register_activation_hook( __FILE__, 'activate_printus' );
    register_deactivation_hook( __FILE__, 'deactivate_printus' );
    require __DIR__ . '/class-uninstall.php';
    if ( function_exists( 'pcpfw_fs' ) ) {
        pcpfw_fs()->add_action( 'after_uninstall', array(new Printus_Uninstall(), 'remove_plugin_settings') );
        pcpfw_fs()->add_filter( 'plugin_icon', function () {
            return __DIR__ . '/assets/admin/img/logo.png';
        } );
    }
    define( 'PRINTUS_BASE_FILE', basename( plugin_dir_path( __FILE__ ) ) );
    define( 'PRINTUS_PLUGIN_NAME', 'printus' );
    define( 'PRINTUS_PLUGIN_DIR', __DIR__ . '/' );
    define( 'PRINTUS_PLUGIN_ASSETS_DIR', __DIR__ . '/assets/' );
    define( 'PRINTUS_PLUGIN_ASSETS_PATH_URL', plugin_dir_url( __FILE__ ) . 'assets/' );
    define( 'PRINTUS_PLUGIN_PATH_URL', plugin_dir_url( __FILE__ ) );
    define( 'PRINTUS_TEMPLATES_PATH', plugin_dir_path( __FILE__ ) . 'includes/Views/Prints/Templates/' );
    if ( is_dir( get_stylesheet_directory() . '/printus/templates/' ) ) {
        $custom_templates_path = get_stylesheet_directory() . '/printus/templates/';
    } elseif ( is_dir( WP_PLUGIN_DIR . '/printus-custom-templates/templates/' ) ) {
        $custom_templates_path = WP_PLUGIN_DIR . '/printus-custom-templates/templates/';
    } else {
        $custom_templates_path = '';
    }
    if ( !empty( $custom_templates_path ) ) {
        define( 'PRINTUS_CUSTOM_TEMPLATES_PATH', $custom_templates_path );
    }
    $debug = false;
    if ( defined( 'SL_DEV_DEBUGGING' ) ) {
        $debug = true;
    }
    define( 'PRINTUS_DEBUG', $debug );
    if ( PRINTUS_DEBUG ) {
        define( 'PRINTUS_DEBUG_PRINT_ADMIN_JOB', true );
    }
    // HPOS Compatibility
    add_action( 'before_woocommerce_init', function () {
        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
        }
    } );
    // Blocks checkout incompatibility.
    add_action( 'before_woocommerce_init', function () {
        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, false );
        }
    } );
    if ( !function_exists( 'soaringleads_printus_init' ) ) {
        /**
         * Bootstrap plugin.
         *
         * @return void
         */
        function soaringleads_printus_init() {
            do_action( 'printus_before_init' );
            $plugin_instance = \Printus\Bootstrap\Main::get_instance();
            $plugin_instance->run();
            do_action( 'printus_after_init' );
        }

    }
    add_action( 'plugins_loaded', 'soaringleads_printus_init' );
}