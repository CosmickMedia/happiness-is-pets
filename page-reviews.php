<?php
/**
 * Template Name: Reviews Page
 * Description: Displays all customer reviews with star ratings
 *
 * @package happiness-is-pets
 */

get_header();
?>

<style>
    /* Dedicated CSS for Reviews Page */
    .reviews-page-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 120px 24px 60px;
        background: #fafafa;
    }

    @media (max-width: 768px) {
        .reviews-page-container {
            padding: 80px 24px 60px;
        }
    }

    .reviews-page-header {
        text-align: center;
        margin-bottom: 60px;
        padding-bottom: 30px;
        border-bottom: 3px solid var(--color-primary-turquoise, #00c8ba);
    }

    .reviews-page-title {
        font-size: 48px;
        font-weight: 900;
        color: #1a202c;
        margin: 0 0 16px 0;
        letter-spacing: -1px;
    }

    .reviews-page-subtitle {
        font-size: 20px;
        color: #718096;
        margin: 0;
        font-weight: 500;
    }

    .reviews-page-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 32px;
        margin-bottom: 40px;
    }

    @media (max-width: 768px) {
        .reviews-page-grid {
            grid-template-columns: 1fr;
        }
    }

    .review-card {
        background: white;
        border-radius: 16px;
        padding: 36px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .review-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 40px rgba(0, 200, 186, 0.15);
        border-color: var(--color-primary-turquoise, #00c8ba);
    }

    .review-card::before {
        content: '"';
        position: absolute;
        top: 16px;
        left: 24px;
        font-size: 120px;
        font-family: Georgia, serif;
        color: rgba(0, 200, 186, 0.08);
        line-height: 1;
        z-index: 0;
    }

    .review-card-inner {
        position: relative;
        z-index: 1;
    }

    .review-stars {
        display: flex;
        gap: 4px;
        margin-bottom: 20px;
        justify-content: center;
    }

    .review-star {
        color: #FFD700;
        font-size: 24px;
    }

    .review-star.empty {
        color: #e2e8f0;
    }

    .review-title {
        font-size: 24px;
        font-weight: 800;
        color: #2d3748;
        margin: 0 0 16px 0;
        text-align: center;
    }

    .review-content {
        font-size: 16px;
        line-height: 1.8;
        color: #4a5568;
        margin: 0 0 24px 0;
        text-align: center;
    }

    .review-author {
        text-align: center;
        padding-top: 24px;
        border-top: 2px solid #f7fafc;
    }

    .review-author-name {
        font-size: 18px;
        font-weight: 700;
        color: var(--color-primary-turquoise, #00c8ba);
        margin: 0;
    }

    .review-author-label {
        font-size: 13px;
        color: #a0aec0;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 4px;
    }

    .reviews-page-empty {
        text-align: center;
        padding: 80px 24px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    }

    .reviews-page-empty-icon {
        font-size: 64px;
        color: #cbd5e0;
        margin-bottom: 24px;
    }

    .reviews-page-empty-text {
        font-size: 20px;
        color: #718096;
        margin: 0;
    }

    .reviews-page-cta {
        text-align: center;
        margin-top: 60px;
        padding: 48px;
        background: linear-gradient(135deg, var(--color-primary-turquoise, #00c8ba) 0%, #00a89c 100%);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 200, 186, 0.25);
    }

    .reviews-page-cta-title {
        font-size: 32px;
        font-weight: 900;
        color: white;
        margin: 0 0 16px 0;
    }

    .reviews-page-cta-text {
        font-size: 18px;
        color: rgba(255, 255, 255, 0.95);
        margin: 0 0 32px 0;
    }

    .reviews-page-cta-button {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 18px 40px;
        background: white;
        color: var(--color-primary-turquoise, #00c8ba);
        text-decoration: none;
        border-radius: 100px;
        font-size: 18px;
        font-weight: 800;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }

    .reviews-page-cta-button:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        color: var(--color-primary-turquoise, #00c8ba);
    }

    @media (max-width: 768px) {
        .reviews-page-title {
            font-size: 36px;
        }

        .reviews-page-subtitle {
            font-size: 18px;
        }

        .reviews-page-cta-title {
            font-size: 28px;
        }

        .reviews-page-cta-text {
            font-size: 16px;
        }
    }
</style>

<main id="primary" class="site-main">
    <div class="reviews-page-container">

        <!-- Page Header -->
        <div class="reviews-page-header">
            <h1 class="reviews-page-title">Happy Tails & Testimonials</h1>
            <p class="reviews-page-subtitle">See what our customers have to say about their experience with Happiness Is Pets</p>
        </div>

        <?php
        // Query all reviews
        $reviews_query = new WP_Query( array(
            'post_type'      => 'reviews',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ) );

        if ( $reviews_query->have_posts() ) :
        ?>
            <!-- Reviews Grid -->
            <div class="reviews-page-grid">
                <?php while ( $reviews_query->have_posts() ) : $reviews_query->the_post();
                    $rating = intval( get_post_meta( get_the_ID(), '_happiness_is_pets_review_rating', true ) );
                    $rating = max( 1, min( 5, $rating ) ); // Ensure rating is between 1-5
                ?>
                    <div class="review-card">
                        <div class="review-card-inner">
                            <!-- Star Rating -->
                            <div class="review-stars">
                                <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                    <i class="fas fa-star review-star <?php echo $i > $rating ? 'empty' : ''; ?>"></i>
                                <?php endfor; ?>
                            </div>

                            <!-- Review Title -->
                            <h3 class="review-title"><?php the_title(); ?></h3>

                            <!-- Review Content -->
                            <div class="review-content">
                                <?php the_content(); ?>
                            </div>

                            <!-- Author -->
                            <div class="review-author">
                                <p class="review-author-name"><?php the_title(); ?></p>
                                <p class="review-author-label">Verified Customer</p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <?php wp_reset_postdata(); ?>

        <?php else : ?>

            <!-- Empty State -->
            <div class="reviews-page-empty">
                <div class="reviews-page-empty-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <p class="reviews-page-empty-text">No reviews yet. Be the first to share your experience!</p>
            </div>

        <?php endif; ?>

        <!-- Call to Action -->
        <div class="reviews-page-cta">
            <h2 class="reviews-page-cta-title">Share Your Story</h2>
            <p class="reviews-page-cta-text">We'd love to hear about your experience with your new furry family member!</p>
            <a href="<?php echo esc_url( get_theme_mod( 'reviews_button_url', '/contact/' ) ); ?>" class="reviews-page-cta-button">
                <i class="fas fa-pen"></i>
                <span>Submit Your Review</span>
            </a>
        </div>

    </div>
</main>

<?php
get_footer();
