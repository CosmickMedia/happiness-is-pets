<?php
/**
 * My Account login form
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="woocommerce-form-login w-100" style="max-width:500px;margin:auto;">
  <?php wc_get_template( 'myaccount/form-login.php', array(), '', WC()->plugin_path() . '/templates/' ); ?>
</div>
