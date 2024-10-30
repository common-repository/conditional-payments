<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$get_section = filter_input( INPUT_GET, 'section', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

if ( 'dscpw_conditional_payments' === $get_section ) {
	require_once( dirname( __FILE__ ) . '/class-dscpwc-conditional-payments-page.php' );
	?>
	<div class="wrap woocommerce">
		<form method="post" enctype="multipart/form-data">
			<hr class="wp-header-end">
			<?php
			global $hide_save_button;
			$hide_save_button = true;
			$conditional_payments_obj = new DSCPW_Conditional_Payments_Page();
			$conditional_payments_obj->dscpw_conditional_payments_output();
			?>
		</form>
	</div>
	<?php
}