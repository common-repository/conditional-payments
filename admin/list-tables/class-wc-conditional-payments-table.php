<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * DSCPW_Conditional_Payments_Table class.
 *
 * @extends WP_List_Table
 */
if ( ! class_exists( 'DSCPW_Conditional_Payments_Table' ) ) {

	class DSCPW_Conditional_Payments_Table extends WP_List_Table {

		const post_type = 'wc_dscpw';
		private static $wc_whsm_found_items = 0;
		private static $admin_object = null;

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			parent::__construct( array(
				'singular' => 'post',
				'plural'   => 'posts',
				'ajax'     => false
			) );
			
			self::$admin_object = new DSCPW_Conditional_Payments_Admin( "", "" );
		}

		/**
		 * get_columns function.
		 *
		 * @return  array
		 * @since 1.0.0
		 *
		 */
		public function get_columns() {
			return array(
				'cb'                => '<input type="checkbox" />',
				'title'             => esc_html__( 'Title', 'conditional-payments' ),
				'status'            => esc_html__( 'Status', 'conditional-payments' ),
				'date'              => esc_html__( 'Date', 'conditional-payments' ),
			);
		}

		/**
		 * get_sortable_columns function.
		 *
		 * @return array
		 * @since 1.0.0
		 *
		 */
		protected function get_sortable_columns() {
			$columns = array(
				'title'  => array( 'title', true ),
				'date'   => array( 'date', false ),
			);

			return $columns;
		}

		/**
		 * Get Methods to display
		 *
		 * @since 1.0.0
		 */
		public function prepare_items() {
			$this->prepare_column_headers();
			$per_page = $this->get_items_per_page( 'whsm_per_page' );

			$get_search  = filter_input( INPUT_POST, 's', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$get_orderby = filter_input( INPUT_GET, 'orderby', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$get_order   = filter_input( INPUT_GET, 'order', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

			$args = array(
				'posts_per_page' => $per_page,
				'orderby'        => 'ID',
				'order'          => 'DESC',
				'offset'         => ( $this->get_pagenum() - 1 ) * $per_page,
			);

			if ( isset( $get_search ) && ! empty( $get_search ) ) {
				$args['s'] = trim( wp_unslash( $get_search ) );
			}

			if ( isset( $get_orderby ) && ! empty( $get_orderby ) ) {
				if ( 'title' === $get_orderby ) {
					$args['orderby'] = 'title';
				} elseif ( 'amount' === $get_orderby ) {
					$args['meta_key'] = 'sm_product_cost'; // phpcs:ignore
					$args['orderby']  = 'meta_value_num';
				} elseif ( 'date' === $get_orderby ) {
					$args['orderby'] = 'date';
				}
			}

			if ( isset( $get_order ) && ! empty( $get_order ) ) {
				if ( 'asc' === strtolower( $get_order ) ) {
					$args['order'] = 'ASC';
				} elseif ( 'desc' === strtolower( $get_order ) ) {
					$args['order'] = 'DESC';
				}
			}

			$this->items = $this->whsm_find( $args );

			$total_items = $this->whsm_count();

			$total_pages = ceil( $total_items / $per_page );

			$this->set_pagination_args( array(
				'total_items' => $total_items,
				'total_pages' => $total_pages,
				'per_page'    => $per_page,
			) );
		}

		/**
		 */
		public function no_items() {
			esc_html_e( 'No rule found.', 'conditional-payments' );
		}

		/**
		 * Checkbox column
		 *
		 * @param string
		 *
		 * @return mixed
		 * @since 1.0.0
		 *
		 */
		public function column_cb( $item ) {
			if ( ! $item->ID ) {
				return;
			}

			return sprintf( '<input type="checkbox" name="%1$s[]" value="%2$s" />', 'method_id_cb', esc_attr( $item->ID ) );
		}

		/**
		 * Output the shipping name column.
		 *
		 * @param object $item
		 *
		 * @return string
		 * @since 1.0.0
		 *
		 */
		public function column_title( $item ) {
			$edit_method_url = add_query_arg( array(
				'page'   => 'wc-settings',
				'tab'    => 'checkout',
				'section' => 'dscpw_conditional_payments',
				'action' => 'edit',
				'post'   => $item->ID
			), admin_url( 'admin.php' ) );
			$editurl         = $edit_method_url;

			$method_name = '<strong>
                            <a href="' . wp_nonce_url( $editurl, 'edit_' . $item->ID, 'cust_nonce' ) . '" class="row-title">' . esc_html( $item->post_title ) . '</a>
                        </strong>';

			echo wp_kses( $method_name, self::$admin_object->dscpw_allowed_html_tags() );
		}

		/**
		 * Generates and displays row action links.
		 *
		 * @param object $item Link being acted upon.
		 * @param string $column_name Current column name.
		 * @param string $primary Primary column name.
		 *
		 * @return string Row action output for links.
		 * @since 1.0.0
		 *
		 */
		protected function handle_row_actions( $item, $column_name, $primary ) {
			if ( $primary !== $column_name ) {
				return '';
			}

			$edit_method_url = add_query_arg( array(
				'page'   => 'wc-settings',
				'tab'    => 'checkout',
				'section' => 'dscpw_conditional_payments',
				'action' => 'edit',
				'post'   => $item->ID
			), admin_url( 'admin.php' ) );
			$editurl         = $edit_method_url;

			$delete_method_url = add_query_arg( array(
				'page'   => 'wc-settings',
				'tab'    => 'checkout',
				'section' => 'dscpw_conditional_payments',
				'action' => 'delete',
				'post'   => $item->ID
			), admin_url( 'admin.php' ) );
			$delurl            = $delete_method_url;

			$actions              = array();
			$actions['edit']      = '<a href="' . wp_nonce_url( $editurl, 'edit_' . $item->ID, 'cust_nonce' ) . '">' . __( 'Edit', 'conditional-payments' ) . '</a>';
			$actions['delete']    = '<a href="' . wp_nonce_url( $delurl, 'del_' . $item->ID, 'cust_nonce' ) . '">' . __( 'Delete', 'conditional-payments' ) . '</a>';

			return $this->row_actions( $actions );
		}

		/**
		 * Output the method amount column.
		 *
		 * @param object $item
		 *
		 * @return int|float
		 * @since 1.0.0
		 *
		 */
		public function column_shipping_method( $item ) {
			if ( 0 === $item->ID ) {
				return esc_html__( 'Everywhere', 'conditional-payments' );
			}

            $shipping_method_list  = get_post_meta( $item->ID, 'shipping_method_list', true );
			$shipping_array = array();
			if (!empty($shipping_method_list)) {
                foreach ($shipping_method_list as $val) {
                    $shipping_array[] = $val;
                }
            }
            if (!empty($shipping_array)) {
                $shipping_method_title =  implode( ', ', $shipping_array );
                return $shipping_method_title;
            } else {
                return 'N/A';
            }
		}

		/**
		 * Output the method enabled column.
		 *
		 * @param object $item
		 *
		 * @return string
		 */
		public function column_status( $item ) {
			if ( 0 === $item->ID ) {
				return esc_html__( 'Everywhere', 'conditional-payments' );
			}

			$item_status 			= get_post_meta( $item->ID, 'dscpw_cp_status', true );
			$cp_status     	= get_post_status( $item->ID );
			$cp_status_chk 	= ( ( ! empty( $cp_status ) && 'publish' === $cp_status ) || empty( $cp_status ) ) ? 'checked' : '';
			if ( 'on' === $item_status ) {
				$status = '<label class="dscpw_toggle_switch">
								<input type="checkbox" name="dscpw_cp_status" class="dscpw_status_on_listing" value="on" '.esc_attr( $cp_status_chk ).' cp-rule-id="'. esc_attr( $item->ID ) .'">
								<div class="dscpw_toggle_btn"></div>
							</label>';
			} else {
				$status = '<label class="dscpw_toggle_switch">
								<input type="checkbox" name="dscpw_cp_status" class="dscpw_status_on_listing" value="on" '.esc_attr( $cp_status_chk ).' cp-rule-id="'. esc_attr( $item->ID ) .'">
								<div class="dscpw_toggle_btn"></div>
							</label>';
			}

			return $status;
		}

		/**
		 * Output the method amount column.
		 *
		 * @param object $item
		 *
		 * @return mixed $item->post_date;
		 * @since 1.0.0
		 *
		 */
		public function column_date( $item ) {
			if ( 0 === $item->ID ) {
				return esc_html__( 'Everywhere', 'conditional-payments' );
			}

			return $item->post_date;
		}

		/**
		 * Display bulk action in filter
		 *
		 * @return array $actions
		 * @since 1.0.0
		 *
		 */
		public function get_bulk_actions() {
			$actions = array(
				'disable' => esc_html__( 'Disable', 'conditional-payments' ),
				'enable'  => esc_html__( 'Enable', 'conditional-payments' ),
				'delete'  => esc_html__( 'Delete', 'conditional-payments' )
			);

			return $actions;
		}

		/**
		 * Process bulk actions
		 *
		 * @since 1.0.0
		 */
		public function process_bulk_action() {
			$delete_nonce     = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$get_method_id_cb = filter_input( INPUT_POST, 'method_id_cb', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY );
			$method_id_cb     = ! empty( $get_method_id_cb ) ? array_map( 'sanitize_text_field', wp_unslash( $get_method_id_cb ) ) : array();
			$get_section 	  = filter_input( INPUT_GET, 'section', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			
			$action = $this->current_action();

			if ( ! isset( $method_id_cb ) ) {
				return;
			}

			$deletenonce = wp_verify_nonce( $delete_nonce, 'bulk-shippingmethods' );

			if ( ! isset( $deletenonce ) && 1 !== $deletenonce ) {
				return;
			}

			$items = array_filter( array_map( 'absint', $method_id_cb ) );

			if ( ! $items ) {
				return;
			}

			if ( 'delete' === $action ) {
				foreach ( $items as $id ) {
					wp_delete_post( $id );
				}
				self::$admin_object->dscpw_updated_message( 'deleted', $get_section, '' );

				// Clear cache
				delete_transient( 'dscpw_name_address_fields' );
			} elseif ( 'enable' === $action ) {

				foreach ( $items as $id ) {
					$enable_post = array(
						'post_type'   => self::post_type,
						'ID'          => $id,
						'post_status' => 'publish'
					);
					wp_update_post( $enable_post );
				}
				self::$admin_object->dscpw_updated_message( 'enabled', $get_section, '' );
			} elseif ( 'disable' === $action ) {
				foreach ( $items as $id ) {
					$disable_post = array(
						'post_type'   => self::post_type,
						'ID'          => $id,
						'post_status' => 'draft'
					);

					wp_update_post( $disable_post );
				}
				self::$admin_object->dscpw_updated_message( 'disabled', $get_section, '' );
			}
		}

		/**
		 * Find post data
		 *
		 * @param mixed $args
		 *
		 * @return array $posts
		 * @since 1.0.0
		 *
		 */
		public static function whsm_find( $args = '' ) {
			$defaults = array(
				'post_status'    => 'any',
				'posts_per_page' => - 1,
				'offset'         => 0,
				'orderby'        => 'ID',
				'order'          => 'ASC',
			);

			$args = wp_parse_args( $args, $defaults );

			$args['post_type'] = self::post_type;

			$wc_whsm_query = new WP_Query( $args );
			$posts          = $wc_whsm_query->query( $args );

			self::$wc_whsm_found_items = $wc_whsm_query->found_posts;

			return $posts;
		}

		/**
		 * Count post data
		 *
		 * @return string
		 * @since 1.0.0
		 *
		 */
		public static function whsm_count() {
			return self::$wc_whsm_found_items;
		}

		/**
		 * Set column_headers property for table list
		 *
		 * @since 1.0.0
		 */
		protected function prepare_column_headers() {
			$this->_column_headers = array(
				$this->get_columns(),
				array(),
				$this->get_sortable_columns(),
			);
		}
	}
}