<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    DSCPW_Conditional_Payments
 * @subpackage DSCPW_Conditional_Payments/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    DSCPW_Conditional_Payments
 * @subpackage DSCPW_Conditional_Payments/admin
 * @author     Sahil Multani
 */
if ( !class_exists( 'DSCPW_Conditional_Payments_Admin' ) ) {
    class DSCPW_Conditional_Payments_Admin {
        const post_type = 'wc_dscpw';

        /**
         * The ID of this plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string $plugin_name The ID of this plugin.
         */
        private $plugin_name;

        /**
         * The version of this plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string $version The current version of this plugin.
         */
        private $version;

        /**
         * Initialize the class and set its properties.
         *
         * @param string $plugin_name The name of this plugin.
         * @param string $version     The version of this plugin.
         *
         * @since    1.0.0
         */
        public function __construct( $plugin_name, $version ) {
            $this->plugin_name = $plugin_name;
            $this->version = $version;
        }

        /**
         * Register the stylesheets for the admin area.
         *
         * @since    1.0.0
         *
         */
        public function dscpw_enqueue_admin_scripts() {
            $get_section = filter_input( INPUT_GET, 'section', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $allConditions = array(
                'ajaxurl'                => admin_url( 'admin-ajax.php' ),
                'ajax_icon'              => esc_url( plugin_dir_url( __FILE__ ) . '/images/ajax-loader.gif' ),
                'plugin_url'             => plugin_dir_url( __FILE__ ),
                'validation_length1'     => esc_html__( 'Please enter 3 or more characters', 'conditional-payments' ),
                'dscpw_ajax_nonce'       => wp_create_nonce( 'dscpw_nonce' ),
                'select_some_options'    => esc_html__( 'Select some options', 'conditional-payments' ),
                'billing_address_group'  => esc_html__( 'Billing Address', 'conditional-payments' ),
                'shipping_address_group' => esc_html__( 'Shipping Address', 'conditional-payments' ),
                'billing_first_name'     => esc_html__( 'First Name', 'conditional-payments' ),
                'billing_last_name'      => esc_html__( 'Last Name', 'conditional-payments' ),
                'billing_company'        => esc_html__( 'Company', 'conditional-payments' ),
                'billing_address_1'      => esc_html__( 'Address', 'conditional-payments' ),
                'billing_address_2'      => esc_html__( 'Address 2', 'conditional-payments' ),
                'billing_country'        => esc_html__( 'Country', 'conditional-payments' ),
                'billing_city'           => esc_html__( 'City', 'conditional-payments' ),
                'billing_postcode'       => esc_html__( 'Postcode', 'conditional-payments' ),
                'shipping_first_name'    => esc_html__( 'First Name', 'conditional-payments' ),
                'shipping_last_name'     => esc_html__( 'Last Name', 'conditional-payments' ),
                'shipping_company'       => esc_html__( 'Company', 'conditional-payments' ),
                'shipping_address_1'     => esc_html__( 'Address', 'conditional-payments' ),
                'shipping_address_2'     => esc_html__( 'Address 2', 'conditional-payments' ),
                'shipping_country'       => esc_html__( 'Country', 'conditional-payments' ),
                'shipping_city'          => esc_html__( 'City', 'conditional-payments' ),
                'shipping_postcode'      => esc_html__( 'Postcode', 'conditional-payments' ),
                'equal_to'               => esc_html__( 'Equal to ( = )', 'conditional-payments' ),
                'not_equal_to'           => esc_html__( 'Not Equal to ( != )', 'conditional-payments' ),
                'less_or_equal_to'       => esc_html__( 'Less or Equal to ( <= )', 'conditional-payments' ),
                'less_than'              => esc_html__( 'Less then ( < )', 'conditional-payments' ),
                'greater_or_equal_to'    => esc_html__( 'Greater or Equal to ( >= )', 'conditional-payments' ),
                'greater_than'           => esc_html__( 'Greater then ( > )', 'conditional-payments' ),
                'is_empty'               => esc_html__( 'Is Empty', 'conditional-payments' ),
                'is_not_empty'           => esc_html__( 'Is Not Empty', 'conditional-payments' ),
                'delete'                 => esc_html__( 'Delete', 'conditional-payments' ),
                'product_specific'       => esc_html__( 'Product Specific', 'conditional-payments' ),
                'product'                => esc_html__( 'Product', 'conditional-payments' ),
                'variable_product'       => esc_html__( 'Variable Product', 'conditional-payments' ),
                'cart_specific'          => esc_html__( 'Cart Specific', 'conditional-payments' ),
                'cart_total'             => esc_html__( 'Cart Subtotal (Before Discount)', 'conditional-payments' ),
                'cart_totalafter'        => esc_html__( 'Cart Subtotal (After Discount)', 'conditional-payments' ),
                'shipping_specific'      => esc_html__( 'Shipping Specific', 'conditional-payments' ),
                'shipping_method'        => esc_html__( 'Shipping Method', 'conditional-payments' ),
                'enable_payments'        => esc_html__( 'Enable Payments Methods', 'conditional-payments' ),
                'disable_payments'       => esc_html__( 'Disable Payments Methods', 'conditional-payments' ),
                'time_specific'          => esc_html__( 'Time Specific', 'conditional-payments' ),
                'day_of_week'            => esc_html__( 'Day Of Week', 'conditional-payments' ),
                'date'                   => esc_html__( 'Date', 'conditional-payments' ),
            );
            $allConditions['product_categories_disabled'] = esc_html__( 'Product Categories 🔒', 'conditional-payments' );
            $allConditions['product_tags_disabled'] = esc_html__( 'Product Tags 🔒', 'conditional-payments' );
            $allConditions['product_type_disabled'] = esc_html__( 'Product Type 🔒', 'conditional-payments' );
            $allConditions['billing_email_disabled'] = esc_html__( 'Email 🔒', 'conditional-payments' );
            $allConditions['billing_phone_disabled'] = esc_html__( 'Phone 🔒', 'conditional-payments' );
            $allConditions['customer_group'] = esc_html__( 'Customer', 'conditional-payments' );
            $allConditions['customer_authenticated_disabled'] = esc_html__( 'Logged in / out 🔒', 'conditional-payments' );
            $allConditions['user_role_disabled'] = esc_html__( 'User Role 🔒', 'conditional-payments' );
            $allConditions['user_disabled'] = esc_html__( 'User 🔒', 'conditional-payments' );
            $allConditions['cart_quantity_disabled'] = esc_html__( 'Cart Quantity 🔒', 'conditional-payments' );
            $allConditions['shipping_class_disabled'] = esc_html__( 'Shipping Class 🔒', 'conditional-payments' );
            $allConditions['coupon_disabled'] = esc_html__( 'Coupon 🔒', 'conditional-payments' );
            $allConditions['previous_order_disabled'] = esc_html__( 'Previous Order 🔒', 'conditional-payments' );
            $allConditions['product_quantity_disabled'] = esc_html__( 'Product Quantity 🔒', 'conditional-payments' );
            $allConditions['total_weight_disabled'] = esc_html__( 'Total Weight 🔒', 'conditional-payments' );
            $allConditions['number_of_items_disabled'] = esc_html__( 'Number Of Items 🔒', 'conditional-payments' );
            $allConditions['total_volume_disabled'] = esc_html__( 'Total Volume 🔒', 'conditional-payments' );
            $allConditions['add_payment_method_fee_disabled'] = esc_html__( 'Add Payment Method Fee 🔒', 'conditional-payments' );
            $allConditions['note'] = esc_html__( 'Note: ', 'conditional-payments' );
            $allConditions['shipping_city_msg'] = esc_html__( 'Add only one city in a Line. You can add multiple cities in each new line.', 'conditional-payments' );
            $allConditions['billing_city_msg'] = esc_html__( 'Add only one city in a Line. You can add multiple cities in each new line.', 'conditional-payments' );
            $allConditions['shipping_postcode_msg'] = esc_html__( 'Add only one post/zip code in a Line. You can add multiple postcode in each new line.', 'conditional-payments' );
            $allConditions['billing_postcode_msg'] = esc_html__( 'Add only one post/zip code in a Line. You can add multiple postcode in each new line.', 'conditional-payments' );
            $allConditions['billing_phone_msg'] = esc_html__( 'Add only one phone number in a Line. You can add multiple phone number in each new line. ', 'conditional-payments' );
            $allConditions['cart_totalafter_msg'] = esc_html__( 'This rule will apply when you would apply coupun in front side. ', 'conditional-payments' );
            $allConditions['click_here'] = esc_html__( 'Click Here.', 'conditional-payments' );
            $allConditions['docs_url'] = esc_url( 'https://docs.thedotstore.com/collection/485-conditional-payments', 'conditional-payments' );
            $allConditions['time_disabled'] = esc_html__( 'Time 🔒', 'conditional-payments' );
            $allConditions['product_visibility_disabled'] = esc_html__( 'Product Visibility 🔒', 'conditional-payments' );
            $allConditions['product_stock_status_disabled'] = esc_html__( 'Stock Status 🔒', 'conditional-payments' );
            if ( isset( $get_section ) && false !== strpos( $get_section, 'dscpw_conditional_payments' ) ) {
                wp_enqueue_script( 'jquery-ui-datepicker' );
                wp_enqueue_style(
                    $this->plugin_name . 'select2-style',
                    plugin_dir_url( __FILE__ ) . 'css/select2.min.css',
                    array(),
                    'all'
                );
                wp_enqueue_style(
                    $this->plugin_name . 'main-style',
                    plugin_dir_url( __FILE__ ) . 'css/style.css',
                    array(),
                    'all'
                );
                wp_enqueue_script(
                    $this->plugin_name . 'select2-js',
                    plugin_dir_url( __FILE__ ) . 'js/select2.full.min.js',
                    array('jquery'),
                    $this->version,
                    true
                );
                wp_enqueue_script(
                    $this->plugin_name . '-help-scout-beacon-js',
                    plugin_dir_url( __FILE__ ) . 'js/help-scout-beacon.js',
                    array('jquery'),
                    $this->version,
                    false
                );
                wp_enqueue_script(
                    $this->plugin_name . 'freemius_pro',
                    'https://checkout.freemius.com/checkout.min.js',
                    array('jquery'),
                    $this->version,
                    true
                );
                wp_enqueue_script(
                    $this->plugin_name . 'admin-js',
                    plugin_dir_url( __FILE__ ) . 'js/conditional-payments-admin.js',
                    array('jquery', 'jquery-ui-datepicker'),
                    $this->version,
                    true
                );
                wp_localize_script( $this->plugin_name . 'admin-js', 'coditional_vars', $allConditions );
            }
        }

        /**
         * Show admin footer review text.
         *
         * @since    1.0.0
         */
        public function dscpw_admin_footer_review() {
            $url = '';
            $url = esc_url( 'https://wordpress.org/plugins/conditional-payments/#reviews' );
            $html = sprintf( wp_kses( __( '<strong>We need your support</strong> to keep updating and improving the plugin. Please <a href="%1$s" target="_blank">help us by leaving a good review</a> :) Thanks!', 'conditional-payments' ), array(
                'strong' => array(),
                'a'      => array(
                    'href'   => array(),
                    'target' => 'blank',
                ),
            ) ), esc_url( $url ) );
            return wp_kses_post( $html );
        }

        /**
         * Redirect to quick start guide after plugin activation
         *
         * @since    1.0.0
         */
        public function dscpw_welcome_conditional_payments_screen_do_activation_redirect() {
            // if no activation redirect
            if ( !get_transient( '_welcome_screen_dscpw_mode_activation_redirect_data' ) ) {
                return;
            }
            // Delete the redirect transient
            delete_transient( '_welcome_screen_dscpw_mode_activation_redirect_data' );
            // if activating from network, or bulk
            $activate_multi = filter_input( INPUT_GET, 'activate-multi', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            if ( is_network_admin() || isset( $activate_multi ) ) {
                return;
            }
            // Redirect to welcome  page
            wp_safe_redirect( add_query_arg( array(
                'page' => 'wc-settings&tab=checkout&section=dscpw_conditional_payments',
            ), admin_url( 'admin.php' ) ) );
            exit;
        }

        /**
         * Register section under "Payments" settings in WooCommerce
         * 
         * @since    1.0.0
         */
        public function dscpw_register_conditions_section( $sections ) {
            $sections['dscpw_conditional_payments'] = __( 'Conditions', 'conditional-payments' );
            return $sections;
        }

        /**
         * Conditional Payments List Page
         *
         * @since    1.0.0
         */
        public function dscpw_start_page() {
            require_once plugin_dir_path( __FILE__ ) . 'partials/dscpw-start-page.php';
        }

        /**
         * Display message in admin side
         *
         * @param string $message
         * @param string $section
         *
         * @return bool
         * @since 1.0.0
         *
         */
        public function dscpw_updated_message( $message, $section, $validation_msg ) {
            if ( empty( $message ) ) {
                return false;
            }
            if ( 'dscpw_conditional_payments' === $section ) {
                if ( 'created' === $message ) {
                    $updated_message = esc_html__( "Conditional paymets rule created.", 'conditional-payments' );
                } elseif ( 'saved' === $message ) {
                    $updated_message = esc_html__( "Conditional paymets rule updated.", 'conditional-payments' );
                } elseif ( 'deleted' === $message ) {
                    $updated_message = esc_html__( "Conditional paymets rule deleted.", 'conditional-payments' );
                } elseif ( 'duplicated' === $message ) {
                    $updated_message = esc_html__( "Conditional paymets rule duplicated.", 'conditional-payments' );
                } elseif ( 'disabled' === $message ) {
                    $updated_message = esc_html__( "Conditional paymets rule disabled.", 'conditional-payments' );
                } elseif ( 'enabled' === $message ) {
                    $updated_message = esc_html__( "Conditional paymets rule enabled.", 'conditional-payments' );
                }
                if ( 'failed' === $message ) {
                    $failed_messsage = esc_html__( "There was an error with saving data.", 'conditional-payments' );
                } elseif ( 'nonce_check' === $message ) {
                    $failed_messsage = esc_html__( "There was an error with security check.", 'conditional-payments' );
                }
                if ( 'validated' === $message ) {
                    $validated_messsage = esc_html( $validation_msg );
                }
            } else {
                if ( 'saved' === $message ) {
                    $updated_message = esc_html__( "Settings saved successfully", 'conditional-payments' );
                }
                if ( 'nonce_check' === $message ) {
                    $failed_messsage = esc_html__( "There was an error with security check.", 'conditional-payments' );
                }
                if ( 'validated' === $message ) {
                    $validated_messsage = esc_html( $validation_msg );
                }
            }
            if ( !empty( $updated_message ) ) {
                echo sprintf( '<div id="message" class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html( $updated_message ) );
                return false;
            }
            if ( !empty( $failed_messsage ) ) {
                echo sprintf( '<div id="message" class="notice notice-error is-dismissible"><p>%s</p></div>', esc_html( $failed_messsage ) );
                return false;
            }
            if ( !empty( $validated_messsage ) ) {
                echo sprintf( '<div id="message" class="notice notice-error is-dismissible"><p>%s</p></div>', esc_html( $validated_messsage ) );
                return false;
            }
        }

        /**
         * Display textfield and multiselect dropdown based on country, product and etc
         *
         * @return string $html
         * @since 1.0.0
         *
         * @uses  dscpw_get_country_list()
         * @uses  dscpw_get_product_list_select_box()
         * @uses  dscpw_get_varible_product_select_box()
         * @uses  dscpw_get_shipping_methods_list()
         * @uses  dscpw_allowed_html_tags()
         * 
         */
        public function dscpw_conditional_payments_conditions_values_ajax() {
            // Security check
            check_ajax_referer( 'dscpw_nonce', 'security' );
            // Add new condition
            $get_condition = filter_input( INPUT_GET, 'condition', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $get_count = filter_input( INPUT_GET, 'count', FILTER_SANITIZE_NUMBER_INT );
            $condition = ( isset( $get_condition ) ? sanitize_text_field( $get_condition ) : '' );
            $count = ( isset( $get_count ) ? sanitize_text_field( $get_count ) : '' );
            $html = '';
            if ( 'billing_country' === $condition ) {
                $html .= wp_json_encode( $this->dscpw_get_country_list( $count, [], true ) );
            } elseif ( 'billing_first_name' === $condition ) {
                $html .= 'input';
            } elseif ( 'billing_last_name' === $condition ) {
                $html .= 'input';
            } elseif ( 'billing_company' === $condition ) {
                $html .= 'input';
            } elseif ( 'billing_address_1' === $condition ) {
                $html .= 'input';
            } elseif ( 'billing_address_2' === $condition ) {
                $html .= 'input';
            } elseif ( 'billing_city' === $condition ) {
                $html .= 'textarea';
            } elseif ( 'billing_postcode' === $condition ) {
                $html .= 'textarea';
            } elseif ( 'shipping_country' === $condition ) {
                $html .= wp_json_encode( $this->dscpw_get_country_list( $count, [], true ) );
            } elseif ( 'shipping_first_name' === $condition ) {
                $html .= 'input';
            } elseif ( 'shipping_last_name' === $condition ) {
                $html .= 'input';
            } elseif ( 'shipping_company' === $condition ) {
                $html .= 'input';
            } elseif ( 'shipping_address_1' === $condition ) {
                $html .= 'input';
            } elseif ( 'shipping_address_2' === $condition ) {
                $html .= 'input';
            } elseif ( 'shipping_city' === $condition ) {
                $html .= 'textarea';
            } elseif ( 'shipping_postcode' === $condition ) {
                $html .= 'textarea';
            } elseif ( 'product' === $condition ) {
                $html .= wp_json_encode( $this->dscpw_get_product_list_select_box( $count, true ) );
            } elseif ( 'variable_product' === $condition ) {
                $html .= wp_json_encode( $this->dscpw_get_varible_product_select_box( $count, true ) );
            } elseif ( 'cart_total' === $condition ) {
                $html .= 'input';
            } elseif ( 'cart_totalafter' === $condition ) {
                $html .= 'input';
            } elseif ( 'shipping_method' === $condition ) {
                $html .= wp_json_encode( $this->dscpw_get_shipping_methods_list( $count, [], true ) );
            } elseif ( 'day_of_week' === $condition ) {
                $html .= wp_json_encode( $this->dscpw_get_day_of_week_list( $count, [], true ) );
            } elseif ( 'date' === $condition ) {
                $html .= 'input';
            }
            echo wp_kses( $html, self::dscpw_allowed_html_tags() );
            wp_die();
            // this is required to terminate immediately and return a proper response
        }

        /**
         * Display payment action fields
         *
         * @since 1.0.0
         *
         * @uses  dscpw_get_payment_gateway_list()
         * 
         */
        public function dscpw_conditional_payments_actions_values_ajax() {
            // Security check
            check_ajax_referer( 'dscpw_nonce', 'security' );
            // Add new action
            $get_actions = filter_input( INPUT_GET, 'payment_action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $get_count = filter_input( INPUT_GET, 'payment_count', FILTER_SANITIZE_NUMBER_INT );
            $actions = ( isset( $get_actions ) ? sanitize_text_field( $get_actions ) : '' );
            $count = ( isset( $get_count ) ? sanitize_text_field( $get_count ) : '' );
            $html = '';
            if ( 'enable_payments' === $actions || 'disable_payments' === $actions ) {
                $html .= wp_json_encode( $this->dscpw_get_payment_gateway_list( $count, [], true ) );
            }
            if ( 'add_payment_method_fee' === $actions ) {
                $html .= wp_json_encode( $this->dscpw_get_payment_gateway_list_payment_fee( $count, [], true ) );
            }
            echo wp_kses( $html, self::dscpw_allowed_html_tags() );
            wp_die();
            // this is required to terminate immediately and return a proper response
        }

        /**
         * Get country list
         *
         * @param string $count
         * @param array  $selected
         * @param bool   $json
         *
         * @return string $html
         * @since  1.0.0
         *
         * @uses   WC_Countries() class
         *
         */
        public function dscpw_get_country_list( $count = '', $selected = array(), $json = false ) {
            $countries_obj = new WC_Countries();
            $getCountries = $countries_obj->__get( 'countries' );
            $html = '<select name="payment[payment_conditions_values][value_' . esc_attr( $count ) . '][]" class="dscpw_select multiselect2 payment_conditions_values payment_conditions_values_country" multiple="multiple">';
            if ( !empty( $getCountries ) ) {
                foreach ( $getCountries as $code => $country ) {
                    $selectedVal = ( is_array( $selected ) && !empty( $selected ) && in_array( $code, $selected, true ) ? 'selected=selected' : '' );
                    $html .= '<option value="' . esc_attr( $code ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $country ) . '</option>';
                }
            }
            $html .= '</select>';
            if ( $json ) {
                return $this->dscpw_convert_array_to_json( $getCountries );
            }
            return $html;
        }

        /**
         * Get product list
         *
         * @param string $count
         * @param bool   $json
         *
         * @return string $html
         * @since  1.0.0
         *
         */
        public function dscpw_get_product_list_select_box( $count = '', $json = false ) {
            $html = '<select id="product-filter-' . esc_attr( $count ) . '" rel-id="' . esc_attr( $count ) . '" name="payment[payment_conditions_values][value_' . esc_attr( $count ) . '][]" class="dscpw_select payment_conditions_values multiselect2 payment_conditions_values_product" data-placeholder="' . esc_attr( 'Please enter 3 or more characters', 'conditional-payments' ) . '" multiple="multiple">';
            $html .= '</select>';
            if ( $json ) {
                return [];
            }
            return $html;
        }

        /**
         * Get product list
         *
         * @param string $count
         * @param array  $selected
         * @param bool   $json
         *
         * @return string $html
         * @since  1.0.0
         *
         * @uses   dscpw_get_default_language_with_sitepress()
         *
         */
        public function dscpw_get_product_list(
            $count = '',
            $selected = array(),
            $action = '',
            $json = false
        ) {
            $default_lang = $this->dscpw_get_default_language_with_sitepress();
            $post_in = '';
            $get_product_list_count = '';
            if ( 'edit' === $action ) {
                $post_in = $selected;
                $get_product_list_count = -1;
            } else {
                $post_in = '';
                $get_product_list_count = 10;
            }
            $get_all_products = new WP_Query(array(
                'post_type'      => 'product',
                'post_status'    => 'publish',
                'posts_per_page' => $get_product_list_count,
                'fields'         => 'ids',
                'orderby'        => 'ID',
                'order'          => 'ASC',
                'post__in'       => $post_in,
            ));
            $html = '<select id="product-filter-' . esc_attr( $count ) . '" rel-id="' . esc_attr( $count ) . '" name="payment[payment_conditions_values][value_' . esc_attr( $count ) . '][]" class="dscpw_select payment_conditions_values multiselect2 payment_conditions_values_product" data-placeholder="' . esc_attr( 'Please enter 3 or more characters', 'conditional-payments' ) . '" multiple="multiple">';
            if ( isset( $get_all_products->posts ) && !empty( $get_all_products->posts ) ) {
                foreach ( $get_all_products->posts as $get_all_product ) {
                    $_product = wc_get_product( $get_all_product );
                    $new_product_id = '';
                    if ( $_product->is_type( 'simple' ) ) {
                        if ( !empty( $sitepress ) ) {
                            $new_product_id = apply_filters(
                                'wpml_object_id',
                                $get_all_product,
                                'product',
                                true,
                                $default_lang
                            );
                        } else {
                            $new_product_id = $get_all_product;
                        }
                        $selected = array_map( 'intval', $selected );
                        $selectedVal = ( is_array( $selected ) && !empty( $selected ) && in_array( $new_product_id, $selected, true ) ? 'selected=selected' : '' );
                        if ( $selectedVal !== '' ) {
                            $html .= '<option value="' . esc_attr( $new_product_id ) . '" ' . esc_attr( $selectedVal ) . '>' . '#' . esc_html( $new_product_id ) . ' - ' . esc_html( get_the_title( $new_product_id ) ) . '</option>';
                        }
                    }
                }
            }
            $html .= '</select>';
            if ( $json ) {
                return [];
            }
            return $html;
        }

        /**
         * Get variable product list select box
         *
         * @param string $count
         * @param bool   $json
         *
         * @return string $html
         *
         * @since  1.0.0
         *
         * @uses   dscpw_get_default_language_with_sitepress()
         */
        public function dscpw_get_varible_product_select_box( $count = '', $json = false ) {
            $html = '<select id="var-product-filter-' . esc_attr( $count ) . '" rel-id="' . esc_attr( $count ) . '" name="payment[payment_conditions_values][value_' . esc_attr( $count ) . '][]" class="dscpw_select payment_conditions_values multiselect2 payment_conditions_values_var_product" multiple="multiple">';
            $html .= '</select>';
            if ( $json ) {
                return [];
            }
            return $html;
        }

        /**
         * Get variable product list
         *
         * @param string $count
         * @param array  $selected
         * @param bool   $json
         *
         * @return string $html
         * @uses   get_available_variations()
         *
         * @since  1.0.0
         *
         * @uses   dscpw_get_default_language_with_sitepress()
         * @uses   wc_get_product()
         * @uses   WC_Product::is_type()
         */
        public function dscpw_get_varible_product_list(
            $count = '',
            $selected = array(),
            $action = '',
            $json = false
        ) {
            global $sitepress;
            $default_lang = $this->dscpw_get_default_language_with_sitepress();
            $post_in = '';
            $get_varible_product_list_count = '';
            if ( 'edit' === $action ) {
                $post_in = $selected;
                $get_varible_product_list_count = -1;
            } else {
                $post_in = '';
                $get_varible_product_list_count = 10;
            }
            $get_all_products = new WP_Query(array(
                'post_type'      => 'product_variation',
                'post_status'    => 'publish',
                'posts_per_page' => $get_varible_product_list_count,
                'orderby'        => 'ID',
                'order'          => 'ASC',
                'post__in'       => $post_in,
            ));
            $html = '<select id="var-product-filter-' . esc_attr( $count ) . '" rel-id="' . esc_attr( $count ) . '" name="payment[payment_conditions_values][value_' . esc_attr( $count ) . '][]" class="dscpw_select payment_conditions_values multiselect2 payment_conditions_values_var_product" multiple="multiple">';
            if ( !empty( $get_all_products->posts ) ) {
                foreach ( $get_all_products->posts as $post ) {
                    $_product = wc_get_product( $post->ID );
                    $new_product_id = '';
                    if ( $_product instanceof WC_Product ) {
                        if ( !empty( $sitepress ) ) {
                            $new_product_id = apply_filters(
                                'wpml_object_id',
                                $post->ID,
                                'product_variation',
                                true,
                                $default_lang
                            );
                        } else {
                            $new_product_id = $post->ID;
                        }
                        $selected = array_map( 'intval', $selected );
                        $selectedVal = ( is_array( $selected ) && !empty( $selected ) && in_array( $new_product_id, $selected, true ) ? 'selected=selected' : '' );
                        if ( '' !== $selectedVal ) {
                            $html .= '<option value="' . esc_attr( $new_product_id ) . '" ' . esc_attr( $selectedVal ) . '>' . '#' . esc_html( $new_product_id ) . ' - ' . esc_html( get_the_title( $new_product_id ) ) . '</option>';
                        }
                    }
                }
            }
            $html .= '</select>';
            if ( $json ) {
                return [];
            }
            return $html;
        }

        /**
         * Display product list based product specific option
         *
         * @return string $html
         * @uses   dscpw_get_default_language_with_sitepress()
         * @uses   wc_get_product()
         * @uses   dscpw_allowed_html_tags()
         *
         * @since  1.0.0
         *
         */
        public function dscpw_conditional_payments_product_list_ajax() {
            // Security check
            check_ajax_referer( 'dscpw_nonce', 'security' );
            // Get products list
            global $sitepress;
            $default_lang = $this->dscpw_get_default_language_with_sitepress();
            $json = true;
            $filter_product_list = [];
            $request_value = filter_input( INPUT_GET, 'value', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $posts_per_page = filter_input( INPUT_GET, 'posts_per_page', FILTER_VALIDATE_INT );
            $_page = filter_input( INPUT_GET, '_page', FILTER_SANITIZE_NUMBER_INT );
            $post_value = ( isset( $request_value ) ? sanitize_text_field( $request_value ) : '' );
            $baselang_product_ids = array();
            function dscpw_posts_wheres(  $where, $wp_query  ) {
                global $wpdb;
                $search_term = $wp_query->get( 'search_pro_title' );
                if ( isset( $search_term ) ) {
                    $search_term_like = $wpdb->esc_like( $search_term );
                    $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $search_term_like ) . '%\'';
                }
                return $where;
            }

            $product_args = array(
                'post_type'        => 'product',
                'posts_per_page'   => $posts_per_page,
                'offset'           => ($_page - 1) * $posts_per_page,
                'search_pro_title' => $post_value,
                'post_status'      => 'publish',
                'orderby'          => 'title',
                'order'            => 'ASC',
            );
            add_filter(
                'posts_where',
                'dscpw_posts_wheres',
                10,
                2
            );
            $wp_query = new WP_Query($product_args);
            remove_filter(
                'posts_where',
                'dscpw_posts_wheres',
                10,
                2
            );
            $get_all_products = $wp_query->posts;
            if ( isset( $get_all_products ) && !empty( $get_all_products ) ) {
                foreach ( $get_all_products as $get_all_product ) {
                    $_product = wc_get_product( $get_all_product->ID );
                    if ( $_product->is_type( 'simple' ) ) {
                        if ( !empty( $sitepress ) ) {
                            $defaultlang_product_id = apply_filters(
                                'wpml_object_id',
                                $get_all_product->ID,
                                'product',
                                true,
                                $default_lang
                            );
                        } else {
                            $defaultlang_product_id = $get_all_product->ID;
                        }
                        $baselang_product_ids[] = $defaultlang_product_id;
                    }
                }
            }
            $html = '';
            if ( isset( $baselang_product_ids ) && !empty( $baselang_product_ids ) ) {
                foreach ( $baselang_product_ids as $baselang_product_id ) {
                    $html .= '<option value="' . $baselang_product_id . '">' . '#' . $baselang_product_id . ' - ' . get_the_title( $baselang_product_id ) . '</option>';
                    $filter_product_list[] = array($baselang_product_id, get_the_title( $baselang_product_id ));
                }
            }
            if ( $json ) {
                echo wp_json_encode( $filter_product_list );
                wp_die();
            }
            echo wp_kses( $html, self::dscpw_allowed_html_tags() );
            wp_die();
        }

        /**
         * Display variable product list based product specific option
         *
         * @return string $html
         * @uses   dscpw_get_default_language_with_sitepress()
         * @uses   wc_get_product()
         * @uses   dscpw_allowed_html_tags()
         *
         * @since  1.0.0
         *
         */
        public function dscpw_conditional_payments_variable_product_list_ajax() {
            // Security check
            check_ajax_referer( 'dscpw_nonce', 'security' );
            // Get variable products list
            global $sitepress;
            $default_lang = $this->dscpw_get_default_language_with_sitepress();
            $json = true;
            $filter_variable_product_list = [];
            $request_value = filter_input( INPUT_GET, 'value', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $posts_per_page = filter_input( INPUT_GET, 'posts_per_page', FILTER_VALIDATE_INT );
            $_page = filter_input( INPUT_GET, '_page', FILTER_SANITIZE_NUMBER_INT );
            $post_value = ( isset( $request_value ) ? sanitize_text_field( $request_value ) : '' );
            $baselang_product_ids = array();
            function dscpw_posts_wheres(  $where, $wp_query  ) {
                global $wpdb;
                $search_term = $wp_query->get( 'search_pro_title' );
                if ( isset( $search_term ) ) {
                    $search_term_like = $wpdb->esc_like( $search_term );
                    $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $search_term_like ) . '%\'';
                }
                return $where;
            }

            $product_args = array(
                'post_type'        => 'product',
                'posts_per_page'   => $posts_per_page,
                'offset'           => ($_page - 1) * $posts_per_page,
                'search_pro_title' => $post_value,
                'post_status'      => 'publish',
                'orderby'          => 'title',
                'order'            => 'ASC',
            );
            add_filter(
                'posts_where',
                'dscpw_posts_wheres',
                10,
                2
            );
            $get_all_products = new WP_Query($product_args);
            remove_filter(
                'posts_where',
                'dscpw_posts_wheres',
                10,
                2
            );
            if ( !empty( $get_all_products ) ) {
                foreach ( $get_all_products->posts as $get_all_product ) {
                    $_product = wc_get_product( $get_all_product->ID );
                    if ( $_product->is_type( 'variable' ) ) {
                        $variations = $_product->get_available_variations();
                        foreach ( $variations as $value ) {
                            if ( !empty( $sitepress ) ) {
                                $defaultlang_product_id = apply_filters(
                                    'wpml_object_id',
                                    $value['variation_id'],
                                    'product',
                                    true,
                                    $default_lang
                                );
                            } else {
                                $defaultlang_product_id = $value['variation_id'];
                            }
                            $baselang_product_ids[] = $defaultlang_product_id;
                        }
                    }
                }
            }
            $html = '';
            if ( isset( $baselang_product_ids ) && !empty( $baselang_product_ids ) ) {
                foreach ( $baselang_product_ids as $baselang_product_id ) {
                    $html .= '<option value="' . $baselang_product_id . '">' . '#' . $baselang_product_id . ' - ' . get_the_title( $baselang_product_id ) . '</option>';
                    $filter_variable_product_list[] = array($baselang_product_id, get_the_title( $baselang_product_id ));
                }
            }
            if ( $json ) {
                echo wp_json_encode( $filter_variable_product_list );
                wp_die();
            }
            echo wp_kses( $html, self::dscpw_allowed_html_tags() );
            wp_die();
        }

        /**
         * Get Shipping Methods list
         *
         * @param string $count
         * @param array  $selected
         * @param bool   $json
         *
         * @return string $html
         * @since  1.0.0
         *
         * @uses   dscpw_get_default_language_with_sitepress()
         * @uses   dscpw_list_out_shipping()
         *
         */
        public function dscpw_get_shipping_methods_list( $count = '', $selected = array(), $json = false ) {
            $combine_shipping_method_list = $this->dscpw_list_out_shipping( 'general' );
            $html = '<select id="product-filter-' . esc_attr( $count ) . '" rel-id="' . esc_attr( $count ) . '" name="payment[payment_conditions_values][value_' . esc_attr( $count ) . '][]" class="dscpw_select payment_conditions_values multiselect2 payment_conditions_values_shipping_method" multiple="multiple">';
            if ( !empty( $combine_shipping_method_list ) && count( $combine_shipping_method_list ) > 0 ) {
                foreach ( $combine_shipping_method_list as $shipping_id => $shipping_title ) {
                    settype( $shipping_id, 'string' );
                    $selectedVal = ( is_array( $selected ) && !empty( $selected ) && in_array( $shipping_id, $selected, true ) ? 'selected=selected' : '' );
                    $html .= '<option value="' . esc_attr( $shipping_id ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $shipping_title ) . '</option>';
                }
            }
            $html .= '</select>';
            if ( $json ) {
                return $this->dscpw_convert_array_to_json( $combine_shipping_method_list );
            }
            return $html;
        }

        /**
         * Get Days of Week list
         *
         * @param string $count
         * @param array  $selected
         * @param bool   $json
         *
         * @return string $html
         * @since  1.1.1
         *
         * @uses   dscpw_get_default_language_with_sitepress()
         *
         */
        public function dscpw_get_day_of_week_list( $count = '', $selected = array(), $json = false ) {
            $filter_day_of_week = [];
            $get_all_day_of_week = array(
                'sun' => esc_html__( 'Sunday', 'conditional-payments' ),
                'mon' => esc_html__( 'Monday', 'conditional-payments' ),
                'tue' => esc_html__( 'Tuesday', 'conditional-payments' ),
                'wed' => esc_html__( 'Wednesday', 'conditional-payments' ),
                'thu' => esc_html__( 'Thursday', 'conditional-payments' ),
                'fri' => esc_html__( 'Friday', 'conditional-payments' ),
                'sat' => esc_html__( 'Saturday', 'conditional-payments' ),
            );
            $html = '<select rel-id="' . esc_attr( $count ) . '" name="payment[payment_conditions_values][value_' . esc_attr( $count ) . '][]" class="dscpw_select product_fees_conditions_values multiselect2" multiple="multiple">';
            if ( isset( $get_all_day_of_week ) && !empty( $get_all_day_of_week ) ) {
                foreach ( $get_all_day_of_week as $key => $get_day_of_week ) {
                    $selectedVal = ( is_array( $selected ) && !empty( $selected ) && in_array( $key, $selected, true ) ? 'selected=selected' : '' );
                    $html .= '<option value="' . esc_attr( $key ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html__( $get_day_of_week, 'conditional-payments' );
                    $html .= '</option>';
                    $filter_day_of_week[$key] = $get_day_of_week;
                }
            }
            $html .= '</select>';
            if ( $json ) {
                return $this->dscpw_convert_array_to_json( $filter_day_of_week );
            }
            return $html;
        }

        /**
         * List out shipping plugin
         *
         * @param string $args
         *
         * @return array $combine_shipping_method_list
         * @uses  WC_Shipping::get_shipping_methods()
         *
         * @uses  WC_Shipping_Zones::get_zones()
         * @since 1.0.0
         *
         */
        public function dscpw_list_out_shipping( $args ) {
            $dscpw_sm_list = $this->dscpw_compatible_shipping_plugin_list();
            $delivery_zones = WC_Shipping_Zones::get_zones();
            $zone_status_array = array();
            foreach ( $delivery_zones as $the_zone ) {
                foreach ( $the_zone['shipping_methods'] as $val ) {
                    if ( in_array( $val->id, $dscpw_sm_list['dscpw_default_shipping'], true ) ) {
                        if ( 'yes' === $val->enabled ) {
                            if ( 'advanced' === $args ) {
                                if ( isset( $val->cost ) && !empty( $val->cost ) ) {
                                    $default_shipping_unique_id = $val->id . ':' . $val->instance_id;
                                    $zone_status_array[$default_shipping_unique_id] = $the_zone['zone_name'] . ' - ' . $val->title;
                                }
                            } else {
                                $default_shipping_unique_id = $val->id . ':' . $val->instance_id;
                                $zone_status_array[$default_shipping_unique_id] = $the_zone['zone_name'] . ' - ' . $val->title;
                            }
                        }
                    }
                    if ( class_exists( 'Flexible_Shipping_Plugin' ) ) {
                        if ( in_array( $val->id, $dscpw_sm_list['dscpw_compatible_shipping'], true ) ) {
                            if ( 'yes' === $val->enabled ) {
                                if ( 'advanced' === $args ) {
                                    if ( isset( $val->cost ) && !empty( $val->cost ) ) {
                                        $default_shipping_unique_id = $val->id . ':' . $val->instance_id;
                                        $zone_status_array[$default_shipping_unique_id] = $the_zone['zone_name'] . ' - ' . $val->title;
                                    }
                                } else {
                                    $default_shipping_unique_id = $val->id . ':' . $val->instance_id;
                                    $zone_status_array[$default_shipping_unique_id] = $the_zone['zone_name'] . ' - ' . $val->title;
                                }
                            }
                        }
                    }
                }
            }
            // Include default zone shipping methods
            $default_zone = new WC_Shipping_Zone(0);
            // ADD ZONE "0" MANUALLY
            $default_zone_name = $default_zone->get_zone_name();
            $default_zone_shipping_methods = $default_zone->get_shipping_methods();
            if ( !empty( $default_zone_shipping_methods ) && is_array( $default_zone_shipping_methods ) ) {
                foreach ( $default_zone_shipping_methods as $default_zone_shipping_method ) {
                    $method_user_title = $default_zone_shipping_method->get_title();
                    // e.g. "Flat Rate"
                    $method_rate_id = $default_zone_shipping_method->get_rate_id();
                    // e.g. "flat_rate:18"
                    $zone_status_array[$method_rate_id] = $default_zone_name . ' - ' . $method_user_title;
                }
            }
            $default_woo_list = array();
            foreach ( $zone_status_array as $unique_shipping => $zone_id ) {
                $default_woo_list[$unique_shipping] = $zone_id;
            }
            $get_other_shipping_method_list = array();
            $get_shipping_methods_list = WC()->shipping()->get_shipping_methods();
            if ( !empty( $get_shipping_methods_list ) && count( $get_shipping_methods_list ) > 0 ) {
                foreach ( $get_shipping_methods_list as $get_object ) {
                    if ( class_exists( 'Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro' ) ) {
                        if ( in_array( $get_object->id, $dscpw_sm_list['dscpw_afsm'], true ) ) {
                            $afrd = $this->dscpw_custom_other_plugin_query( $get_object->id, 'afrd', $args );
                            if ( !empty( $afrd ) ) {
                                foreach ( $afrd as $afrd_id => $afrd_value ) {
                                    $get_other_shipping_method_list[$afrd_id] = $afrd_value;
                                }
                            }
                        }
                    }
                }
            }
            $combine_shipping_method_list = $default_woo_list + $get_other_shipping_method_list;
            return $combine_shipping_method_list;
        }

        /**
         * Shipping list from other plugin
         *
         * @param int    $plugins_unique_id
         * @param string $other_plugin
         *
         * @return array $tr_shipping_list
         *
         * @since 1.0.0
         *
         */
        public function dscpw_custom_other_plugin_query( $plugins_unique_id, $other_plugin, $args ) {
            $tr_shipping_list = array();
            if ( 'afrd' === $other_plugin ) {
                if ( class_exists( 'Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro' ) ) {
                    if ( class_exists( 'Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin' ) ) {
                        $adrsfwp = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin('', '');
                        $get_all_shipping = $adrsfwp::afrsm_pro_get_shipping_method( 'not_list' );
                    }
                    if ( !empty( $get_all_shipping ) ) {
                        foreach ( $get_all_shipping as $get_all_shipping_data ) {
                            $unique_shipping_id = $plugins_unique_id . ':' . $get_all_shipping_data->ID;
                            $sm_cost = get_post_meta( $get_all_shipping_data->ID, 'sm_product_cost', true );
                            if ( 'advanced' === $args ) {
                                if ( !empty( $sm_cost ) || '0' !== $sm_cost ) {
                                    $tr_shipping_list[$unique_shipping_id] = $get_all_shipping_data->post_title;
                                }
                            } else {
                                $tr_shipping_list[$unique_shipping_id] = $get_all_shipping_data->post_title;
                            }
                        }
                    }
                }
            }
            return $tr_shipping_list;
        }

        /**
         * Compatible shipping plugin list
         *
         * @return array $retun_pram
         *
         * @since 1.0.0
         *
         */
        public function dscpw_compatible_shipping_plugin_list() {
            $dscpw_default_sm_list = array('flat_rate', 'free_shipping', 'local_pickup');
            $dscpw_afsm = array('advanced_flat_rate_shipping');
            $dscpw_all_sm_list = array(
                'flat_rate',
                'free_shipping',
                'local_pickup',
                'advanced_flat_rate_shipping',
                'flexible_shipping_single'
            );
            $retun_pram = array(
                'dscpw_default_shipping'    => $dscpw_default_sm_list,
                'dscpw_afsm'                => $dscpw_afsm,
                'dscpw_compatible_shipping' => $dscpw_all_sm_list,
            );
            return $retun_pram;
        }

        /**
         * Get Payment Methods list
         *
         * @param string $count
         * @param array  $selected
         * @param bool   $json
         *
         * @return string $html
         * @since  1.1.3
         *
         * @uses   WC_Session::get()
         *
         */
        public function dscpw_get_payment_gateway_list( $count = '', $selected = array(), $json = false ) {
            $filter_payment_gateway = [];
            $chosen_payment_method = WC()->payment_gateways->payment_gateways();
            $html = '<select rel-id="' . esc_attr( $count ) . '" name="cp_actions[payment_actions_values][value_' . esc_attr( $count ) . '][]" class="dscpw_select payment_actions_values multiselect2 payment_actions_values_payment_gateway" multiple="multiple">';
            if ( isset( $chosen_payment_method ) && !empty( $chosen_payment_method ) ) {
                foreach ( $chosen_payment_method as $chosen_payment_method_key ) {
                    if ( isset( $chosen_payment_method_key->enabled ) && 'yes' === $chosen_payment_method_key->enabled ) {
                        $selectedVal = ( !empty( $selected ) && in_array( $chosen_payment_method_key->id, $selected, true ) ? 'selected=selected' : '' );
                        $html .= '<option value="' . esc_attr( $chosen_payment_method_key->id ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $chosen_payment_method_key->title ) . '</option>';
                        $filter_payment_gateway[$chosen_payment_method_key->id] = $chosen_payment_method_key->title;
                    }
                }
            }
            $html .= '</select>';
            if ( $json ) {
                return $this->dscpw_convert_array_to_json( $filter_payment_gateway );
            }
            return $html;
        }

        /**
         * Get Payment Methods list with payment fee
         *
         * @param string $count
         * @param array  $selected
         * @param bool   $json
         *
         * @return string $html
         * @since  1.1.3
         *
         * @uses   WC_Session::get()
         *
         */
        public function dscpw_get_payment_gateway_list_payment_fee( $count = '', $selected = array(), $json = false ) {
            $filter_payment_gateway = [];
            $tax_classes = WC_Tax::get_tax_classes();
            $chosen_payment_method = WC()->payment_gateways->payment_gateways();
            $selected_payment = ( isset( $selected['payment'] ) && !empty( $selected['payment'] ) ? $selected['payment'] : array() );
            $fee_title = ( isset( $selected['fee-title'] ) && !empty( $selected['fee-title'] ) ? $selected['fee-title'] : '' );
            $amount = ( isset( $selected['amount'] ) && !empty( $selected['amount'] ) ? $selected['amount'] : '' );
            $per_cur = ( isset( $selected['per-cur'] ) && !empty( $selected['per-cur'] ) ? $selected['per-cur'] : '' );
            $tax = ( isset( $selected['tax'] ) && !empty( $selected['tax'] ) ? $selected['tax'] : '' );
            $html = '<div class="dscpw-apmf-section">';
            $html .= '<select rel-id="' . esc_attr( $count ) . '" name="cp_actions[payment_actions_values][value_' . esc_attr( $count ) . '][payment][]" class="dscpw_select payment_actions_values multiselect2 payment_actions_values_payment_gateway" multiple="multiple">';
            if ( isset( $chosen_payment_method ) && !empty( $chosen_payment_method ) ) {
                foreach ( $chosen_payment_method as $chosen_payment_method_key ) {
                    if ( isset( $chosen_payment_method_key->enabled ) && 'yes' === $chosen_payment_method_key->enabled ) {
                        $selectedVal = ( !empty( $selected_payment ) && in_array( $chosen_payment_method_key->id, $selected_payment, true ) ? 'selected=selected' : '' );
                        $html .= '<option value="' . esc_attr( $chosen_payment_method_key->id ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $chosen_payment_method_key->title ) . '</option>';
                        $filter_payment_gateway['payment'][$chosen_payment_method_key->id] = $chosen_payment_method_key->title;
                    }
                }
            }
            $html .= '</select>';
            $html .= '<div class="dscpw-apmf-item">
							<input type="text" rel-id="' . esc_attr( $count ) . '" class="dscpw-apmf-title" placeholder="Fee Title" name="cp_actions[payment_actions_values][value_' . esc_attr( $count ) . '][fee-title]" value="' . esc_attr( $fee_title ) . '">
						</div>
						<div class="dscpw-apmf-item">
							<input type="text" rel-id="' . esc_attr( $count ) . '" class="dscpw-apmf-amount" placeholder="Amount" name="cp_actions[payment_actions_values][value_' . esc_attr( $count ) . '][amount]" value="' . esc_attr( $amount ) . '">
							<select class="dscpw-apmf-rp" rel-id="' . esc_attr( $count ) . '" name="cp_actions[payment_actions_values][value_' . esc_attr( $count ) . '][per-cur]">';
            $html .= '<option value="$" ' . (( "\$" === $per_cur ? 'selected=selected' : "" )) . '>$</option>';
            $html .= '<option value="%" ' . (( "%" === $per_cur ? 'selected=selected' : "" )) . '>%</option>';
            $html .= '</select>
						</div>
						<div class="dscpw-apmf-item">
							<select class="dscpw-apmf-tax" rel-id="' . esc_attr( $count ) . '" name="cp_actions[payment_actions_values][value_' . esc_attr( $count ) . '][tax]">';
            $html .= '<option value="_none" ' . (( "_none" === $tax ? 'selected=selected' : "" )) . ' >' . esc_html( '- Not taxable -' ) . '</option>';
            $html .= '<option value="standard" ' . (( "standard" === $tax ? 'selected=selected' : "" )) . ' >' . esc_html( 'Standard' ) . '</option>';
            foreach ( $tax_classes as $tax_class ) {
                $selectedTax = ( !empty( $tax_class ) && sanitize_title( $tax_class ) === $tax ? 'selected=selected' : '' );
                $html .= '<option value="' . esc_attr( sanitize_title( $tax_class ) ) . '" ' . esc_attr( $selectedTax ) . '>' . esc_html( $tax_class ) . '</option>';
                $filter_payment_gateway['apf']['tax'][sanitize_title( $tax_class )] = $tax_class;
            }
            $html .= '</select>
						</div>
					</div>';
            $filter_payment_gateway['apf']['fee-title'] = $fee_title;
            $filter_payment_gateway['apf']['amount'] = $amount;
            $filter_payment_gateway['apf']['per-cur'] = $per_cur;
            if ( $json ) {
                return $this->dscpw_lpf_convert_array_to_json( $filter_payment_gateway );
            }
            return $html;
        }

        /**
         * Convert array to json
         *
         * @param array $arr
         *
         * @return array $filter_data
         * @since 1.0.0
         *
         */
        public function dscpw_lpf_convert_array_to_json( $arr ) {
            $filter_data = [];
            if ( isset( $arr['payment'] ) && !empty( $arr['payment'] ) ) {
                foreach ( $arr['payment'] as $key => $value ) {
                    $option = [];
                    $option['name'] = $value;
                    $option['attributes']['value'] = $key;
                    $filter_data['payment'][] = $option;
                }
            }
            if ( isset( $arr['apf'] ) && !empty( $arr['apf'] ) ) {
                $filter_data['apf']['fee-title'] = $arr['apf']['fee-title'];
                $filter_data['apf']['amount'] = $arr['apf']['amount'];
                $filter_data['apf']['per-cur'] = $arr['apf']['per-cur'];
                foreach ( $arr['apf']['tax'] as $key => $value ) {
                    $option = [];
                    $option['name'] = $value;
                    $option['attributes']['value'] = $key;
                    $filter_data['apf']['tax'][] = $option;
                }
            }
            return $filter_data;
        }

        /**
         * Convert array to json
         *
         * @param array $arr
         * @param string $condition
         *
         * @return array $filter_data
         * @since 1.0.0
         *
         */
        public function dscpw_convert_array_to_json( $arr, $condition = '' ) {
            $filter_data = [];
            if ( 'time' === $condition ) {
                foreach ( $arr as $key => $value ) {
                    foreach ( $value as $val_key => $times ) {
                        $option = [];
                        $option['name'] = $times;
                        $option['attributes']['value'] = $val_key;
                        $filter_data[$key][] = $option;
                    }
                }
            } else {
                foreach ( $arr as $key => $value ) {
                    $option = [];
                    $option['name'] = $value;
                    $option['attributes']['value'] = $key;
                    $filter_data[] = $option;
                }
            }
            return $filter_data;
        }

        /**
         * Get default site language
         *
         * @return string $default_lang
         *
         * @since  1.0.0
         */
        public function dscpw_get_default_language_with_sitepress() {
            global $sitepress;
            if ( !empty( $sitepress ) ) {
                $default_lang = $sitepress->get_default_language();
            } else {
                $default_lang = $this->dscpw_get_current_site_language();
            }
            return $default_lang;
        }

        /**
         * Get current site language
         *
         * @return string $default_lang
         * @since 1.0.0
         *
         */
        public function dscpw_get_current_site_language() {
            $get_site_language = get_bloginfo( "language" );
            if ( false !== strpos( $get_site_language, '-' ) ) {
                $get_site_language_explode = explode( '-', $get_site_language );
                $default_lang = $get_site_language_explode[0];
            } else {
                $default_lang = $get_site_language;
            }
            return $default_lang;
        }

        /**
         * Get all conditional payments rules
         *
         * @return array|object $get_all_conditions
         *
         * @since  1.0.0
         *
         */
        public static function dscpw_get_conditional_payments_rules() {
            $cp_args = array(
                'post_type'      => self::post_type,
                'posts_per_page' => -1,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
                'post_status'    => 'publish',
            );
            $get_all_conditions = new WP_Query($cp_args);
            $get_all_conditions = $get_all_conditions->get_posts();
            return $get_all_conditions;
        }

        /**
         * Change rule status from listing page
         *
         * @since  1.1.2
         *
         * @uses   dscpw_get_default_language_with_sitepress()
         *
         */
        public function dscpw_change_status_from_listing_page() {
            // Security check
            check_ajax_referer( 'dscpw_nonce', 'security' );
            // Enable & disable rule status
            global $sitepress;
            $default_lang = $this->dscpw_get_default_language_with_sitepress();
            $get_current_rule_id = filter_input( INPUT_GET, 'current_rule_id', FILTER_SANITIZE_NUMBER_INT );
            if ( !empty( $sitepress ) ) {
                $get_current_rule_id = apply_filters(
                    'wpml_object_id',
                    $get_current_rule_id,
                    self::post_type,
                    true,
                    $default_lang
                );
            } else {
                $get_current_rule_id = $get_current_rule_id;
            }
            $get_current_value = filter_input( INPUT_GET, 'current_value', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $get_post_id = ( isset( $get_current_rule_id ) ? absint( $get_current_rule_id ) : '' );
            if ( empty( $get_post_id ) ) {
                echo '<strong>' . esc_html__( 'Something went wrong', 'conditional-payments' ) . '</strong>';
                wp_die();
            }
            $current_value = ( isset( $get_current_value ) ? sanitize_text_field( $get_current_value ) : '' );
            if ( 'true' === $current_value ) {
                $post_args = array(
                    'ID'          => $get_post_id,
                    'post_status' => 'publish',
                    'post_type'   => self::post_type,
                );
                $post_update = wp_update_post( $post_args );
                update_post_meta( $get_post_id, 'dscpw_cp_status', 'on' );
            } else {
                $post_args = array(
                    'ID'          => $get_post_id,
                    'post_status' => 'draft',
                    'post_type'   => self::post_type,
                );
                $post_update = wp_update_post( $post_args );
                update_post_meta( $get_post_id, 'dscpw_cp_status', 'off' );
            }
            if ( !empty( $post_update ) ) {
                echo esc_html__( 'Rule status changed successfully.', 'conditional-payments' );
            } else {
                echo esc_html__( 'Something went wrong', 'conditional-payments' );
            }
            wp_die();
        }

        /**
         * Allowed html tags used for wp_kses function
         *
         * @return array
         * @since     1.0.0
         *
         */
        public static function dscpw_allowed_html_tags() {
            $allowed_tags = array(
                'a'        => array(
                    'href'         => array(),
                    'title'        => array(),
                    'class'        => array(),
                    'target'       => array(),
                    'data-tooltip' => array(),
                ),
                'ul'       => array(
                    'class' => array(),
                ),
                'li'       => array(
                    'class' => array(),
                ),
                'div'      => array(
                    'class' => array(),
                    'id'    => array(),
                ),
                'select'   => array(
                    'rel-id'   => array(),
                    'id'       => array(),
                    'name'     => array(),
                    'class'    => array(),
                    'multiple' => array(),
                    'style'    => array(),
                ),
                'input'    => array(
                    'id'          => array(),
                    'value'       => array(),
                    'name'        => array(),
                    'class'       => array(),
                    'type'        => array(),
                    'data-index'  => array(),
                    'style'       => array(),
                    'placeholder' => array(),
                ),
                'textarea' => array(
                    'id'    => array(),
                    'name'  => array(),
                    'class' => array(),
                ),
                'option'   => array(
                    'id'       => array(),
                    'selected' => array(),
                    'name'     => array(),
                    'value'    => array(),
                ),
                'br'       => array(),
                'p'        => array(),
                'b'        => array(
                    'style' => array(),
                ),
                'em'       => array(),
                'strong'   => array(),
                'i'        => array(
                    'class' => array(),
                ),
                'span'     => array(
                    'class' => array(),
                ),
                'small'    => array(
                    'class' => array(),
                ),
                'label'    => array(
                    'class' => array(),
                    'id'    => array(),
                    'for'   => array(),
                ),
            );
            return $allowed_tags;
        }

    }

}