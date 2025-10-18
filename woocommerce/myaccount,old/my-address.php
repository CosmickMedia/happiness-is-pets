<?php
/**
 * My Account address template
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="woocommerce-MyAccount-addresses">
  <?php wc_get_template( 'myaccount/my-address.php', array(), '', WC()->plugin_path() . '/templates/' ); ?>
</div>
