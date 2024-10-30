<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * DSCPW_Conditional_Payments_Page class.
 */
if ( !class_exists( 'DSCPW_Conditional_Payments_Page' ) ) {
    class DSCPW_Conditional_Payments_Page {
        /**
         * Output the Admin UI
         *
         * @since 1.0.0
         */
        const post_type = 'wc_dscpw';

        private static $admin_object = null;

        /**
         * Register post type
         *
         * @since 1.0.0
         */
        public static function dscpw_register_post_type() {
            register_post_type( self::post_type, array(
                'labels'          => array(
                    'name'          => __( 'Conditional Payments', 'conditional-payments' ),
                    'singular_name' => __( 'Conditional Payments', 'conditional-payments' ),
                ),
                'rewrite'         => false,
                'query_var'       => false,
                'public'          => false,
                'capability_type' => 'page',
                'capabilities'    => array(
                    'edit_post'          => 'edit_conditional_payments',
                    'read_post'          => 'read_conditional_payments',
                    'delete_post'        => 'delete_conditional_payments',
                    'edit_posts'         => 'edit_conditional_payments',
                    'edit_others_posts'  => 'edit_conditional_payments',
                    'publish_posts'      => 'edit_conditional_payments',
                    'read_private_posts' => 'edit_conditional_payments',
                ),
            ) );
        }

        /**
         * Display output
         *
         * @since 1.0.0
         *
         * @uses DSCPW_Conditional_Payments_Admin
         * @uses dscpw_save_conditional_method
         * @uses dscpw_add_conditional_payments_form
         * @uses dscpw_conditional_method_edit_screen
         * @uses dscpw_delete_conditional_method
         * @uses dscpw_list_conditional_methods_screen
         * @uses DSCPW_Conditional_Payments_Admin::dscpw_updated_message()
         *
         * @access   public
         */
        public static function dscpw_conditional_payments_output() {
            self::$admin_object = new DSCPW_Conditional_Payments_Admin('', '');
            $action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $post_id_request = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
            $cust_nonce = filter_input( INPUT_GET, 'cust_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $get_dscpw_add = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $get_section = filter_input( INPUT_GET, 'section', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $message = filter_input( INPUT_GET, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            if ( isset( $action ) && !empty( $action ) ) {
                if ( 'add' === $action ) {
                    self::dscpw_save_conditional_method();
                    self::dscpw_add_conditional_payments_form();
                } elseif ( 'edit' === $action ) {
                    if ( isset( $cust_nonce ) && !empty( $cust_nonce ) ) {
                        $getnonce = wp_verify_nonce( $cust_nonce, 'edit_' . $post_id_request );
                        if ( isset( $getnonce ) && 1 === $getnonce ) {
                            self::dscpw_conditional_method_edit_screen( $post_id_request );
                        } else {
                            wp_safe_redirect( add_query_arg( array(
                                'page'    => 'wc-settings',
                                'tab'     => 'checkout',
                                'section' => 'dscpw_conditional_payments',
                            ), admin_url( 'admin.php' ) ) );
                            exit;
                        }
                    } elseif ( isset( $get_dscpw_add ) && !empty( $get_dscpw_add ) ) {
                        if ( !wp_verify_nonce( $get_dscpw_add, 'dscpw_add' ) ) {
                            $message = 'nonce_check';
                        } else {
                            self::dscpw_conditional_method_edit_screen( $post_id_request );
                        }
                    }
                } elseif ( 'delete' === $action ) {
                    self::dscpw_delete_conditional_method( $post_id_request );
                } else {
                    self::dscpw_list_conditional_methods_screen();
                }
            } else {
                self::dscpw_list_conditional_methods_screen();
            }
            if ( isset( $message ) && !empty( $message ) ) {
                self::$admin_object->dscpw_updated_message( $message, $get_section, "" );
            }
            // Clear cache
            delete_transient( 'dscpw_name_address_fields' );
        }

        /**
         * Delete shipping method
         *
         * @param int $id
         *
         * @access   public
         * @uses DSCPW_Conditional_Payments_Admin::dscpw_updated_message()
         *
         * @since    1.0.0
         *
         */
        public static function dscpw_delete_conditional_method( $id ) {
            $cust_nonce = filter_input( INPUT_GET, 'cust_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $get_section = filter_input( INPUT_GET, 'section', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $getnonce = wp_verify_nonce( $cust_nonce, 'del_' . $id );
            if ( isset( $getnonce ) && 1 === $getnonce ) {
                wp_delete_post( $id );
                // Clear cache
                delete_transient( 'dscpw_name_address_fields' );
                wp_safe_redirect( add_query_arg( array(
                    'page'    => 'wc-settings',
                    'tab'     => 'checkout',
                    'section' => 'dscpw_conditional_payments',
                    'message' => 'deleted',
                ), admin_url( 'admin.php' ) ) );
                exit;
            } else {
                self::$admin_object->dscpw_updated_message( 'nonce_check', $get_section, "" );
            }
        }

        /**
         * Count total conditional payments methods
         *
         * @return int $conditional_payments_list
         * @since    1.0.0
         *
         */
        public static function dscpw_count_cp_method() {
            $conditional_payments_args = array(
                'post_type'      => self::post_type,
                'post_status'    => array('publish', 'draft'),
                'posts_per_page' => -1,
                'orderby'        => 'ID',
                'order'          => 'DESC',
            );
            $cp_post_query = new WP_Query($conditional_payments_args);
            $conditional_payments_list = $cp_post_query->posts;
            return count( $conditional_payments_list );
        }

        /**
         * Save debug settings
         *
         * @since    1.2.1
         *
         */
        private static function dscpw_save_debug_settings() {
            $get_section = filter_input( INPUT_GET, 'section', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $dscpw_save = filter_input( INPUT_POST, 'dscpw_save_debug', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $woocommerce_save_method_nonce = filter_input( INPUT_POST, 'woocommerce_save_method_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            if ( "dscpw_conditional_payments" === $get_section && isset( $dscpw_save ) ) {
                if ( empty( $woocommerce_save_method_nonce ) || !wp_verify_nonce( sanitize_text_field( $woocommerce_save_method_nonce ), 'woocommerce_save_method' ) ) {
                    self::$admin_object->dscpw_updated_message( 'nonce_check', $get_section, '' );
                }
                $dscpw_enable_debug = filter_input( INPUT_POST, 'dscpw_enable_debug', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                if ( isset( $dscpw_enable_debug ) ) {
                    update_option( 'dscpw_enable_debug', 'yes' );
                } else {
                    update_option( 'dscpw_enable_debug', 'no' );
                }
            }
        }

        /**
         * Save conditional payments method when add or edit
         *
         * @param int $method_id
         *
         * @return bool false when nonce is not verified
         * @uses dscpw_count_cp_method()
         *
         * @since    1.0.0
         *
         * @uses DSCPW_Conditional_Payments_Admin::dscpw_updated_message()
         */
        private static function dscpw_save_conditional_method( $method_id = 0 ) {
            global $sitepress;
            $action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $get_section = filter_input( INPUT_GET, 'section', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $dscpw_save = filter_input( INPUT_POST, 'dscpw_save', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $woocommerce_save_method_nonce = filter_input( INPUT_POST, 'woocommerce_save_method_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            if ( isset( $action ) && !empty( $action ) ) {
                if ( isset( $dscpw_save ) ) {
                    if ( empty( $woocommerce_save_method_nonce ) || !wp_verify_nonce( sanitize_text_field( $woocommerce_save_method_nonce ), 'woocommerce_save_method' ) ) {
                        self::$admin_object->dscpw_updated_message( 'nonce_check', $get_section, '' );
                    }
                    $dscpw_cp_status = filter_input( INPUT_POST, 'dscpw_cp_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                    $dscpw_cp_rule_name = filter_input( INPUT_POST, 'dscpw_cp_rule_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                    $conditional_payments_count = self::dscpw_count_cp_method();
                    settype( $method_id, 'integer' );
                    if ( isset( $dscpw_cp_status ) ) {
                        $post_status = 'publish';
                    } else {
                        $post_status = 'draft';
                    }
                    if ( '' !== $method_id && 0 !== $method_id ) {
                        $dscpw_cp_post = array(
                            'ID'          => $method_id,
                            'post_title'  => sanitize_text_field( $dscpw_cp_rule_name ),
                            'post_status' => $post_status,
                            'menu_order'  => $conditional_payments_count + 1,
                            'post_type'   => self::post_type,
                        );
                        $method_id = wp_update_post( $dscpw_cp_post );
                    } else {
                        $dscpw_cp_post = array(
                            'post_title'  => sanitize_text_field( $dscpw_cp_rule_name ),
                            'post_status' => $post_status,
                            'menu_order'  => $conditional_payments_count + 1,
                            'post_type'   => self::post_type,
                        );
                        $method_id = wp_insert_post( $dscpw_cp_post );
                    }
                    if ( '' !== $method_id && 0 !== $method_id ) {
                        if ( $method_id > 0 ) {
                            // conditions settings
                            $payment = filter_input(
                                INPUT_POST,
                                'payment',
                                FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                                FILTER_REQUIRE_ARRAY
                            );
                            $get_condition_key = filter_input(
                                INPUT_POST,
                                'condition_key',
                                FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                                FILTER_REQUIRE_ARRAY
                            );
                            $get_cost_rule_match = filter_input(
                                INPUT_POST,
                                'cost_rule_match',
                                FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                                FILTER_REQUIRE_ARRAY
                            );
                            $cost_rule_match = ( isset( $get_cost_rule_match ) ? array_map( 'sanitize_text_field', $get_cost_rule_match ) : array() );
                            $paymentArray = array();
                            $conditions_values_array = array();
                            $condition_key = ( isset( $get_condition_key ) ? $get_condition_key : array() );
                            $payment_conditions = $payment['conditional_payments_conditions'];
                            $conditions_is = $payment['payments_conditions_is'];
                            $conditions_values = ( isset( $payment['payment_conditions_values'] ) && !empty( $payment['payment_conditions_values'] ) ? $payment['payment_conditions_values'] : array() );
                            $conditions_size = count( $payment_conditions );
                            foreach ( array_keys( $condition_key ) as $key ) {
                                if ( !array_key_exists( $key, $conditions_values ) ) {
                                    $conditions_values[$key] = array();
                                }
                            }
                            uksort( $conditions_values, 'strnatcmp' );
                            foreach ( $conditions_values as $v ) {
                                $conditions_values_array[] = $v;
                            }
                            for ($i = 0; $i < $conditions_size; $i++) {
                                $paymentArray[] = array(
                                    'conditional_payments_conditions' => $payment_conditions[$i],
                                    'payments_conditions_is'          => $conditions_is[$i],
                                    'payment_conditions_values'       => $conditions_values_array[$i],
                                );
                            }
                            update_post_meta( $method_id, 'cost_rule_match', maybe_serialize( $cost_rule_match ) );
                            update_post_meta( $method_id, 'cp_metabox', $paymentArray );
                            // actions settings
                            $cp_actions = filter_input(
                                INPUT_POST,
                                'cp_actions',
                                FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                                FILTER_REQUIRE_ARRAY
                            );
                            $get_actions_key = filter_input(
                                INPUT_POST,
                                'actions_key',
                                FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                                FILTER_REQUIRE_ARRAY
                            );
                            $paymentActionArray = array();
                            $actions_values_array = array();
                            $actions_key = ( isset( $get_actions_key ) ? $get_actions_key : array() );
                            $payment_actions = $cp_actions['conditional_payments_actions'];
                            $actions_values = ( isset( $cp_actions['payment_actions_values'] ) && !empty( $cp_actions['payment_actions_values'] ) ? $cp_actions['payment_actions_values'] : array() );
                            $conditional_payments_message = '';
                            $actions_size = count( $actions_values );
                            foreach ( array_keys( $actions_key ) as $key ) {
                                if ( !array_key_exists( $key, $actions_values ) ) {
                                    $actions_values[$key] = array();
                                }
                            }
                            uksort( $actions_values, 'strnatcmp' );
                            foreach ( $actions_values as $v ) {
                                $actions_values_array[] = $v;
                            }
                            for ($i = 0; $i < $actions_size; $i++) {
                                $paymentActionArray[] = array(
                                    'conditional_payments_actions' => $payment_actions[$i],
                                    'payment_actions_values'       => $actions_values_array[$i],
                                );
                            }
                            update_post_meta( $method_id, 'cp_actions_metabox', $paymentActionArray );
                            update_post_meta( $method_id, 'cp_message_metabox', $conditional_payments_message );
                            if ( !empty( $sitepress ) ) {
                                do_action(
                                    'wpml_register_single_string',
                                    'conditional-payments',
                                    sanitize_text_field( $dscpw_cp_rule_name ),
                                    sanitize_text_field( $dscpw_cp_rule_name )
                                );
                            }
                            $getSortOrder = get_option( 'dscpw_sortable_order' );
                            if ( !empty( $getSortOrder ) ) {
                                foreach ( $getSortOrder as $getSortOrder_id ) {
                                    settype( $getSortOrder_id, 'integer' );
                                }
                                array_unshift( $getSortOrder, $method_id );
                            }
                            update_option( 'dscpw_sortable_order', $getSortOrder );
                        }
                    } else {
                        echo '<div class="updated error"><p>' . esc_html__( 'Error saving conditional payments.', 'conditional-payments' ) . '</p></div>';
                        return false;
                    }
                    $dscpw_add = wp_create_nonce( 'dscpw_add' );
                    if ( 'add' === $action ) {
                        wp_safe_redirect( add_query_arg( array(
                            'page'     => 'wc-settings',
                            'tab'      => 'checkout',
                            'section'  => 'dscpw_conditional_payments',
                            'action'   => 'edit',
                            'post'     => $method_id,
                            '_wpnonce' => esc_attr( $dscpw_add ),
                            'message'  => 'created',
                        ), admin_url( 'admin.php' ) ) );
                        exit;
                    }
                    if ( 'edit' === $action ) {
                        wp_safe_redirect( add_query_arg( array(
                            'page'     => 'wc-settings',
                            'tab'      => 'checkout',
                            'section'  => 'dscpw_conditional_payments',
                            'action'   => 'edit',
                            'post'     => $method_id,
                            '_wpnonce' => esc_attr( $dscpw_add ),
                            'message'  => 'saved',
                        ), admin_url( 'admin.php' ) ) );
                        exit;
                    }
                }
            }
        }

        /**
         * Edit conditional payments method screen
         *
         * @param string $id
         *
         * @uses dscpw_save_conditional_method()
         * @uses dscpw_cp_edit_method()
         *
         * @since    1.0.0
         *
         */
        public static function dscpw_conditional_method_edit_screen( $id ) {
            self::dscpw_save_conditional_method( $id );
            self::dscpw_cp_edit_method();
        }

        /**
         * Edit conditional payments method
         *
         * @since    1.0.0
         */
        private static function dscpw_cp_edit_method() {
            include plugin_dir_path( __FILE__ ) . 'form-dscpw.php';
        }

        /**
         * List conditional payment methods function.
         *
         * @since    1.0.0
         *
         * @uses DSCPW_Conditional_Payments_Table class
         * @uses DSCPW_Conditional_Payments_Table::process_bulk_action()
         * @uses DSCPW_Conditional_Payments_Table::prepare_items()
         * @uses DSCPW_Conditional_Payments_Table::search_box()
         * @uses DSCPW_Conditional_Payments_Table::display()
         *
         * @access public
         *
         */
        public static function dscpw_list_conditional_methods_screen() {
            if ( !class_exists( 'DSCPW_Conditional_Payments_Table' ) ) {
                require_once plugin_dir_path( dirname( __FILE__ ) ) . 'list-tables/class-wc-conditional-payments-table.php';
            }
            $link = add_query_arg( array(
                'page'    => 'wc-settings',
                'tab'     => 'checkout',
                'section' => 'dscpw_conditional_payments',
                'action'  => 'add',
            ), admin_url( 'admin.php' ) );
            $allowed_tooltip_html = wp_kses_allowed_html( 'post' )['span'];
            // Upgrade to pro popup
            if ( !(cp_fs()->is__premium_only() && cp_fs()->can_use_premium_code()) ) {
                require_once DSCPW_PLUGIN_PATH . 'admin/partials/dots-upgrade-popup.php';
            }
            ?>
			<div class="dscpw-section-left">
	            <h1 class="wp-heading-inline">
					<?php 
            echo esc_html( __( 'Conditional Payments Rules', 'conditional-payments' ) );
            ?>
	            </h1>
	            <a href="<?php 
            echo esc_url( $link );
            ?>"
	               class="page-title-action cpw-btn-with-brand-color"><?php 
            echo esc_html__( 'Add New', 'conditional-payments' );
            ?></a>
				<?php 
            $request_s = filter_input( INPUT_POST, 's', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            if ( isset( $request_s ) && !empty( $request_s ) ) {
                echo sprintf( '<span class="subtitle">' . wp_kses( __( 'Search results for: <strong>%s</strong>', 'conditional-payments' ), array(
                    'strong' => array(),
                ) ) . '</span>', esc_html( $request_s ) );
            }
            ?>
				<?php 
            $DSCPW_Conditional_Payments_Table = new DSCPW_Conditional_Payments_Table();
            $DSCPW_Conditional_Payments_Table->process_bulk_action();
            $DSCPW_Conditional_Payments_Table->prepare_items();
            $DSCPW_Conditional_Payments_Table->search_box( esc_html__( 'Search', 'conditional-payments' ), 'dscpw-payments' );
            $DSCPW_Conditional_Payments_Table->display();
            ?>
			</div>
			<div class="dscpw-section-advanced-settings">
				<h2 class="wp-heading-inline"><?php 
            esc_html_e( 'Advanced Settings', 'conditional-payments' );
            ?></h2>
				<?php 
            ?>
					<table class="form-table table-outer genral-settings-tbl">
						<tbody>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label for="dscpw_enable_debug"><?php 
            esc_html_e( 'Debug Mode', 'conditional-payments' );
            ?><span class="dscpw-pro-label"></span><?php 
            echo wp_kses( wc_help_tip( esc_html__( 'Debug mode shows passed conditions and which actions were run in the checkout.', 'conditional-payments' ) ), array(
                'span' => $allowed_tooltip_html,
            ) );
            ?></label>
								</th>
								<td class="forminp">
									<label class="dscpw_toggle_switch">
										<input type="checkbox" name="dscpw_enable_debug" value="on" disabled>
										<span class="dscpw_toggle_btn dscpw-pro-feature"></span>
									</label>
								</td>
							</tr>
						</tbody>
					</table>
					<p class="submit">
						<input type="submit" class="button button-primary" name="dscpw_save_debug" value="<?php 
            esc_attr_e( 'Save Changes', 'conditional-payments' );
            ?>" disabled>
					</p>
					<?php 
            ?>
			</div>
			<?php 
        }

        /**
         * Add conditional payments methods form function.
         *
         * @since    1.0.0
         */
        public static function dscpw_add_conditional_payments_form() {
            include plugin_dir_path( __FILE__ ) . 'form-dscpw.php';
        }

    }

}