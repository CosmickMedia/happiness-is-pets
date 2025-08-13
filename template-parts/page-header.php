<?php
if ( is_front_page() ) {
    return;
}
?>
<div class="header-title">
    <div class="page-header-bar py-4">
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
                } else {
                    echo esc_html( get_the_title() );
                }
                ?>
            </h1>

            <div class="breadcrumbs small">
                <?php if ( function_exists( 'dreamtails_breadcrumb' ) ) { dreamtails_breadcrumb(); } ?>
            </div>
        </div>
    </div>
</div>