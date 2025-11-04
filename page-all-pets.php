<?php
/**
 * Template Name: All Pets
 * Description: Displays all products excluding the Accessories category.
 *
 * @package happiness-is-pets
 */

get_header();
get_template_part( 'template-parts/page', 'header' );
?>

<div class="main-container">
    <div class="breadcrumbs small mb-4">
        <?php if ( function_exists( 'happiness_is_pets_breadcrumb' ) ) { happiness_is_pets_breadcrumb(); } ?>
    </div>
</div>

<main id="primary" class="site-main py-5">
    <div class="main-container">
        <?php
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => array( 'accessories' ),
                    'operator' => 'NOT IN',
                ),
            ),
        );

        $pets = new WP_Query( $args );

        if ( $pets->have_posts() ) :
            woocommerce_product_loop_start();
            while ( $pets->have_posts() ) :
                $pets->the_post();
                wc_get_template_part( 'content', 'product' );
            endwhile;
            woocommerce_product_loop_end();
        else :
            wc_get_template( 'loop/no-products-found.php' );
        endif;

        wp_reset_postdata();
        ?>
    </div>
</main>

<?php
get_footer();
