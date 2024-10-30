<?php

/**
 * Plugin Name: Conditional Payment Methods for WooCommerce
 * Plugin URI: https://www.thedotstore.com/conditional-payments-for-woocommerce/
 * Description: Allows store owners to restrict payment methods based on the various conditions!
 * Version: 1.2.1
 * Author: theDotstore
 * Author URI: https://www.thedotstore.com/
 * License: GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: conditional-payments
 * Domain Path: /languages/
 * Requires Plugins: woocommerce
 * 
 * WC requires at least: 4.5
 * WP tested up to:      6.6.2
 * WC tested up to:      9.3.3
 * Requires PHP:         5.6
 * Requires at least:    5.0
 */
// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    die;
}
if ( function_exists( 'cp_fs' ) ) {
    cp_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    if ( !function_exists( 'cp_fs' ) ) {
        // Create a helper function for easy SDK access.
        function cp_fs() {
            global $cp_fs;
            if ( !isset( $cp_fs ) ) {
                // Activate multisite network integration.
                if ( !defined( 'WP_FS__PRODUCT_10262_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_10262_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $cp_fs = fs_dynamic_init( array(
                    'id'             => '10262',
                    'slug'           => 'conditional-payments',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_cacffec308be455e8627fc8957f10',
                    'is_premium'     => false,
                    'premium_suffix' => 'Premium',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'menu'           => array(
                        'slug'       => 'conditional-payments',
                        'first-path' => 'admin.php?page=wc-settings&tab=checkout&section=dscpw_conditional_payments',
                        'support'    => false,
                    ),
                    'is_live'        => true,
                ) );
            }
            return $cp_fs;
        }

        // Init Freemius.
        cp_fs();
        // Signal that SDK was initiated.
        do_action( 'cp_fs_loaded' );
    }
}
if ( !defined( 'DSCPW_PLUGIN_VERSION' ) ) {
    define( 'DSCPW_PLUGIN_VERSION', '1.2.1' );
}
if ( !defined( 'DSCPW_PLUGIN_URL' ) ) {
    define( 'DSCPW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( !defined( 'DSCPW_PLUGIN_PATH' ) ) {
    define( 'DSCPW_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}
if ( !defined( 'DSCPW_PLUGIN_BASENAME' ) ) {
    define( 'DSCPW_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( !defined( 'DSCPW_PLUGIN_NAME' ) ) {
    define( 'DSCPW_PLUGIN_NAME', 'Conditional Payments for WooCommerce' );
}
if ( !defined( 'DSCPW_STORE_URL' ) ) {
    define( 'DSCPW_STORE_URL', 'https://www.thedotstore.com/' );
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-category-banner-management-activator.php
 */
if ( !function_exists( 'activate_dscpw_conditional_payments' ) ) {
    function activate_dscpw_conditional_payments() {
        require plugin_dir_path( __FILE__ ) . 'includes/class-conditional-payments-activator.php';
        DSCPW_Conditional_Payments_Activator::activate();
    }

}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-category-banner-management-deactivator.php
 */
if ( !function_exists( 'deactivate_dscpw_conditional_payments' ) ) {
    function deactivate_dscpw_conditional_payments() {
        require plugin_dir_path( __FILE__ ) . 'includes/class-conditional-payments-deactivator.php';
        DSCPW_Conditional_Payments_Deactivator::deactivate();
    }

}
register_activation_hook( __FILE__, 'activate_dscpw_conditional_payments' );
register_deactivation_hook( __FILE__, 'deactivate_dscpw_conditional_payments' );
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) || function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */
    require plugin_dir_path( __FILE__ ) . 'includes/class-conditional-payments.php';
    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since    1.0.0
     */
    if ( !function_exists( 'run_dscpw_woo_product_author' ) ) {
        function run_dscpw_woo_product_author() {
            $plugin = new DSCPW_Conditional_Payments();
            $plugin->run();
        }

    }
}
/**
 * Check Initialize plugin in case of WooCommerce plugin is missing.
 *
 * @since    1.0.0
 */
if ( !function_exists( 'dscpw_initialize_plugin' ) ) {
    function dscpw_initialize_plugin() {
        if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) && (!function_exists( 'is_plugin_active_for_network' ) || !is_plugin_active_for_network( 'woocommerce/woocommerce.php' )) ) {
            add_action( 'admin_notices', 'dscpw_plugin_admin_notice' );
        } else {
            run_dscpw_woo_product_author();
        }
        // Load the plugin text domain for translation.
        load_plugin_textdomain( 'conditional-payments', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

}
add_action( 'plugins_loaded', 'dscpw_initialize_plugin' );
/**
 * Show admin notice in case of WooCommerce plugin is missing.
 *
 * @since    1.0.0
 */
if ( !function_exists( 'dscpw_plugin_admin_notice' ) ) {
    function dscpw_plugin_admin_notice() {
        $wpa_plugin_name = esc_html( DSCPW_PLUGIN_NAME );
        $wc_plugin = esc_html__( 'WooCommerce', 'conditional-payments' );
        ?>
        <div class="error">
            <p>
                <?php 
        echo sprintf( esc_html__( '%1$s requires %2$s to be installed & activated!', 'conditional-payments' ), '<strong>' . esc_html( $wpa_plugin_name ) . '</strong>', '<a href="' . esc_url( 'https://wordpress.org/plugins/woocommerce/' ) . '" target="_blank"><strong>' . esc_html( $wc_plugin ) . '</strong></a>' );
        ?>
            </p>
        </div>
        <?php 
    }

}
/**
 * Plugin compability with WooCommerce HPOS
 *
 * @since 1.1.4
 */
add_action( 'before_woocommerce_init', function () {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );