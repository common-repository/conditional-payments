<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Fired during plugin deactivation
 *
 * @since      1.0.0
 *
 * @package    DSCPW_Conditional_Payments
 * @subpackage DSCPW_Conditional_Payments/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    DSCPW_Conditional_Payments
 * @subpackage DSCPW_Conditional_Payments/includes
 * @author     Multidots <inquiry@multidots.in>
 */

if ( !class_exists( 'DSCPW_Conditional_Payments_Deactivator' ) ) {
	class DSCPW_Conditional_Payments_Deactivator {

		/**
		 * Short Description. (use period)
		 *
		 * Long Description.
		 *
		 * @since    1.0.0
		 */
		public static function deactivate() {

		}

	}
}