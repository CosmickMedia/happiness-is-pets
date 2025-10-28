<?php
/**
 * The template for displaying all single posts
 * @package happiness-is-pets
 */

get_header();
get_template_part( 'template-parts/page', 'header' );
?>

<main id="primary" class="site-main">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="single-post-wrapper">
                    <?php
                    while ( have_posts() ) :
                        the_post();

                        // Use the dedicated single post template
                        get_template_part( 'template-parts/content', 'single' );

                        // Enhanced post navigation
                        echo '<div class="post-navigation-wrapper mt-5 mb-4">';
                        the_post_navigation( array(
                            'prev_text' => '<div class="nav-post prev-post"><span class="nav-subtitle">' . esc_html__( 'Previous Post', 'happiness-is-pets' ) . '</span><span class="nav-title">%title</span></div>',
                            'next_text' => '<div class="nav-post next-post"><span class="nav-subtitle">' . esc_html__( 'Next Post', 'happiness-is-pets' ) . '</span><span class="nav-title">%title</span></div>',
                        ) );
                        echo '</div>';

                        // If comments are open or we have at least one comment, load up the comment template.
                        if ( comments_open() || get_comments_number() ) :
                            echo '<div class="comments-wrapper mt-5">';
                            comments_template();
                            echo '</div>';
                        endif;

                    endwhile; // End of the loop.
                    ?>
                </div>
            </div>
            
            <!-- Sidebar for related posts or additional content -->
            <div class="col-12 col-lg-4">
                <div class="single-post-sidebar">
                    <?php
                    // Display related posts
                    $current_categories = wp_get_post_categories(get_the_ID());
                    $related_posts = array();
                    
                    if (!empty($current_categories)) {
                        $related_posts = get_posts(array(
                            'category__in' => $current_categories,
                            'numberposts' => 3,
                            'post__not_in' => array(get_the_ID()),
                            'orderby' => 'rand',
                            'post_status' => 'publish',
                            'meta_query' => array(
                                array(
                                    'key' => '_thumbnail_id',
                                    'compare' => 'EXISTS'
                                )
                            )
                        ));
                        
                        // If we don't have enough posts with thumbnails, get more without the thumbnail requirement
                        if (count($related_posts) < 3) {
                            $additional_posts = get_posts(array(
                                'category__in' => $current_categories,
                                'numberposts' => 3 - count($related_posts),
                                'post__not_in' => array_merge(array(get_the_ID()), wp_list_pluck($related_posts, 'ID')),
                                'orderby' => 'rand',
                                'post_status' => 'publish'
                            ));
                            $related_posts = array_merge($related_posts, $additional_posts);
                        }
                    }
                    
                    // If still no related posts, get recent posts
                    if (empty($related_posts)) {
                        $related_posts = get_posts(array(
                            'numberposts' => 3,
                            'post__not_in' => array(get_the_ID()),
                            'orderby' => 'date',
                            'order' => 'DESC',
                            'post_status' => 'publish'
                        ));
                    }
                    
                    if ($related_posts) :
                    ?>
                        <div class="related-posts-widget mb-4">
                            <h5 class="widget-title"><?php esc_html_e('Related Posts', 'happiness-is-pets'); ?></h5>
                            <div class="related-posts-list">
                                <?php foreach ($related_posts as $post) : setup_postdata($post); ?>
                                    <div class="related-post-item mb-3">
                                        <div class="related-post-thumbnail">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php 
                                                $thumbnail_id = get_post_thumbnail_id();
                                                if ($thumbnail_id) {
                                                    $thumbnail_url = wp_get_attachment_image_url($thumbnail_id, 'medium');
                                                    if ($thumbnail_url) {
                                                        echo '<img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr(get_the_title()) . '" class="img-fluid rounded">';
                                                    } else {
                                                        echo '<div class="no-thumbnail-placeholder"><i class="fas fa-image"></i></div>';
                                                    }
                                                } else {
                                                    // Try to get the first image from post content
                                                    $content = get_the_content();
                                                    $first_image = '';
                                                    if (preg_match('/<img[^>]+src="([^"]+)"/', $content, $matches)) {
                                                        $first_image = $matches[1];
                                                    }
                                                    
                                                    if ($first_image) {
                                                        echo '<img src="' . esc_url($first_image) . '" alt="' . esc_attr(get_the_title()) . '" class="img-fluid rounded">';
                                                    } else {
                                                        echo '<div class="no-thumbnail-placeholder"><i class="fas fa-image"></i></div>';
                                                    }
                                                }
                                                ?>
                                            </a>
                                        </div>
                                        <div class="related-post-content">
                                            <h6 class="related-post-title">
                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                            </h6>
                                            <div class="related-post-meta">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    <?php echo get_the_date(); ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; wp_reset_postdata(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Recent posts widget -->
                    <div class="recent-posts-widget">
                        <h5 class="widget-title"><?php esc_html_e('Recent Posts', 'happiness-is-pets'); ?></h5>
                        <?php
                        $recent_posts = wp_get_recent_posts(array(
                            'numberposts' => 5,
                            'post_status' => 'publish',
                            'exclude' => array(get_the_ID())
                        ));
                        ?>
                        <ul class="recent-posts-list list-unstyled">
                            <?php foreach ($recent_posts as $post) : ?>
                                <li class="recent-post-item mb-2">
                                    <a href="<?php echo get_permalink($post['ID']); ?>" class="recent-post-link">
                                        <?php echo $post['post_title']; ?>
                                    </a>
                                    <div class="recent-post-date">
                                        <small class="text-muted">
                                            <?php echo get_the_date('M j, Y', $post['ID']); ?>
                                        </small>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();