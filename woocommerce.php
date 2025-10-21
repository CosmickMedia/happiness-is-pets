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
                <div class="pets-filter-toolbar d-flex flex-wrap align-items-center gap-3 mb-4">
                    <button class="btn filter-pets-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#petsFilterOffcanvas" aria-controls="petsFilterOffcanvas">
                        <i class="fas fa-filter me-2"></i><?php esc_html_e( 'Filter Pets', 'happiness-is-pets' ); ?>
                    </button>

                    <?php
                    // Get current location from URL
                    $current_location = isset( $_GET['location'] ) ? sanitize_text_field( $_GET['location'] ) : '';
                    ?>
                    <div class="location-dropdown-wrapper" style="flex: 1; max-width: 350px;">
                        <label for="locationDropdown" class="visually-hidden">Change Location</label>
                        <select class="form-select location-select" id="locationDropdown">
                            <option value="" <?php selected( $current_location, '' ); ?>>All Locations</option>
                            <option value="Indianapolis" <?php selected( $current_location, 'Indianapolis' ); ?>>Happiness Is Pets Indianapolis</option>
                            <option value="Schererville" <?php selected( $current_location, 'Schererville' ); ?>>Happiness Is Pets Schererville</option>
                        </select>
                    </div>
                </div>

                <div class="offcanvas offcanvas-start pets-offcanvas" tabindex="-1" id="petsFilterOffcanvas" aria-labelledby="petsFilterLabel">
                    <div class="offcanvas-header">
                        <h5 id="petsFilterLabel" class="offcanvas-title">
                            <i class="fas fa-paw me-2"></i>
                            <span class="pets-count-display">
                                <?php
                                // Get total count of products (excluding Accessories)
                                global $wp_query;
                                echo esc_html( $wp_query->found_posts );
                                ?>
                            </span> pets found
                        </h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="<?php esc_attr_e( 'Close', 'happiness-is-pets' ); ?>"></button>
                    </div>
                    <div class="offcanvas-body">
                        <?php
                        // Use custom woo-sidebar instead of widget-based sidebar
                        if ( file_exists( get_template_directory() . '/woo-sidebar.php' ) ) {
                            include( get_template_directory() . '/woo-sidebar.php' );
                        } else {
                            get_sidebar( 'woocommerce-pets' );
                        }
                        ?>
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