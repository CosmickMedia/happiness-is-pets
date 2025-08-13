<?php
defined( 'ABSPATH' ) || exit;
get_header( 'shop' );
?>
<?php do_action( 'woocommerce_before_main_content' ); ?>

<?php if ( is_product_taxonomy() || is_shop() ) : ?>
    <button class="btn filter-pets-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#petsFilterOffcanvas" aria-controls="petsFilterOffcanvas">
        <?php esc_html_e( 'Filter Pets', 'dreamtails' ); ?>
    </button>

    <div class="offcanvas offcanvas-start pets-offcanvas" tabindex="-1" id="petsFilterOffcanvas" aria-labelledby="petsFilterLabel">
        <div class="offcanvas-header">
            <h5 id="petsFilterLabel" class="offcanvas-title"><?php esc_html_e( 'Filter Pets', 'dreamtails' ); ?></h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="<?php esc_attr_e( 'Close', 'dreamtails' ); ?>"></button>
        </div>
        <div class="offcanvas-body">
            <?php get_sidebar( 'woocommerce-pets' ); ?>
        </div>
    </div>
<?php endif; ?>

<?php if ( woocommerce_product_loop() ) : ?>
    <div class="shop-toolbar d-flex justify-content-between align-items-center mb-3">
        <?php woocommerce_result_count(); ?>
        <?php woocommerce_catalog_ordering(); ?>
    </div>
    <select class="product-filter form-select mb-3 d-block d-md-none">
        <option value=""><?php esc_html_e( 'Filter Products', 'dreamtails' ); ?></option>
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
