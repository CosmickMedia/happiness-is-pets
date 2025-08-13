<?php
/**
 * Sidebar used inside the pets filter offcanvas.
 *
 * @package Dream_Tails
 */

if ( is_active_sidebar( 'woocommerce-pets' ) ) : ?>
    <aside id="pets-sidebar" class="widget-area">
        <?php dynamic_sidebar( 'woocommerce-pets' ); ?>
    </aside>
<?php endif; ?>
