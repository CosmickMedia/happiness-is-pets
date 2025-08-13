<?php
/**
 * WooCommerce template wrapper for all shop pages.
 *
 * Displays WooCommerce content within the theme layout and
 * adds an optional sidebar on product category archives.
 *
 * @package happiness-is-pets
 */

get_header();
get_template_part( 'template-parts/page', 'header' );
?>

    <main id="primary" class="site-main py-5">
        <div class="main-container">
            <?php if ( is_product_taxonomy() || is_shop() ) : ?>
                <button class="btn filter-pets-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#petsFilterOffcanvas" aria-controls="petsFilterOffcanvas">
                    <?php esc_html_e( 'Filter Pets', 'happiness-is-pets' ); ?>
                </button>

                <div class="offcanvas offcanvas-start pets-offcanvas" tabindex="-1" id="petsFilterOffcanvas" aria-labelledby="petsFilterLabel">
                    <div class="offcanvas-header">
                        <h5 id="petsFilterLabel" class="offcanvas-title"><?php esc_html_e( 'Filter Pets', 'happiness-is-pets' ); ?></h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="<?php esc_attr_e( 'Close', 'happiness-is-pets' ); ?>"></button>
                    </div>
                    <div class="offcanvas-body">
                        <?php get_sidebar( 'woocommerce-pets' ); ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <?php
                // Check if it is a product archive page (like category or shop) and if the sidebar is active.
                if ( is_archive() && is_active_sidebar( 'shop-sidebar' ) ) :
                    ?>
                    <div class="col-lg-9">
                        <?php woocommerce_content(); ?>
                    </div>
                    <div class="col-lg-3">
                        <?php get_sidebar( 'shop' ); ?>
                    </div>
                <?php else : ?>
                    <div class="col-12">
                        <?php woocommerce_content(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

<?php
get_footer();