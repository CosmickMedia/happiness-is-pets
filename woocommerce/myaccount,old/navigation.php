<?php
/**
 * My Account navigation template
 */
defined( 'ABSPATH' ) || exit;
?>
<nav class="woocommerce-MyAccount-navigation mb-4">
  <ul class="list-unstyled">
    <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
      <li class="mb-2 <?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
        <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
      </li>
    <?php endforeach; ?>
  </ul>
</nav>
