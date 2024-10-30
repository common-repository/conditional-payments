<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    DSCPW_Conditional_Payments
 * @subpackage DSCPW_Conditional_Payments/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    DSCPW_Conditional_Payments
 * @subpackage DSCPW_Conditional_Payments/includes
 * @author     Multidots <inquiry@multidots.in>
 */
if ( !class_exists( 'DSCPW_Conditional_Payments_Activator' ) ) {
	class DSCPW_Conditional_Payments_Activator {

		/**
		 * Short Description. (use period)
		 *
		 * Long Description.
		 *
		 * @since    1.0.0
		 */
		public static function activate() {
			set_transient( '_welcome_screen_dscpw_mode_activation_redirect_data', true, 30 );
			
			if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) && ! is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
				wp_die( wp_kses_post( "<strong>". DSCPW_PLUGIN_NAME ."</strong> plugin requires <strong>WooCommerce</strong>. Return to <a href='" . esc_url( get_admin_url( null, 'plugins.php' ) ) . "'>Plugins page</a>." ) );
			}
		}
	}
}