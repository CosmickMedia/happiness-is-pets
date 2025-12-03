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
            // Deduplicate post IDs at query level
            $all_post_ids = wp_list_pluck( $pets->posts, 'ID' );
            $unique_post_ids = array_values( array_unique( $all_post_ids ) );
            
            // If we found duplicates, rebuild the posts array
            if ( count( $unique_post_ids ) !== count( $all_post_ids ) ) {
                $unique_posts = array();
                foreach ( $unique_post_ids as $post_id ) {
                    $post = get_post( $post_id );
                    if ( $post ) {
                        $unique_posts[] = $post;
                    }
                }
                $pets->posts = $unique_posts;
                $pets->post_count = count( $unique_posts );
            }
            
            woocommerce_product_loop_start();
            
            // Track displayed IDs as double-check
            $displayed_ids = array();
            while ( $pets->have_posts() ) :
                $pets->the_post();
                $product_id = get_the_ID();
                
                // Skip if already displayed
                if ( in_array( $product_id, $displayed_ids, true ) ) {
                    continue;
                }
                $displayed_ids[] = $product_id;
                
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
