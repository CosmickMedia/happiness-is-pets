<?php
/**
 * Customer completed order email template
 */
defined( 'ABSPATH' ) || exit;

/** @var WC_Order $order */

 do_action( 'woocommerce_email_header', $email_heading, $email );
?>
<p><?php esc_html_e( 'Hi there. Your recent order is now complete. Thank you for shopping with us!', 'dreamtails' ); ?></p>
<?php
  do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );
  do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );
  do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );
?>
<p><?php esc_html_e( 'We hope to see you again soon.', 'dreamtails' ); ?></p>
<?php
do_action( 'woocommerce_email_footer', $email );
