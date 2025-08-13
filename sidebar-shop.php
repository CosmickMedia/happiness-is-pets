<?php
/**
 * Sidebar for WooCommerce shop and product category pages.
 *
 * @package Dream_Tails
 */

if ( is_active_sidebar( 'shop-sidebar' ) ) : ?>
    <aside id="secondary" class="widget-area">
        <?php dynamic_sidebar( 'shop-sidebar' ); ?>
    </aside>
<?php endif; ?>
