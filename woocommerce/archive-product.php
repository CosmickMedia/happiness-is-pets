<?php
defined( 'ABSPATH' ) || exit;
get_header( 'shop' );
?>
<?php do_action( 'woocommerce_before_main_content' ); ?>

<?php if ( woocommerce_product_loop() ) : ?>
    <div class="shop-toolbar d-flex justify-content-between align-items-center mb-3">
        <?php woocommerce_result_count(); ?>
        <?php woocommerce_catalog_ordering(); ?>
    </div>
    <select class="product-filter form-select mb-3 d-block d-md-none">
        <option value=""><?php esc_html_e( 'Filter Products', 'happiness-is-pets' ); ?></option>
    </select>

    <?php woocommerce_product_loop_start(); ?>

    <?php if ( wc_get_loop_prop( 'total' ) ) {
        while ( have_posts() ) {
            the_post();
            wc_get_template_part( 'content', 'product' );
        }
    } ?>

    <?php woocommerce_product_loop_end(); ?>

    <?php do_action( 'woocommerce_after_shop_loop' ); ?>
<?php else : ?>
    <?php do_action( 'woocommerce_no_products_found' ); ?>
<?php endif; ?>

<?php do_action( 'woocommerce_after_main_content' ); ?>
<?php
get_footer( 'shop' );
