<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package happiness-is-pets
 */

get_header();
?>

<style>
    .error-404-page {
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 100px 20px 80px;
    }

    .error-404-content {
        max-width: 700px;
        margin: 0 auto;
        text-align: center;
    }

    .error-404-number {
        font-size: 150px;
        font-weight: 800;
        line-height: 1;
        color: var(--color-primary-light-peach, #F7BCAC);
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    }

    .error-404-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--color-heading, #3D5155);
        margin-bottom: 20px;
        line-height: 1.4;
    }

    .error-404-message {
        font-size: 1.125rem;
        color: #6c757d;
        margin-bottom: 40px;
        line-height: 1.6;
    }

    .error-404-cta {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 16px 40px;
        font-size: 1.125rem;
        font-weight: 600;
        background: var(--color-primary-light-peach, #F7BCAC);
        color: #3D5155;
        border: none;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(247, 188, 172, 0.3);
    }

    .error-404-cta:hover {
        background: var(--color-primary-dark-teal, #3D5155);
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(61, 81, 85, 0.4);
    }

    .error-404-icon {
        font-size: 80px;
        margin-bottom: 30px;
        opacity: 0.3;
    }

    @media (max-width: 768px) {
        .error-404-page {
            padding: 60px 20px 60px;
        }

        .error-404-number {
            font-size: 100px;
        }

        .error-404-title {
            font-size: 1.5rem;
        }

        .error-404-message {
            font-size: 1rem;
        }

        .error-404-cta {
            padding: 14px 30px;
            font-size: 1rem;
        }
    }
</style>

<main id="primary" class="site-main">
    <div class="error-404-page">
        <div class="error-404-content">
            <div class="error-404-icon">
                <i class="fas fa-paw"></i>
            </div>

            <div class="error-404-number">404</div>

            <h1 class="error-404-title">
                <?php esc_html_e( 'Oops! This Page Went to the Dog Park', 'happiness-is-pets' ); ?>
            </h1>

            <p class="error-404-message">
                <?php esc_html_e( 'We\'re sorry, but the page you\'re looking for seems to have wandered off. Don\'t worry though, we have plenty of adorable puppies waiting to meet you!', 'happiness-is-pets' ); ?>
            </p>

            <a href="<?php echo esc_url( home_url( '/puppies-for-sale/' ) ); ?>" class="error-404-cta">
                <i class="fas fa-heart"></i>
                <span><?php esc_html_e( 'View Our Adorable Puppies', 'happiness-is-pets' ); ?></span>
            </a>
        </div>
    </div>
</main>

<?php
get_footer();
