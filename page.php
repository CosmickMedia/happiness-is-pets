<?php
/**
 * The template for displaying all static pages
 * @package happiness-is-pets
 */

get_header();
get_template_part( 'template-parts/page', 'header' );
?>

<style>
    /* Default Page Template Styling */
    .default-page-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 60px 24px;
        background: #ffffff;
    }

    @media (max-width: 768px) {
        .default-page-container {
            padding: 40px 20px;
        }
    }

    .default-page-article {
        background: #ffffff;
    }

    .default-page-content {
        font-size: 16px;
        line-height: 1.8;
        color: #4a5568;
    }

    .default-page-content h1,
    .default-page-content h2,
    .default-page-content h3,
    .default-page-content h4,
    .default-page-content h5,
    .default-page-content h6 {
        color: #2d3748;
        font-weight: 700;
        margin-top: 32px;
        margin-bottom: 16px;
        line-height: 1.3;
    }

    .default-page-content h1 {
        font-size: 36px;
        font-weight: 900;
    }

    .default-page-content h2 {
        font-size: 30px;
        font-weight: 800;
    }

    .default-page-content h3 {
        font-size: 24px;
    }

    .default-page-content h4 {
        font-size: 20px;
    }

    .default-page-content p {
        margin-bottom: 20px;
    }

    .default-page-content ul,
    .default-page-content ol {
        margin-bottom: 20px;
        padding-left: 32px;
    }

    .default-page-content li {
        margin-bottom: 8px;
    }

    .default-page-content a {
        color: var(--color-primary-turquoise, #00c8ba);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .default-page-content a:hover {
        color: #00a89c;
        text-decoration: underline;
    }

    .default-page-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 24px 0;
    }

    .default-page-content blockquote {
        border-left: 4px solid var(--color-primary-turquoise, #00c8ba);
        padding-left: 24px;
        margin: 24px 0;
        font-style: italic;
        color: #718096;
    }

    .default-page-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 24px 0;
    }

    .default-page-content table th,
    .default-page-content table td {
        padding: 12px;
        border: 1px solid #e2e8f0;
        text-align: left;
    }

    .default-page-content table th {
        background: #f7fafc;
        font-weight: 700;
        color: #2d3748;
    }

    .default-page-content .page-links {
        margin-top: 32px;
        padding-top: 24px;
        border-top: 2px solid #e2e8f0;
        font-weight: 600;
    }

    .default-page-content .page-links a {
        display: inline-block;
        padding: 8px 16px;
        margin: 4px;
        background: #f7fafc;
        border-radius: 6px;
        color: var(--color-primary-turquoise, #00c8ba);
    }

    .default-page-content .page-links a:hover {
        background: var(--color-primary-turquoise, #00c8ba);
        color: white;
        text-decoration: none;
    }

    .default-page-comments {
        margin-top: 60px;
        padding-top: 40px;
        border-top: 3px solid #e2e8f0;
    }

    @media (max-width: 768px) {
        .default-page-content h1 {
            font-size: 28px;
        }

        .default-page-content h2 {
            font-size: 24px;
        }

        .default-page-content h3 {
            font-size: 20px;
        }
    }
</style>

<main id="primary" class="site-main">
    <div class="default-page-container">
        <?php
        while ( have_posts() ) :
            the_post();
        ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('default-page-article'); ?>>
                <div class="entry-content default-page-content">
                    <?php
                    the_content();

                    wp_link_pages( array(
                        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'happiness-is-pets' ),
                        'after'  => '</div>',
                    ) );
                    ?>
                </div>
            </article>

            <?php
            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
            ?>
                <div class="default-page-comments">
                    <?php comments_template(); ?>
                </div>
            <?php
            endif;

        endwhile; // End of the loop.
        ?>
    </div>
</main>

<?php
get_footer();