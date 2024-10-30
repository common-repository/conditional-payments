<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    DSCPW_Conditional_Payments
 * @subpackage DSCPW_Conditional_Payments/includes
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
 * @package    DSCPW_Conditional_Payments
 * @subpackage DSCPW_Conditional_Payments/includes
 * @author     Multidots <inquiry@multidots.in>
 */
if ( !class_exists( 'DSCPW_Conditional_Payments' ) ) {
    class DSCPW_Conditional_Payments {
        /**
         * The loader that's responsible for maintaining and registering all hooks that power
         * the plugin.
         *
         * @since    1.0.0
         * @access   protected
         * @var      DSCPW_Conditional_Payments_Loader $loader Maintains and registers all hooks for the plugin.
         */
        protected $loader;

        /**
         * The unique identifier of this plugin.
         *
         * @since    1.0.0
         * @access   protected
         * @var      string $plugin_name The string used to uniquely identify this plugin.
         */
        protected $plugin_name;

        /**
         * The current version of the plugin.
         *
         * @since    1.0.0
         * @access   protected
         * @var      string $version The current version of the plugin.
         */
        protected $version;

        /**
         * Define the core functionality of the plugin.
         *
         * Set the plugin name and the plugin version that can be used throughout the plugin.
         * Load the dependencies, define the locale, and set the hooks for the admin area and
         * the public-facing side of the site.
         *
         * @since    1.0.0
         */
        public function __construct() {
            $this->plugin_name = 'conditional-payments';
            $this->version = DSCPW_PLUGIN_VERSION;
            $this->load_dependencies();
            $this->define_admin_hooks();
            $this->define_public_hooks();
            $prefix = ( is_network_admin() ? 'network_admin_' : '' );
            add_filter(
                "{$prefix}plugin_action_links_" . DSCPW_PLUGIN_BASENAME,
                array($this, 'dscpw_plugin_action_links'),
                10,
                4
            );
            add_filter(
                'plugin_row_meta',
                array($this, 'dscpw_plugin_row_meta_action_links'),
                20,
                3
            );
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
            /**
             * The class responsible for orchestrating the actions and filters of the
             * core plugin.
             */
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-conditional-payments-loader.php';
            /**
             * The class responsible for defining all actions that occur in the admin area.
             */
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-conditional-payments-admin.php';
            /**
             * The class responsible for defining all actions that occur in the public-facing
             * side of the site.
             */
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-conditional-payments-public.php';
            $this->loader = new DSCPW_Conditional_Payments_Loader();
        }

        /**
         * Register all of the hooks related to the admin area functionality
         * of the plugin.
         *
         * @since    1.0.0
         * @access   private
         */
        private function define_admin_hooks() {
            if ( !is_admin() ) {
                return;
            }
            $get_section = filter_input( INPUT_GET, 'section', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $plugin_admin = new DSCPW_Conditional_Payments_Admin($this->get_plugin_name(), $this->get_version());
            $this->loader->add_action( 'admin_init', $plugin_admin, 'dscpw_welcome_conditional_payments_screen_do_activation_redirect' );
            $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'dscpw_enqueue_admin_scripts' );
            $this->loader->add_action( 'woocommerce_settings_checkout', $plugin_admin, 'dscpw_start_page' );
            $this->loader->add_filter( 'woocommerce_get_sections_checkout', $plugin_admin, 'dscpw_register_conditions_section' );
            $this->loader->add_action( 'wp_ajax_dscpw_conditional_payments_product_list_ajax', $plugin_admin, 'dscpw_conditional_payments_product_list_ajax' );
            $this->loader->add_action( 'wp_ajax_dscpw_conditional_payments_variable_product_list_ajax', $plugin_admin, 'dscpw_conditional_payments_variable_product_list_ajax' );
            $this->loader->add_action( 'wp_ajax_dscpw_conditional_payments_conditions_values_ajax', $plugin_admin, 'dscpw_conditional_payments_conditions_values_ajax' );
            $this->loader->add_action( 'wp_ajax_dscpw_conditional_payments_actions_values_ajax', $plugin_admin, 'dscpw_conditional_payments_actions_values_ajax' );
            $this->loader->add_action( 'wp_ajax_dscpw_change_status_from_listing_page', $plugin_admin, 'dscpw_change_status_from_listing_page' );
            if ( isset( $get_section ) && false !== strpos( $get_section, 'dscpw_conditional_payments' ) ) {
                $this->loader->add_filter( 'admin_footer_text', $plugin_admin, 'dscpw_admin_footer_review' );
            }
        }

        /**
         * Register all of the hooks related to the public-facing functionality
         * of the plugin.
         *
         * @since    1.0.0
         * @access   private
         */
        private function define_public_hooks() {
            $plugin_public = new DSCPW_Conditional_Payments_Public($this->get_plugin_name(), $this->get_version());
            $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'dscpw_enqueue_public_scripts' );
            $this->loader->add_action( 'woocommerce_before_checkout_form', $plugin_public, 'dscpw_name_address_fields' );
            $this->loader->add_action(
                'woocommerce_checkout_update_order_review',
                $plugin_public,
                'dscpw_store_customer_details',
                10,
                1
            );
            $this->loader->add_action( 'woocommerce_blocks_enqueue_checkout_block_scripts_after', $plugin_public, 'dscpw_enqueue_checkout_script' );
            $this->loader->add_action( 'woocommerce_init', $plugin_public, 'dscpw_update_block_check_payment_methods' );
            if ( !is_admin() ) {
                $this->loader->add_filter( 'woocommerce_available_payment_gateways', $plugin_public, 'dscpw_unset_payments_methods' );
            }
        }

        /**
         * Return the plugin action links.  This will only be called if the plugin
         * is active.
         *
         * @param array $actions associative array of action names to anchor tags
         *
         * @return array associative array of plugin action links
         * @since 1.0.0
         */
        public function dscpw_plugin_action_links( $actions ) {
            global $cp_fs;
            if ( cp_fs()->is_plan( 'pro', true ) ) {
                $account = $cp_fs->get_account_url();
                $account_label = 'My Account';
            } else {
                $account = cp_fs()->get_upgrade_url();
                $account_label = 'Upgrade to Pro';
            }
            $custom_actions = array(
                'account'   => sprintf( '<a href="%s">%s</a>', esc_url( $account ), __( $account_label, 'conditional-payments' ) ),
                'configure' => sprintf( '<a href="%s">%s</a>', esc_url( add_query_arg( array(
                    'page' => 'wc-settings&tab=checkout&section=dscpw_conditional_payments',
                ), admin_url( 'admin.php' ) ) ), __( 'Settings', 'conditional-payments' ) ),
                'docs'      => sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( 'https://docs.thedotstore.com/collection/485-conditional-payments' ), __( 'Docs', 'conditional-payments' ) ),
                'support'   => sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( 'www.thedotstore.com/support' ), __( 'Support', 'conditional-payments' ) ),
            );
            // add the links to the front of the actions list
            return array_merge( $custom_actions, $actions );
        }

        /**
         * Add review stars in plugin row meta
         *
         * @since 1.0.0
         */
        public function dscpw_plugin_row_meta_action_links( $plugin_meta, $plugin_file, $plugin_data ) {
            if ( isset( $plugin_data['TextDomain'] ) && $plugin_data['TextDomain'] !== 'conditional-payments' ) {
                return $plugin_meta;
            }
            $url = '';
            $url = esc_url( 'https://wordpress.org/plugins/conditional-payments/#reviews' );
            $plugin_meta[] = sprintf( '<a href="%s" target="_blank" style="color:#f5bb00;">%s</a>', $url, esc_html( '★★★★★' ) );
            return $plugin_meta;
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
         * @return    string    The name of the plugin.
         * @since     1.0.0
         */
        public function get_plugin_name() {
            return $this->plugin_name;
        }

        /**
         * The reference to the class that orchestrates the hooks with the plugin.
         *
         * @return    DSCPW_Conditional_Payments_Loader orchestrates the hooks of the plugin.
         * @since     1.0.0
         */
        public function get_loader() {
            return $this->loader;
        }

        /**
         * Retrieve the version number of the plugin.
         *
         * @return    string    The version number of the plugin.
         * @since     1.0.0
         */
        public function get_version() {
            return $this->version;
        }

    }

}