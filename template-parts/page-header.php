<?php
if ( is_front_page() ) {
    return;
}
?>
<div class="header-title">
    <div class="page-header-bar">
        <div class="main-container">
            <h1 class="page-title mb-1">
                <?php
                if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
                    if ( is_product_category( 'puppies-for-sale' ) ) {
                        echo 'Available Puppies';
                    } elseif ( is_product_category( 'kittens-for-sale' ) ) {
                        echo 'Available Kittens';
                    } else {
                        echo esc_html( woocommerce_page_title( false ) );
                    }
                } elseif ( is_archive() ) {
                    the_archive_title();
                } elseif ( is_home() && ! is_front_page() ) {
                    single_post_title();
                } else {
                    echo esc_html( get_the_title() );
                }
                ?>
            </h1>

            <div class="breadcrumbs small">
                <?php if ( function_exists( 'happiness_is_pets_breadcrumb' ) ) { happiness_is_pets_breadcrumb(); } ?>
            </div>
        </div>
    </div>
</div>