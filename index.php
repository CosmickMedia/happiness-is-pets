<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Dream_Tails
 */

get_header();
?>

    <main id="primary" class="site-main py-5"> <?php // Added padding utility class ?>
    <div class="container">
        <div class="row">
            <?php
            // Example structure with a potential sidebar
            // Default layout assumed a sidebar, leaving the main content at eight
            // columns on large screens. Since no sidebar is currently used we
            // make the content area span the full width by default.
            $main_col_class = 'col-12';
            ?>
            <div class="<?php echo esc_attr($main_col_class); ?>">
                <?php if ( is_home() && ! is_front_page() ) : ?>
                    <header class="page-header mb-4">
                        <h1 class="page-title"><?php single_post_title(); ?></h1>
                    </header>
                <?php endif; ?>

                <?php
                if ( have_posts() ) :

                    /* Start the Loop */
                    while ( have_posts() ) :
                        the_post();

                        /*
                        * Include the Post-Type-specific template for the content.
                        * If you want to override this in a child theme, then include a file
                        * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                        */
                        get_template_part( 'template-parts/content', get_post_type() );

                    endwhile;

                    // Add pagination if needed
                    the_posts_pagination( array(
                        'prev_text' => '<i class="fas fa-arrow-left"></i><span class="screen-reader-text">' . __( 'Previous page', 'dreamtails' ) . '</span>',
                        'next_text' => '<span class="screen-reader-text">' . __( 'Next page', 'dreamtails' ) . '</span><i class="fas fa-arrow-right"></i>',
                        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'dreamtails' ) . ' </span>',
                        // Add Bootstrap pagination classes if desired
                        'type'      => 'list', // Use list format for easier Bootstrap styling
                        'echo'      => false   // Return the links to maybe wrap them later
                    ) );
                // Example: Wrap pagination with Bootstrap classes
                // $pagination_links = the_posts_pagination( ... 'echo' => false ... );
                // if ( $pagination_links ) {
                //    echo '<nav class="navigation posts-navigation" aria-label="' . esc_attr__( 'Posts', 'dreamtails' ) . '"><ul class="pagination justify-content-center">' . $pagination_links . '</ul></nav>';
                // }


                else :

                    get_template_part( 'template-parts/content', 'none' );

                endif;
                ?>
            </div><?php
            // Uncomment the following line if you want to include a sidebar
            // get_sidebar();
            ?>

        </div></div></main><?php
get_footer();