<div id="dscpw-debug">
	<div id="dscpw-debug-header">
		<div class="dscpw-debug-title"><?php esc_html_e( 'Conditional Payment Methods Debug', 'conditional-payments' ); ?></div>
		<div class="dscpw-debug-toggle"></div>
	</div>
	<div id="dscpw-debug-contents">
	<h3><?php esc_html_e( 'Payment methods', 'conditional-payments' ); ?></h3>

	<table class="dscpw-debug-table dscpw-debug-table-fixed">
		<thead>
			<tr>
				<th>
					<?php esc_html_e( 'Before filtering', 'conditional-payments' ); ?>
				</th>
				<th>
					<?php esc_html_e( 'After filtering', 'conditional-payments' ); ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="before-filter-methods">
					<?php
					
					if ( ! isset( $debug['payment_methods']['before'] ) || empty( $debug['payment_methods']['before'] ) ) { ?>
						<em><?php esc_html_e( 'No payment methods', 'conditional-payments' ); ?></em>
					<?php } else { ?>
						<?php echo implode('<br>', array_map('esc_html', $debug['payment_methods']['before'])); ?>
					<?php } ?>
				</td>
				<td class="after-filter-methods">
					<?php if ( ! isset( $debug['payment_methods']['before'] ) || empty( $debug['payment_methods']['after'] ) ) { ?>
						<em><?php esc_html_e( 'No payment methods', 'conditional-payments' ); ?></em>
					<?php } else { ?>
						<?php echo implode('<br>', array_map('esc_html', $debug['payment_methods']['after'])); ?>
					<?php } ?>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="dscpw-debug-info"><?php esc_html_e( "If payment method is not listed above or is not available as expected, another plugin might be affecting its visibility or its settings do not allow it to be available for the cart or customer address.", 'conditional-payments' ); ?></p>
	<h3><?php esc_html_e( 'Rulesets', 'conditional-payments' ); ?></h3>
	<?php if ( isset( $debug['rulesets'] ) && !empty( $debug['rulesets'] ) ) { ?>
    <?php foreach ( $debug['rulesets'] as $ruleset_id => $data ) {
        $edit_method_url = add_query_arg( array(
            'page'   => 'wc-settings',
            'tab'    => 'checkout',
            'section' => 'dscpw_conditional_payments',
            'action' => 'edit',
            'post'   => $ruleset_id
        ), admin_url( 'admin.php' ) );
        $editurl         = $edit_method_url;
        $ruleset_url = wp_nonce_url( $editurl, 'edit_' . $ruleset_id, 'cust_nonce' )
        ?>
		<div class="dscpw-debug-<?php echo esc_attr( $ruleset_id ); ?>">
			<h3 class="ruleset-title">
				<a href="<?php echo esc_url(  $ruleset_url ); ?>" target="_blank">
					<span class="ruleset-url-text"><?php echo esc_html( get_the_title($ruleset_id)); ?></span>
				</a>
			</h3>
			<table class="dscpw-debug-table dscpw-debug-conditions">
				<thead>
					<tr>
						<th colspan="2"><?php esc_html_e( 'Conditions - All conditions have to pass (AND)', 'conditional-payments' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $data['conditions'] as $condition ) {?>
						<tr>
							<td><?php echo esc_html_e( $condition['desc'], 'conditional-payments' ); ?></td>
							<td class="align-right">
								<span class="dscpw-debug-result-label dscpw-debug-result-label-<?php echo ( $condition['result'] ? 'pass' : 'fail' ); ?>">
									<?php echo esc_html( ( $condition['result'] ? __( 'Pass', 'conditional-payments' ) : __( 'Fail', 'conditional-payments' ) ) ); ?>
								</span>
							</td>
						</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<th><?php esc_html_e( 'Result', 'conditional-payments' ); ?></th>
						<th class="align-right">
							<span class="dscpw-debug-result-label dscpw-debug-result-label-<?php echo ( $data['result'] ? 'pass' : 'fail' ); ?>">
								<?php echo esc_html( ( $data['result'] ? __( 'Pass', 'conditional-payments' ) : __( 'Fail', 'conditional-payments' ) ) ); ?>
							</span>
						</th>
					</tr>
				</tfoot>
			</table>
            <table class="dscpw-debug-table dscpw-debug-actions">
				<thead>
					<tr>
						<th colspan="2"><?php esc_html_e( 'Actions', 'conditional-payments' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $data['action'] as $action_data ) { ?>
						<tr class="status-<?php echo esc_attr( $action_data['status'] ); ?>">
							<td>
								<?php if ( $action_data['desc'] ) { ?>
									<?php echo esc_html( $action_data['desc'] ); ?>
								<?php } ?>
							</td>
							<td class="align-right">
								<span class="dscpw-debug-result-label dscpw-debug-result-label-<?php echo esc_attr( $action_data['status'] ); ?>">
									<?php echo esc_html( ( $action_data['status']  ? __( 'Run', 'conditional-payments' ) : __( 'Fail', 'conditional-payments' ) ) ); ?>
								</span>
							</td>
						</tr>
					<?php } ?>
					<?php if ( empty( $data['action'] ) ) { ?>
						<tr>
							<td colspan="2"><?php esc_html_e( 'No actions were run for this ruleset', 'conditional-payments' ); ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<?php } ?>
	<?php } else { ?>
		<p><?php esc_html_e( 'No rulesets were evaluated.', 'conditional-payments' ); ?></p>
	<?php } ?>
		</div>
	</div>