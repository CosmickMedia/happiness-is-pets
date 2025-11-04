<?php
/**
 * Template part for displaying blog posts in archive/listing pages
 *
 * @package happiness-is-pets
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-post-item mb-5' ); ?>>
    <div class="blog-post-card">
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="blog-post-image">
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid' ) ); ?>
                </a>
            </div>
        <?php endif; ?>

        <div class="blog-post-content">
            <div class="blog-post-meta mb-3">
                <span class="post-date">
                    <i class="fas fa-calendar-alt me-1"></i>
                    <?php echo get_the_date(); ?>
                </span>
                <span class="post-author ms-3">
                    <i class="fas fa-user me-1"></i>
                    <?php the_author(); ?>
                </span>
                <?php if ( has_category() ) : ?>
                    <span class="post-categories ms-3">
                        <i class="fas fa-folder me-1"></i>
                        <?php the_category( ', ' ); ?>
                    </span>
                <?php endif; ?>
            </div>

            <h2 class="blog-post-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>

            <div class="blog-post-excerpt">
                <?php
                if ( has_excerpt() ) {
                    the_excerpt();
                } else {
                    echo wp_trim_words( get_the_content(), 30, '...' );
                }
                ?>
            </div>

            <?php if ( has_tag() ) : ?>
                <div class="blog-post-tags mt-3">
                    <?php
                    $tags = get_the_tags();
                    if ( $tags ) {
                        foreach ( $tags as $tag ) {
                            echo '<a href="' . get_tag_link( $tag->term_id ) . '" class="tag-badge">' . $tag->name . '</a>';
                        }
                    }
                    ?>
                </div>
            <?php endif; ?>

            <div class="blog-post-footer mt-3">
                <a href="<?php the_permalink(); ?>" class="blog-read-more-link">
                    <?php esc_html_e( 'Read More', 'happiness-is-pets' ); ?> <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</article>
