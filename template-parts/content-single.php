<?php
/**
 * Template part for displaying single blog posts
 *
 * @package happiness-is-pets
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('single-post-article'); ?>>
    <div class="post-header mb-4">
        <?php if (has_post_thumbnail()) : ?>
            <div class="post-featured-image mb-4">
                <?php the_post_thumbnail('large', array('class' => 'img-fluid rounded')); ?>
            </div>
        <?php endif; ?>
        
        <div class="post-meta mb-3">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="post-meta-info">
                        <span class="post-date me-3">
                            <i class="fas fa-calendar-alt me-1"></i>
                            <?php echo get_the_date(); ?>
                        </span>
                        <?php if (has_category()) : ?>
                            <span class="post-categories me-3">
                                <i class="fas fa-folder me-1"></i>
                                <?php the_category(', '); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="post-reading-time">
                        <i class="fas fa-clock me-1"></i>
                        <?php
                        $word_count = str_word_count(strip_tags(get_the_content()));
                        $reading_time = ceil($word_count / 200); // Average reading speed: 200 words per minute
                        echo $reading_time . ' min read';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="post-content">
        <?php
        the_content();

        wp_link_pages(array(
            'before' => '<div class="page-links mt-4 mb-4"><span class="page-links-title">' . esc_html__('Pages:', 'happiness-is-pets') . '</span>',
            'after'  => '</div>',
        ));
        ?>
    </div>

    <?php if (has_tag()) : ?>
        <div class="post-tags mt-4 mb-4">
            <h6 class="tags-title mb-2"><?php esc_html_e('Tags:', 'happiness-is-pets'); ?></h6>
            <div class="tag-list">
                <?php
                $tags = get_the_tags();
                if ($tags) {
                    foreach ($tags as $tag) {
                        echo '<a href="' . get_tag_link($tag->term_id) . '" class="tag-link me-2 mb-2">' . $tag->name . '</a>';
                    }
                }
                ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="post-footer mt-2 pt-2 border-top p-5">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="post-share">
                    <h6 class="share-title mb-2"><?php esc_html_e('Share this post:', 'happiness-is-pets'); ?></h6>
                    <div class="share-buttons d-flex flex-wrap gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                           target="_blank" rel="noopener" class="btn btn-facebook btn-sm">
                            <i class="fab fa-facebook-f me-1"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                           target="_blank" rel="noopener" class="btn btn-twitter btn-sm">
                            <i class="fab fa-twitter me-1"></i> Twitter
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" 
                           target="_blank" rel="noopener" class="btn btn-linkedin btn-sm">
                            <i class="fab fa-linkedin-in me-1"></i> LinkedIn
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="post-actions">
                    <button class="btn btn-print btn-sm" onclick="window.print()">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>
</article>
