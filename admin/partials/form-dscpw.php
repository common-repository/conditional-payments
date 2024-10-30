<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$get_action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
$get_id = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
/*
 * edit all posted data method define in class-conditional-payments-admin
 */
if ( $get_action === 'edit' ) {
    if ( !empty( $get_id ) && $get_id !== "" ) {
        $get_post_id = ( isset( $get_id ) ? sanitize_text_field( wp_unslash( $get_id ) ) : '' );
        $dscpw_cp_status = get_post_status( $get_post_id );
        $dscpw_cp_rule_name = __( get_the_title( $get_post_id ), 'conditional-payments' );
        $cp_metabox = get_post_meta( $get_post_id, 'cp_metabox', true );
        if ( is_serialized( $cp_metabox ) ) {
            $cp_metabox = maybe_unserialize( $cp_metabox );
        } else {
            $cp_metabox = $cp_metabox;
        }
        $cp_actions_metabox = get_post_meta( $get_post_id, 'cp_actions_metabox', true );
        if ( is_serialized( $cp_actions_metabox ) ) {
            $cp_actions_metabox = maybe_unserialize( $cp_actions_metabox );
        } else {
            $cp_actions_metabox = $cp_actions_metabox;
        }
        $title_text = esc_html__( 'Edit Conditional Payments Rules', 'conditional-payments' );
    }
} else {
    $get_post_id = '';
    $dscpw_cp_status = '';
    $dscpw_cp_rule_name = '';
    $cp_message_metabox = '';
    $dscpw_cp_rule_name = ( !empty( $dscpw_cp_rule_name ) ? esc_attr( stripslashes( $dscpw_cp_rule_name ) ) : '' );
    $title_text = esc_html__( 'Add Conditional Payments Rules', 'conditional-payments' );
}
$dscpw_cp_status = ( !empty( $dscpw_cp_status ) && 'publish' === $dscpw_cp_status || empty( $dscpw_cp_status ) ? 'checked' : '' );
$dscpw_admin_object = new DSCPW_Conditional_Payments_Admin('', '');
$allowed_tooltip_html = wp_kses_allowed_html( 'post' )['span'];
?>
<div class="dscpw-section-main">
	<div class="dscpw-rules-section">
		<h2 class="genral-settings-title"><?php 
echo esc_html__( $title_text, 'conditional-payments' );
?></h2>
		<table class="form-table table-outer genral-settings-tbl">
			<tbody>
				<tr valign="top">
					<th class="titledesc" scope="row">
						<label for="dscpw_cp_status"><?php 
esc_html_e( 'Status', 'conditional-payments' );
echo wp_kses( wc_help_tip( esc_html__( 'Enable or Disable this rule using this button (This rule will work for customers only if it is enabled).', 'conditional-payments' ) ), array(
    'span' => $allowed_tooltip_html,
) );
?></label>
					</th>
					<td class="forminp">
						<label class="dscpw_toggle_switch">
							<input type="checkbox" name="dscpw_cp_status" value="on" <?php 
echo esc_attr( $dscpw_cp_status );
?>>
							<span class="dscpw_toggle_btn"></span>
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th class="titledesc" scope="row">
						<label for="dscpw_cp_rule_name"><?php 
esc_html_e( 'Rule Name', 'conditional-payments' );
echo wp_kses( wc_help_tip( esc_html__( 'This rule name is only for the admin purpose.', 'conditional-payments' ) ), array(
    'span' => $allowed_tooltip_html,
) );
?></label>
					</th>
					<td class="forminp">
						<input type="text" name="dscpw_cp_rule_name" placeholder="<?php 
echo esc_attr( 'Enter rule name' );
?>" value="<?php 
echo esc_attr( $dscpw_cp_rule_name );
?>" required>
					</td>
				</tr>
				<tr valign="top">
					<th class="titledesc" scope="row">
						<label><?php 
esc_html_e( 'Conditions', 'conditional-payments' );
echo wp_kses( wc_help_tip( esc_html__( 'Set conditions for your conditional payments.', 'conditional-payments' ) ), array(
    'span' => $allowed_tooltip_html,
) );
?></label>
					</th>
					<td class="forminp">
						<div class="dscpw-conditional-rules">
							<div class="tap">
								<table id="tbl-condition-payment-rules" class="conditional-payments-tbl table-outer tap-cas form-table">
									<tbody>
									<?php 
