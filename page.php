<?php
/**
 * The template for displaying all static pages
 * @package Dream_Tails
 */

get_header();
get_template_part( 'template-parts/page', 'header' );
?>

    <main id="primary" class="site-main py-5">
    <div class="main-container">
        <?php
        while ( have_posts() ) :
            the_post();

            get_template_part( 'template-parts/content', 'page' );

            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;

        endwhile; // End of the loop.
        ?>
    </div></main><?php
get_footer();