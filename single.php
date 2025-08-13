<?php
/**
 * The template for displaying all single posts
 * @package happiness-is-pets
 */

get_header();
get_template_part( 'template-parts/page', 'header' );
?>

    <main id="primary" class="site-main py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <?php
                while ( have_posts() ) :
                    the_post();

                    get_template_part( 'template-parts/content', get_post_type() );

                    // Previous/next post navigation.
                    the_post_navigation( array(
                        'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'happiness-is-pets' ) . '</span> <span class="nav-title">%title</span>',
                        'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'happiness-is-pets' ) . '</span> <span class="nav-title">%title</span>',
                    ) );


                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() ) :
                        comments_template(); // Ensure this is called
                    endif;

                endwhile; // End of the loop.
                ?>
            </div></div></div></main><?php
get_footer();