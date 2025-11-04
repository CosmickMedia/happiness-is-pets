<?php
/**
 * The template for displaying archive pages (blog listing)
 *
 * @package happiness-is-pets
 */

get_header();
get_template_part( 'template-parts/page', 'header' );
?>

<main id="primary" class="site-main">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="blog-archive-wrapper">
                    <?php if ( have_posts() ) : ?>
                        
                        <?php if ( is_archive() ) : ?>
                            <div class="archive-header mb-4">
                                <?php
//                                the_archive_title( '<h1 class="archive-title">', '</h1>' );
//                                the_archive_description( '<div class="archive-description">', '</div>' );
                                ?>
                            </div>
                        <?php endif; ?>

                        <div class="blog-posts-grid">
                            <?php
                            /* Start the Loop */
                            while ( have_posts() ) :
                                the_post();

                                get_template_part( 'template-parts/content', 'blog' );

                            endwhile;
                            ?>
                        </div>

                        <?php
                        // Pagination
                        the_posts_pagination( array(
                            'mid_size'  => 2,
                            'prev_text' => '<i class="fas fa-chevron-left me-1"></i>' . esc_html__( 'Previous', 'happiness-is-pets' ),
                            'next_text' => esc_html__( 'Next', 'happiness-is-pets' ) . '<i class="fas fa-chevron-right ms-1"></i>',
                        ) );
                        ?>

                    <?php else : ?>

                        <div class="no-posts-found">
                            <h2><?php esc_html_e( 'Nothing Found', 'happiness-is-pets' ); ?></h2>
                            <p><?php esc_html_e( 'It seems we can\'t find what you\'re looking for. Perhaps try searching?', 'happiness-is-pets' ); ?></p>
                            <?php get_search_form(); ?>
                        </div>

                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-12 col-lg-4">
                <div class="blog-sidebar">
                    <?php
                    // Get categories
                    $categories = get_categories( array(
                        'orderby' => 'count',
                        'order'   => 'DESC',
                        'number'  => 10,
                    ) );
                    
                    if ( $categories ) :
                    ?>
                        <div class="sidebar-widget mb-4">
                            <h3 class="widget-title"><?php esc_html_e( 'Categories', 'happiness-is-pets' ); ?></h3>
                            <ul class="category-list list-unstyled">
                                <?php foreach ( $categories as $category ) : ?>
                                    <li class="category-item mb-2">
                                        <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="category-link">
                                            <i class="fas fa-folder me-2"></i>
                                            <?php echo esc_html( $category->name ); ?>
                                            <span class="category-count">(<?php echo esc_html( $category->count ); ?>)</span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php
                    // Get recent posts
                    $recent_posts = wp_get_recent_posts( array(
                        'numberposts' => 5,
                        'post_status' => 'publish',
                    ) );
                    
                    if ( $recent_posts ) :
                    ?>
                        <div class="sidebar-widget mb-4">
                            <h3 class="widget-title"><?php esc_html_e( 'Recent Posts', 'happiness-is-pets' ); ?></h3>
                            <ul class="recent-posts-list list-unstyled">
                                <?php foreach ( $recent_posts as $post ) : ?>
                                    <li class="recent-post-item mb-3">
                                        <a href="<?php echo esc_url( get_permalink( $post['ID'] ) ); ?>" class="recent-post-link">
                                            <?php echo esc_html( $post['post_title'] ); ?>
                                        </a>
                                        <div class="recent-post-date">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                <?php echo get_the_date( 'M j, Y', $post['ID'] ); ?>
                                            </small>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php
                    // Get tags
                    $tags = get_tags();
                    if ( $tags ) :
                    ?>
                        <div class="sidebar-widget mb-4">
                            <h3 class="widget-title"><?php esc_html_e( 'Popular Tags', 'happiness-is-pets' ); ?></h3>
                            <div class="tag-cloud">
                                <?php foreach ( $tags as $tag ) : ?>
                                    <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="tag-link">
                                        <?php echo esc_html( $tag->name ); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
