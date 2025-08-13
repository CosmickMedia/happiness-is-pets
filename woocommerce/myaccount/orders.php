<?php
/**
 * My Account orders template
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="woocommerce-MyAccount-orders">
  <?php wc_get_template( 'myaccount/orders.php', array( 'current_user' => wp_get_current_user() ), '', WC()->plugin_path() . '/templates/' ); ?>
</div>
