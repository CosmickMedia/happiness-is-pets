<?php
/**
 * My Account dashboard
 */
defined( 'ABSPATH' ) || exit;
?>
<p class="woocommerce-MyAccount-content">
  <?php
  wc_print_notices();
  /** @var WP_User $current_user */
  $current_user = wp_get_current_user();
  printf( __( 'Hello %1$s', 'dreamtails' ), '<strong>' . esc_html( $current_user->display_name ) . '</strong>' );
  ?>
</p>
<?php do_action( 'woocommerce_account_dashboard' ); ?>