if ( isset( $cp_metabox ) && !empty( $cp_metabox ) ) {
    $i = 2;
    foreach ( $cp_metabox as $paymentConditions ) {
        $payment_conditions = ( isset( $paymentConditions['conditional_payments_conditions'] ) ? $paymentConditions['conditional_payments_conditions'] : '' );
        $condition_is = ( isset( $paymentConditions['payments_conditions_is'] ) ? $paymentConditions['payments_conditions_is'] : '' );
        $condtion_value = ( isset( $paymentConditions['payment_conditions_values'] ) ? $paymentConditions['payment_conditions_values'] : array() );
        ?>
										<tr id="row_<?php 
        echo esc_attr( $i );
        ?>" valign="top">
											<th class="titledesc" scope="row">
												<select rel-id="<?php 
        echo esc_attr( $i );
        ?>" name="payment[conditional_payments_conditions][]" id="conditional_payments_conditions_<?php 
        echo esc_attr( $i );
        ?>" class="conditional_payments_conditions">
													<optgroup label="<?php 
        esc_attr_e( 'Product Specific', 'conditional-payments' );
        ?>">
														<option value="product" <?php 
        echo ( 'product' === $payment_conditions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Product', 'conditional-payments' );
        ?></option>
														<option value="variable_product" <?php 
        echo ( 'variable_product' === $payment_conditions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Variable Product', 'conditional-payments' );
        ?></option>
														<?php 
        ?>
																	<option value="product_categories_disabled"><?php 
        esc_html_e( 'Product Categories 🔒', 'conditional-payments' );
        ?></option>
																	<option value="product_tags_disabled"><?php 
        esc_html_e( 'Product Tags 🔒', 'conditional-payments' );
        ?></option>
																	<option value="product_type_disabled"><?php 
        esc_html_e( 'Product Type 🔒', 'conditional-payments' );
        ?></option>
																	<option value="product_visibility_disabled"><?php 
        esc_html_e( 'Product Visibility 🔒', 'conditional-payments' );
        ?></option>
																	<option value="product_quantity_disabled"><?php 
        esc_html_e( 'Product Quantity 🔒', 'conditional-payments' );
        ?></option>
																	<option value="product_stock_status_disabled"><?php 
        esc_html_e( 'Stock Status 🔒', 'conditional-payments' );
        ?></option>
																<?php 
        ?>
													</optgroup>
													<optgroup label="<?php 
        esc_attr_e( 'Cart Specific', 'conditional-payments' );
        ?>">
														<option value="cart_total" <?php 
        echo ( 'cart_total' === $payment_conditions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Cart Subtotal (Before Discount)', 'conditional-payments' );
        ?></option>
														<option value="cart_totalafter" <?php 
        echo ( 'cart_totalafter' === $payment_conditions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Cart Subtotal (After Discount)', 'conditional-payments' );
        ?></option>
														<?php 
        ?>
																	<option value="cart_quantity_disabled"><?php 
        esc_html_e( 'Cart Quantity 🔒', 'conditional-payments' );
        ?></option>
																	<option value="shipping_class_disabled"><?php 
        esc_html_e( 'Shipping Class 🔒', 'conditional-payments' );
        ?></option>
																	<option value="coupon_disabled"><?php 
        esc_html_e( 'Coupon 🔒', 'conditional-payments' );
        ?></option>
																	<option value="total_weight_disabled"><?php 
        esc_html_e( 'Total Weight 🔒', 'conditional-payments' );
        ?></option>
																	<option value="number_of_items_disabled"><?php 
        esc_html_e( 'Number Of Items 🔒', 'conditional-payments' );
        ?></option>
																	<option value="total_volume_disabled"><?php 
        esc_html_e( 'Total Volume 🔒', 'conditional-payments' );
        ?></option>
																<?php 
        ?>
													</optgroup>
													<optgroup label="<?php 
        esc_attr_e( 'Shipping Specific', 'conditional-payments' );
        ?>">
														<option value="shipping_method" <?php 
        echo ( 'shipping_method' === $payment_conditions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Shipping Method', 'conditional-payments' );
        ?></option>
													</optgroup>
													<optgroup label="<?php 
        esc_attr_e( 'Billing Address', 'conditional-payments' );
        ?>">
														<option value="billing_first_name" <?php 
        echo ( 'billing_first_name' === $payment_conditions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'First Name', 'conditional-payments' );
        ?></option>
														<option value="billing_last_name" <?php 
        echo ( 'billing_last_name' === $payment_conditions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Last Name', 'conditional-payments' );
        ?></option>
														<option value="billing_company" <?php 
        echo ( 'billing_company' === $payment_conditions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Company', 'conditional-payments' );
        ?></option>
														<option value="billing_address_1" <?php 
        echo ( 'billing_address_1' === $payment_conditions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Address', 'conditional-payments' );
        ?></option>
														<option value="billing_address_2" <?php 
        echo ( 'billing_address_2' === $payment_conditions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Address 2', 'conditional-payments' );
        ?></option>
														<option value="billing_country" <?php 
        echo ( 'billing_country' === $payment_conditions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Country', 'conditional-payments' );
        ?></option>
														<option value="billing_city" <?php 
        echo ( 'billing_city' === $payment_conditions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'City', 'conditional-payments' );
        ?></option>
														<option value="billing_postcode" <?php 
        echo ( 'billing_postcode' === $payment_conditions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Postcode', 'conditional-payments' );
        ?></option>
														<?php 
        ?>
																	<option value="billing_email_disabled"><?php 
        esc_html_e( 'Email 🔒', 'conditional-payments' );
        ?></option>
																	<option value="billing_phone_disabled"><?php 
        esc_html_e( 'Phone 🔒', 'conditional-payments' );
        ?></option>
																	<option value="previous_order_disabled"><?php 
        esc_html_e( 'Previous Order 🔒', 'conditional-payments' );
        ?></option>
																<?php 
        ?>
													</optgroup>
													<?php 
        ?>
															<optgroup label="<?php 
        esc_attr_e( 'Customer', 'conditional-payments' );
        ?>">
																<option value="customer_authenticated_disabled"><?php 
        esc_html_e( 'Logged in / out 🔒', 'conditional-payments' );
        ?></option>
																<option value="user_disabled"><?php 
        esc_html_e( 'User 🔒', 'conditional-payments' );
        ?></option>
																<option value="user_role_disabled" ><?php 
        esc_html_e( 'User Role 🔒', 'conditional-payments' );
        ?></option>
															</optgroup>
														<?php 
        ?>
													<optgroup label="<?php 
        esc_attr_e( 'Shipping Address', 'conditional-payments' );
        ?>">
														<option value="shipping_first_name" <?php 
        echo ( 'shipping_first_name' === $payment_conditions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'First Name', 'conditional-payments' );
        ?></option>
														<option value="shipping_last_name" <?php 
        echo ( 'shipping_last_name' === $payment_conditions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Last Name', 'conditional-payments' );
        ?></option>
														<option value="shipping_company" <?php 
        echo ( 'shipping_company' === $payment_conditions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Company', 'conditional-payments' );
        ?></option>
														<option value="shipping_address_1" <?php 
        echo ( 'shipping_address_1' === $payment_conditions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Address', 'conditional-payments' );
        ?></option>
														<option value="shipping_address_2" <?php 
        echo ( 'shipping_address_2' === $payment_conditions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Address 2', 'conditional-payments' );
        ?></option>
														<option value="shipping_country" <?php 
        echo ( 'shipping_country' === $payment_conditions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Country', 'conditional-payments' );
        ?></option>
														<option value="shipping_city" <?php 
        echo ( 'shipping_city' === $payment_conditions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'City', 'conditional-payments' );
        ?></option>
														<option value="shipping_postcode" <?php 
        echo ( 'shipping_postcode' === $payment_conditions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Postcode', 'conditional-payments' );
        ?></option>
													</optgroup>
													<optgroup label="<?php 
        esc_attr_e( 'Time Specific', 'conditional-payments' );
        ?>">
														<option value="day_of_week" <?php 
        echo ( 'day_of_week' === $payment_conditions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Day Of Week', 'conditional-payments' );
        ?></option>
														<option value="date" <?php 
        echo ( 'date' === $payment_conditions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Date', 'conditional-payments' );
        ?></option>
														<?php 
        ?>
															<option value="time_disabled"><?php 
        esc_html_e( 'Time 🔒', 'conditional-payments' );
        ?></option>
															<?php 
        ?>
													</optgroup>
												</select>
											</th>
											<td class="select_condition_for_in_notin">
												<?php 
        if ( 'cart_total' === $payment_conditions || 'cart_totalafter' === $payment_conditions || 'cart_quantity' === $payment_conditions || 'previous_order' === $payment_conditions || 'product_quantity' === $payment_conditions || 'total_weight' === $payment_conditions || 'number_of_items' === $payment_conditions || 'total_volume' === $payment_conditions || 'date' === $payment_conditions || 'time' === $payment_conditions ) {
            ?>
												<select name="payment[payments_conditions_is][]"
												        class="payments_conditions_is payments_conditions_is_<?php 
            echo esc_attr( $i );
            ?>">
													<option value="is_equal_to" <?php 
            echo ( 'is_equal_to' === $condition_is ? 'selected' : '' );
            ?>><?php 
            esc_html_e( 'Equal to ( = )', 'conditional-payments' );
            ?></option>
													<option value="less_equal_to" <?php 
            echo ( 'less_equal_to' === $condition_is ? 'selected' : '' );
            ?>><?php 
            esc_html_e( 'Less or Equal to ( <= )', 'conditional-payments' );
            ?></option>
													<option value="less_then" <?php 
            echo ( 'less_then' === $condition_is ? 'selected' : '' );
            ?>><?php 
            esc_html_e( 'Less than ( < )', 'conditional-payments' );
            ?></option>
													<option value="greater_equal_to" <?php 
            echo ( 'greater_equal_to' === $condition_is ? 'selected' : '' );
            ?>><?php 
            esc_html_e( 'Greater or Equal to ( >= )', 'conditional-payments' );
            ?></option>
													<option value="greater_then" <?php 
            echo ( 'greater_then' === $condition_is ? 'selected' : '' );
            ?>><?php 
            esc_html_e( 'Greater than ( > )', 'conditional-payments' );
            ?></option>
													<option value="not_in" <?php 
            echo ( 'not_in' === $condition_is ? 'selected' : '' );
            ?>><?php 
            esc_html_e( 'Not Equal to ( != )', 'conditional-payments' );
            ?></option>
												</select>
												<?php 
        } elseif ( 'product' === $payment_conditions || 'variable_product' === $payment_conditions || 'shipping_method' === $payment_conditions || 'billing_country' === $payment_conditions || 'shipping_country' === $payment_conditions || 'day_of_week' === $payment_conditions || 'product_visibility' === $payment_conditions ) {
            ?>
													<select name="payment[payments_conditions_is][]"
												        class="payments_conditions_is payments_conditions_is_<?php 
            echo esc_attr( $i );
            ?>">
														<option value="is_equal_to" <?php 
            echo ( 'is_equal_to' === $condition_is ? 'selected' : '' );
            ?>><?php 
            esc_html_e( 'Equal to ( = )', 'conditional-payments' );
            ?></option>
														<option value="not_in" <?php 
            echo ( 'not_in' === $condition_is ? 'selected' : '' );
            ?>><?php 
            esc_html_e( 'Not Equal to ( != )', 'conditional-payments' );
            ?></option>
													</select>
													<?php 
        } elseif ( 'billing_email' === $payment_conditions || 'user' === $payment_conditions || 'user_role' === $payment_conditions || 'product_categories' === $payment_conditions || 'product_tags' === $payment_conditions || 'product_type' === $payment_conditions || 'shipping_class' === $payment_conditions || 'coupon' === $payment_conditions || 'product_stock_status' === $payment_conditions ) {
        } elseif ( 'customer_authenticated' === $payment_conditions ) {
        } else {
            ?>
													<select name="payment[payments_conditions_is][]"
												        class="payments_conditions_is payments_conditions_is_<?php 
            echo esc_attr( $i );
            ?>">
														<option value="is_equal_to" <?php 
            echo ( 'is_equal_to' === $condition_is ? 'selected' : '' );
            ?>><?php 
            esc_html_e( 'Equal to ( = )', 'conditional-payments' );
            ?></option>
														<option value="not_in" <?php 
            echo ( 'not_in' === $condition_is ? 'selected' : '' );
            ?>><?php 
            esc_html_e( 'Not Equal to ( != )', 'conditional-payments' );
            ?></option>
														<option value="is_empty" <?php 
            echo ( 'is_empty' === $condition_is ? 'selected' : '' );
            ?>><?php 
            esc_html_e( 'Is Empty', 'conditional-payments' );
            ?></option>
														<option value="is_not_empty" <?php 
            echo ( 'is_not_empty' === $condition_is ? 'selected' : '' );
            ?>><?php 
            esc_html_e( 'Is Not Empty', 'conditional-payments' );
            ?></option>
													</select>
													<?php 
        }
        ?>
											</td>
											<td class="condition-value" id="column_<?php 
        echo esc_attr( $i );
        ?>">
												<?php 
        $html = '';
        $val_class = ( $condition_is === 'is_empty' || $condition_is === 'is_not_empty' ? 'payment_conditions_values is_empty_or_not' : 'payment_conditions_values' );
        if ( 'product' === $payment_conditions ) {
            $html .= $dscpw_admin_object->dscpw_get_product_list( $i, $condtion_value, 'edit' );
        } elseif ( 'variable_product' === $payment_conditions ) {
            $html .= $dscpw_admin_object->dscpw_get_varible_product_list( $i, $condtion_value, 'edit' );
        } elseif ( 'cart_total' === $payment_conditions ) {
            $html .= '<input type = "text" name = "payment[payment_conditions_values][value_' . esc_attr( $i ) . ']" class = "payment_conditions_values" value = "' . esc_attr( $condtion_value ) . '">';
        } elseif ( 'cart_totalafter' === $payment_conditions ) {
            $html .= '<input type="text" name="payment[payment_conditions_values][value_' . esc_attr( $i ) . ']" class="payment_conditions_values" value="' . esc_attr( $condtion_value ) . '">';
            $html .= sprintf( wp_kses( __( '<p><b style="color: red;">Note: </b>This rule will apply when you would apply coupun in front side. <a href="%s" target="_blank">Click Here</a>.</p>', 'conditional-payments' ), array(
                'p' => array(),
                'b' => array(
                    'style' => array(),
                ),
                'a' => array(
                    'href'   => array(),
                    'target' => array(),
                ),
            ) ), esc_url( 'https://docs.thedotstore.com/collection/485-conditional-payments' ) );
        } elseif ( 'shipping_method' === $payment_conditions ) {
            $html .= $dscpw_admin_object->dscpw_get_shipping_methods_list( $i, $condtion_value );
        } elseif ( 'billing_first_name' === $payment_conditions ) {
            $html .= '<input type = "text" name = "payment[payment_conditions_values][value_' . esc_attr( $i ) . ']" class= "' . $val_class . '" value="' . esc_attr( $condtion_value ) . '">';
        } elseif ( 'billing_last_name' === $payment_conditions ) {
            $html .= '<input type = "text" name = "payment[payment_conditions_values][value_' . esc_attr( $i ) . ']" class = "' . $val_class . '" value = "' . esc_attr( $condtion_value ) . '">';
        } elseif ( 'billing_company' === $payment_conditions ) {
            $html .= '<input type = "text" name = "payment[payment_conditions_values][value_' . esc_attr( $i ) . ']" class = "' . $val_class . '" value = "' . esc_attr( $condtion_value ) . '">';
        } elseif ( 'billing_address_1' === $payment_conditions ) {
            $html .= '<input type = "text" name = "payment[payment_conditions_values][value_' . esc_attr( $i ) . ']" class = "' . $val_class . '" value = "' . esc_attr( $condtion_value ) . '">';
        } elseif ( 'billing_address_2' === $payment_conditions ) {
            $html .= '<input type = "text" name = "payment[payment_conditions_values][value_' . esc_attr( $i ) . ']" class = "' . $val_class . '" value = "' . esc_attr( $condtion_value ) . '">';
        } elseif ( 'billing_country' === $payment_conditions ) {
            $html .= $dscpw_admin_object->dscpw_get_country_list( $i, $condtion_value );
        } elseif ( 'billing_city' === $payment_conditions ) {
            $html .= '<textarea name = "payment[payment_conditions_values][value_' . esc_attr( $i ) . ']" class = "' . $val_class . '">' . esc_html( $condtion_value ) . '</textarea>';
            if ( 'is_empty' !== $condition_is && 'is_not_empty' !== $condition_is ) {
                $html .= sprintf( wp_kses( __( '<p><b style="color: red;">Note: </b>Add only one city in a Line. You can add multiple cities in each new line.</p>', 'conditional-payments' ), array(
                    'p' => array(),
                    'b' => array(
                        'style' => array(),
                    ),
                ) ) );
            }
        } elseif ( 'billing_postcode' === $payment_conditions ) {
            $html .= '<textarea name = "payment[payment_conditions_values][value_' . esc_attr( $i ) . ']" class = "' . $val_class . '">' . esc_html( $condtion_value ) . '</textarea>';
            if ( 'is_empty' !== $condition_is && 'is_not_empty' !== $condition_is ) {
                $html .= sprintf( wp_kses( __( '<p><b style="color: red;">Note: </b>Add only one post/zip code in a Line. You can add multiple postcode in each new line.</p>', 'conditional-payments' ), array(
                    'p' => array(),
                    'b' => array(
                        'style' => array(),
                    ),
                ) ) );
            }
        } elseif ( 'shipping_first_name' === $payment_conditions ) {
            $html .= '<input type = "text" name = "payment[payment_conditions_values][value_' . esc_attr( $i ) . ']" class = "' . $val_class . '" value = "' . esc_attr( $condtion_value ) . '">';
        } elseif ( 'shipping_last_name' === $payment_conditions ) {
            $html .= '<input type = "text" name = "payment[payment_conditions_values][value_' . esc_attr( $i ) . ']" class = "' . $val_class . '" value = "' . esc_attr( $condtion_value ) . '">';
        } elseif ( 'shipping_company' === $payment_conditions ) {
            $html .= '<input type = "text" name = "payment[payment_conditions_values][value_' . esc_attr( $i ) . ']" class = "' . $val_class . '" value = "' . esc_attr( $condtion_value ) . '">';
        } elseif ( 'shipping_address_1' === $payment_conditions ) {
            $html .= '<input type = "text" name = "payment[payment_conditions_values][value_' . esc_attr( $i ) . ']" class = "' . $val_class . '" value = "' . esc_attr( $condtion_value ) . '">';
        } elseif ( 'shipping_address_2' === $payment_conditions ) {
            $html .= '<input type = "text" name = "payment[payment_conditions_values][value_' . esc_attr( $i ) . ']" class = "' . $val_class . '" value = "' . esc_attr( $condtion_value ) . '">';
        } elseif ( 'shipping_country' === $payment_conditions ) {
            $html .= $dscpw_admin_object->dscpw_get_country_list( $i, $condtion_value );
        } elseif ( 'shipping_city' === $payment_conditions ) {
            $html .= '<textarea name = "payment[payment_conditions_values][value_' . esc_attr( $i ) . ']" class = "' . $val_class . '">' . esc_html( $condtion_value ) . '</textarea>';
            if ( 'is_empty' !== $condition_is && 'is_not_empty' !== $condition_is ) {
                $html .= sprintf( wp_kses( __( '<p><b style="color: red;">Note: </b>Add only one city in a Line. You can add multiple cities in each new line.</p>', 'conditional-payments' ), array(
                    'p' => array(),
                    'b' => array(
                        'style' => array(),
                    ),
                ) ) );
            }
        } elseif ( 'shipping_postcode' === $payment_conditions ) {
            $html .= '<textarea name = "payment[payment_conditions_values][value_' . esc_attr( $i ) . ']" class = "' . $val_class . '">' . esc_html( $condtion_value ) . '</textarea>';
            if ( 'is_empty' !== $condition_is && 'is_not_empty' !== $condition_is ) {
                $html .= sprintf( wp_kses( __( '<p><b style="color: red;">Note: </b>Add only one post/zip code in a Line. You can add multiple postcode in each new line.</p>', 'conditional-payments' ), array(
                    'p' => array(),
                    'b' => array(
                        'style' => array(),
                    ),
                ) ) );
            }
        } elseif ( 'day_of_week' === $payment_conditions ) {
            $html .= $dscpw_admin_object->dscpw_get_day_of_week_list( $i, $condtion_value );
        } elseif ( 'date' === $payment_conditions ) {
            $html .= '<input type = "text" name = "payment[payment_conditions_values][value_' . esc_attr( $i ) . ']" class = "dscpw_datepicker ' . $val_class . '" value = "' . esc_attr( $condtion_value ) . '">';
        }
        echo wp_kses( $html, $dscpw_admin_object::dscpw_allowed_html_tags() );
        ?>
												<input type="hidden" name="condition_key[value_<?php 
        echo esc_attr( $i );
        ?>]" value="">
											</td>
											<td class="condition-delete-field-outer">
												<span rel-id="<?php 
        echo esc_attr( $i );
        ?>" class="condition-delete-field" title="Delete"><span class="dashicons dashicons-trash"></span></span>
											</td>
										</tr>
										<?php 
        $i++;
    }
} else {
    $i = 1;
    ?>
										<tr id="row_1" valign="top">
											<th class="titledesc" scope="row">
												<select rel-id="1" name="payment[conditional_payments_conditions][]" id="conditional_payments_conditions_<?php 
    echo esc_attr( $i );
    ?>" class="conditional_payments_conditions">
													<optgroup label="<?php 
    esc_attr_e( 'Product Specific', 'conditional-payments' );
    ?>">
														<option value="product"><?php 
    esc_html_e( 'Product', 'conditional-payments' );
    ?></option>
														<option value="variable_product"><?php 
    esc_html_e( 'Variable Product', 'conditional-payments' );
    ?></option>
														<?php 
    ?>
																	<option value="product_categories_disabled"><?php 
    esc_html_e( 'Product Categories 🔒', 'conditional-payments' );
    ?></option>
																	<option value="product_tags_disabled"><?php 
    esc_html_e( 'Product Tags 🔒', 'conditional-payments' );
    ?></option>
																	<option value="product_type_disabled"><?php 
    esc_html_e( 'Product Type 🔒', 'conditional-payments' );
    ?></option>
																	<option value="product_visibility_disabled"><?php 
    esc_html_e( 'Product Visibility 🔒', 'conditional-payments' );
    ?></option>
																	<option value="product_quantity_disabled"><?php 
    esc_html_e( 'Product Quantity 🔒', 'conditional-payments' );
    ?></option>
																	<option value="product_stock_status_disabled"><?php 
    esc_html_e( 'Stock Status 🔒', 'conditional-payments' );
    ?></option>
																<?php 
    ?>
													</optgroup>
													<optgroup label="<?php 
    esc_attr_e( 'Cart Specific', 'conditional-payments' );
    ?>">
														<option value="cart_total"><?php 
    esc_html_e( 'Cart Subtotal (Before Discount)', 'conditional-payments' );
    ?></option>
														<option value="cart_totalafter"><?php 
    esc_html_e( 'Cart Subtotal (After Discount)', 'conditional-payments' );
    ?></option>
														<?php 
    ?>
																	<option value="cart_quantity_disabled"><?php 
    esc_html_e( 'Cart Quantity 🔒', 'conditional-payments' );
    ?></option>
																	<option value="shipping_class_disabled"><?php 
    esc_html_e( 'Shipping Class 🔒', 'conditional-payments' );
    ?></option>
																	<option value="coupon_disabled"><?php 
    esc_html_e( 'Coupon 🔒', 'conditional-payments' );
    ?></option>
																	<option value="total_weight_disabled"><?php 
    esc_html_e( 'Total Weight 🔒', 'conditional-payments' );
    ?></option>
																	<option value="number_of_items_disabled"><?php 
    esc_html_e( 'Number Of Items 🔒', 'conditional-payments' );
    ?></option>
																	<option value="total_volume_disabled"><?php 
    esc_html_e( 'Total Volume 🔒', 'conditional-payments' );
    ?></option>
																<?php 
    ?>
													</optgroup>
													<optgroup label="<?php 
    esc_attr_e( 'Shipping Specific', 'conditional-payments' );
    ?>">
														<option value="shipping_method"><?php 
    esc_html_e( 'Shipping Method', 'conditional-payments' );
    ?></option>
													</optgroup>
													<optgroup label="<?php 
    esc_attr_e( 'Billing Address', 'conditional-payments' );
    ?>">
														<option value="billing_first_name"><?php 
    esc_html_e( 'First Name', 'conditional-payments' );
    ?></option>
														<option value="billing_last_name"><?php 
    esc_html_e( 'Last Name', 'conditional-payments' );
    ?></option>
														<option value="billing_company"><?php 
    esc_html_e( 'Company', 'conditional-payments' );
    ?></option>
														<option value="billing_address_1"><?php 
    esc_html_e( 'Address', 'conditional-payments' );
    ?></option>
														<option value="billing_address_2"><?php 
    esc_html_e( 'Address 2', 'conditional-payments' );
    ?></option>
														<option value="billing_country"><?php 
    esc_html_e( 'Country', 'conditional-payments' );
    ?></option>
														<option value="billing_city"><?php 
    esc_html_e( 'City', 'conditional-payments' );
    ?></option>
														<option value="billing_postcode"><?php 
    esc_html_e( 'Postcode', 'conditional-payments' );
    ?></option>
														<?php 
    ?>
																	<option value="billing_email_disabled"><?php 
    esc_html_e( 'Email 🔒', 'conditional-payments' );
    ?></option>
																	<option value="billing_phone_disabled"><?php 
    esc_html_e( 'Phone 🔒', 'conditional-payments' );
    ?></option>
																	<option value="previous_order_disabled"><?php 
    esc_html_e( 'Previous Order', 'conditional-payments' );
    ?></option>
																<?php 
    ?>
													</optgroup>
													<?php 
    ?>
														<optgroup label="<?php 
    esc_attr_e( 'Customer', 'conditional-payments' );
    ?>">
															<option value="customer_authenticated_disabled"><?php 
    esc_html_e( 'Logged in / out 🔒', 'conditional-payments' );
    ?></option>
															<option value="user_disabled"><?php 
    esc_html_e( 'User 🔒', 'conditional-payments' );
    ?></option>
															<option value="user_role_disabled"><?php 
    esc_html_e( 'User Role 🔒', 'conditional-payments' );
    ?></option>
														</optgroup>
														<?php 
    ?>
													<optgroup label="<?php 
    esc_attr_e( 'Shipping Address', 'conditional-payments' );
    ?>">
														<option value="shipping_first_name"><?php 
    esc_html_e( 'First Name', 'conditional-payments' );
    ?></option>
														<option value="shipping_last_name"><?php 
    esc_html_e( 'Last Name', 'conditional-payments' );
    ?></option>
														<option value="shipping_company"><?php 
    esc_html_e( 'Company', 'conditional-payments' );
    ?></option>
														<option value="shipping_address_1"><?php 
    esc_html_e( 'Address', 'conditional-payments' );
    ?></option>
														<option value="shipping_address_2"><?php 
    esc_html_e( 'Address 2', 'conditional-payments' );
    ?></option>
														<option value="shipping_country"><?php 
    esc_html_e( 'Country', 'conditional-payments' );
    ?></option>
														<option value="shipping_city"><?php 
    esc_html_e( 'City', 'conditional-payments' );
    ?></option>
														<option value="shipping_postcode"><?php 
    esc_html_e( 'Postcode', 'conditional-payments' );
    ?></option>
													</optgroup>
													<optgroup label="<?php 
    esc_attr_e( 'Time Specific', 'conditional-payments' );
    ?>">
														<option value="day_of_week"><?php 
    esc_html_e( 'Day Of Week', 'conditional-payments' );
    ?></option>
														<option value="date"><?php 
    esc_html_e( 'Date', 'conditional-payments' );
    ?></option>
														<?php 
    ?><option value="time_disabled"><?php 
    esc_html_e( 'Time 🔒', 'conditional-payments' );
    ?></option><?php 
    ?>
													</optgroup>
												</select>
											</th>
											<td class="select_condition_for_in_notin">
												<select name="payment[payments_conditions_is][]"
												        class="payments_conditions_is payments_conditions_is_1">
													<option value="is_equal_to"><?php 
    esc_html_e( 'Equal to ( = )', 'conditional-payments' );
    ?></option>
													<option value="not_in"><?php 
    esc_html_e( 'Not Equal to ( != )', 'conditional-payments' );
    ?></option>
												</select>
											</td>
											<td class="condition-value" id="column_1">
												<?php 
    echo wp_kses( $dscpw_admin_object->dscpw_get_product_list( 1 ), $dscpw_admin_object::dscpw_allowed_html_tags() );
    ?>
												<input type="hidden" name="condition_key[value_1]" value="">
											</td>
											<td class="condition-delete-field-outer">
												<span rel-id="1" class="condition-delete-field" title="Delete"><span class="dashicons dashicons-trash"></span></span>
											</td>
										</tr>
										<?php 
}
?>
									</tbody>
								</table>
								<input type="hidden" name="conditions_total_row" id="conditions_total_row" value="<?php 
echo esc_attr( $i );
?>">
							</div>
							<div class="sub-title">
								<a id="conition-add-field" class="button button-large"
									   href="javascript:void(0)"><?php 
esc_html_e( '+ Add Condition', 'conditional-payments' );
?></a>
							</div>
						</div>
					</td>
				</tr>
				<tr valign="top">
					<th class="titledesc" scope="row">
						<label><?php 
esc_html_e( 'Actions', 'conditional-payments' );
echo wp_kses( wc_help_tip( esc_html__( 'Set actions for your conditional payments.', 'conditional-payments' ) ), array(
    'span' => $allowed_tooltip_html,
) );
?></label>
					</th>
					<td class="forminp">
						<div class="dscpw-conditional-rules">
							<div class="tap">
								<table id="tbl-actions-payment-rules" class="conditional-payments-tbl table-outer tap-cas form-table">
									<tbody>
									<?php 
if ( isset( $cp_actions_metabox ) && !empty( $cp_actions_metabox ) ) {
    $i = 2;
    foreach ( $cp_actions_metabox as $paymentAction ) {
        $payment_actions = ( isset( $paymentAction['conditional_payments_actions'] ) ? $paymentAction['conditional_payments_actions'] : '' );
        $action_value = ( isset( $paymentAction['payment_actions_values'] ) ? $paymentAction['payment_actions_values'] : array() );
        ?>
										<tr id="action_row_<?php 
        echo esc_attr( $i );
        ?>" valign="top">
											<th class="titledesc" scope="row">
												<select rel-id="<?php 
        echo esc_attr( $i );
        ?>" name="cp_actions[conditional_payments_actions][]" id="conditional_payments_actions" class="conditional_payments_actions">
													<option value="enable_payments" <?php 
        echo ( 'enable_payments' === $payment_actions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Enable Payments Methods', 'conditional-payments' );
        ?></option>
													<option value="disable_payments" <?php 
        echo ( 'disable_payments' === $payment_actions ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Disable Payments Methods', 'conditional-payments' );
        ?></option>
													<?php 
        ?>
																<option value="add_payment_method_fee_disabled"><?php 
        esc_html_e( 'Add Payment Method Fee 🔒', 'conditional-payments' );
        ?></option>
															<?php 
        ?>
												</select>
											</th>
											<td class="action-value" id="action_column_<?php 
        echo esc_attr( $i );
        ?>">
												<?php 
        $html = '';
        if ( 'enable_payments' === $payment_actions ) {
            $html .= $dscpw_admin_object->dscpw_get_payment_gateway_list( $i, $action_value );
        } elseif ( 'disable_payments' === $payment_actions ) {
            $html .= $dscpw_admin_object->dscpw_get_payment_gateway_list( $i, $action_value );
        } elseif ( 'add_payment_method_fee' === $payment_actions ) {
            $html .= $dscpw_admin_object->dscpw_get_payment_gateway_list_payment_fee( $i, $action_value );
        }
        echo wp_kses( $html, $dscpw_admin_object::dscpw_allowed_html_tags() );
        ?>
												<input type="hidden" name="actions_key[value_<?php 
        echo esc_attr( $i );
        ?>]" value="">
											</td>
											<td class="action-delete-field-outer">
												<span rel-id="<?php 
        echo esc_attr( $i );
        ?>" class="action-delete-field" title="Delete"><span class="dashicons dashicons-trash"></span></span>
											</td>
										</tr>
										<?php 
        $i++;
    }
} else {
    $i = 1;
    ?>
										<tr id="action_row_1" valign="top">
											<th class="titledesc" scope="row">
												<select rel-id="1" name="cp_actions[conditional_payments_actions][]" id="conditional_payments_actions" class="conditional_payments_actions">
													<option value="enable_payments"><?php 
    esc_html_e( 'Enable Payments Methods', 'conditional-payments' );
    ?></option>
													<option value="disable_payments"><?php 
    esc_html_e( 'Disable Payments Methods', 'conditional-payments' );
    ?></option>
													<?php 
    ?>
																<option value="add_payment_method_fee_disabled"><?php 
    esc_html_e( 'Add Payment Method Fee 🔒', 'conditional-payments' );
    ?></option>
															<?php 
    ?>
												</select>
											</th>
											<td class="select_payment_methods" id="action_column_1">
												<?php 
    echo wp_kses( $dscpw_admin_object->dscpw_get_payment_gateway_list( 1 ), $dscpw_admin_object::dscpw_allowed_html_tags() );
    ?>
											</td>
											<td class="action-delete-field-outer">
												<span rel-id="1" class="action-delete-field" title="Delete"><span class="dashicons dashicons-trash"></span></span>
											</td>
										</tr>
										<?php 
}
?>
									</tbody>
								</table>
								<input type="hidden" name="action_total_row" id="action_total_row" value="<?php 
echo esc_attr( $i );
?>">
							</div>
							<div class="sub-title">
								<a id="action-add-field" class="button button-large"
									   href="javascript:void(0)"><?php 
esc_html_e( '+ Add Action', 'conditional-payments' );
?></a>
							</div>
						</div>
					</td>
				</tr>
				<?php 
?>
			</tbody>
		</table>
	</div>
	<p class="submit">
		<input type="submit" class="button button-primary" name="dscpw_save" value="<?php 
esc_attr_e( 'Save Changes', 'conditional-payments' );
?>">
	</p>
	<?php 
wp_nonce_field( 'woocommerce_save_method', 'woocommerce_save_method_nonce' );
?>
</div>
<!-- Upgrade to pro popup -->
<?php 
if ( !(cp_fs()->is__premium_only() && cp_fs()->can_use_premium_code()) ) {
    require_once DSCPW_PLUGIN_PATH . 'admin/partials/dots-upgrade-popup.php';
}