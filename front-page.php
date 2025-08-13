<?php
/**
 * The template for displaying the static front page.
 *
 * @package happiness-is-pets
 */

get_header();
?>

    <main id="primary" class="site-main">

<?php // --- Hero Section --- ?>
    <section class="front-page-hero">
        <div class="paw-background">
            <i class="fas fa-paw"></i>
            <i class="fas fa-paw"></i>
            <i class="fas fa-paw"></i>
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 p-0 hero-image mb-4 mb-md-0" style="background-image: url('<?php echo esc_url( get_theme_mod( 'front_hero_image', get_template_directory_uri() . '/assets/images/homepage_hero.png' ) ); ?>');"></div>
                <div class="col-md-6">
                    <div class="hero-content p-4 p-md-5 rounded text-center">
                        <h1 class="display-4" style="color: var(--color-primary-dark-teal);">
                            <?php echo esc_html( get_theme_mod( 'front_hero_heading', __( 'where pets find their people', 'happiness-is-pets' ) ) ); ?>
                        </h1>
                        <?php $hero_url = get_theme_mod( 'header_book_button_url', '' ); ?>
                        <?php if ( $hero_url ) : ?>
                            <a href="<?php echo esc_url( $hero_url ); ?>" class="btn btn-lg mt-3 btn-book-appointment d-inline-flex align-items-center mt-2" style="background-color: var(--color-button); color: var(--color-button-text);">
                                <i class="fa-regular fa-calendar-check me-2"></i> <?php echo esc_html( get_theme_mod( 'front_hero_button_text', __( 'Book an Appointment', 'happiness-is-pets' ) ) ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php // --- Icon Section --- ?>
    <section class="front-page-section py-5" id="dreaming-of-you">
        <div class="container">
            <div class="row text-center gy-4">
                <div class="col-md-4 icon-item">
                    <a href="<?php echo esc_url( get_theme_mod( 'front_icon1_link', '#' ) ); ?>" class="text-decoration-none d-block">
                        <img src="<?php echo esc_url( get_theme_mod( 'front_icon1_img', get_template_directory_uri() . '/assets/images/puppy_ico.png' ) ); ?>" alt="<?php esc_attr_e( 'Puppy icon', 'happiness-is-pets' ); ?>" class="mb-3" />
                        <p class="fw-bold mb-0"><?php echo esc_html( get_theme_mod( 'front_icon1_text', __( 'puppies dreaming of you', 'happiness-is-pets' ) ) ); ?></p>
                    </a>
                </div>
                <div class="col-md-4 icon-item">
                    <a href="<?php echo esc_url( get_theme_mod( 'front_icon2_link', '#' ) ); ?>" class="text-decoration-none d-block">
                        <img src="<?php echo esc_url( get_theme_mod( 'front_icon2_img', get_template_directory_uri() . '/assets/images/kittens_ico.png' ) ); ?>" alt="<?php esc_attr_e( 'Kitten icon', 'happiness-is-pets' ); ?>" class="mb-3" />
                        <p class="fw-bold mb-0"><?php echo esc_html( get_theme_mod( 'front_icon2_text', __( 'kittens dreaming of you', 'happiness-is-pets' ) ) ); ?></p>
                    </a>
                </div>
                <div class="col-md-4 icon-item">
                    <a href="<?php echo esc_url( get_theme_mod( 'front_icon3_link', '#' ) ); ?>" class="text-decoration-none d-block">
                        <img src="<?php echo esc_url( get_theme_mod( 'front_icon3_img', get_template_directory_uri() . '/assets/images/concierge.png' ) ); ?>" alt="<?php esc_attr_e( 'Concierge icon', 'happiness-is-pets' ); ?>" class="mb-3" />
                        <p class="fw-bold mb-0"><?php echo esc_html( get_theme_mod( 'front_icon3_text', __( 'concierge service', 'happiness-is-pets' ) ) ); ?></p>
                    </a>
                </div>
            </div>
        </div>
    </section>

<?php // --- Featured Pets Section --- ?>
    <section class="front-page-section py-5 bg-light mb-5" id="featured-pets">
        <div class="container">
            <h2 class="section-title text-center mb-4">
                <?php echo esc_html( get_theme_mod( 'front_featured_pets_heading', __( 'featured dream pets', 'happiness-is-pets' ) ) ); ?>
            </h2>
            <div class="row featured-pets-row align-items-center">
                <div class="col-12 col-md-9">
                    <div class="featured-pets-images d-flex flex-wrap flex-md-nowrap justify-content-between">
                        <?php
                        // Output featured pet images from Customizer settings.
                        for ( $i = 1; $i <= 3; $i++ ) {
                            $image = get_theme_mod( 'front_featured_pet_image' . $i, get_template_directory_uri() . '/assets/images/pet-placeholder-' . $i . '.jpg' );
                            $link  = get_theme_mod( 'front_featured_pet_link' . $i, '' );
                            ?>
                            <div class="featured-pet-image mb-3 mb-md-0 text-center">
                                <?php if ( $link ) : ?>
                                    <a href="<?php echo esc_url( $link ); ?>">
                                <?php endif; ?>
                                <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( sprintf( __( 'Featured Pet %d', 'happiness-is-pets' ), $i ) ); ?>" class="img-fluid">
                                <?php if ( $link ) : ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php }
                        ?>
                    </div>
                </div>
                <div class="col-12 col-md-3 text-md-start text-center mt-3 mt-md-0 d-flex align-items-center justify-content-center">
                    <a href="/all-pets/" class="view-all-pets-link d-inline-flex align-items-center">
                        <span class="me-2"><?php esc_html_e('View all Dream Pets', 'happiness-is-pets'); ?></span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

<?php // --- Testimonial Section with Swiper Slider --- ?>
<section class="front-page-section py-5 mb-5" id="happy-tails">
    <div class="container">
        <h2 class="section-title text-center mb-5">
            <?php echo esc_html( get_theme_mod( 'front_testimonial_heading', __( 'happy tails start here', 'happiness-is-pets' ) ) ); ?>
        </h2>

        <?php
        $review_query = new WP_Query( array(
            'post_type'      => 'reviews',
            'posts_per_page' => -1,
        ) );

        if ( $review_query->have_posts() ) :
        ?>
            <div class="swiper testimonial-swiper">
                <div class="swiper-wrapper">
                    <?php while ( $review_query->have_posts() ) : $review_query->the_post();
                        $rating = intval( get_post_meta( get_the_ID(), '_happiness_is_pets_review_rating', true ) );
                    ?>
                        <div class="swiper-slide">
                            <div class="text-center testimonial-content px-4">
                                <i class="fas fa-quote-left fa-2x mb-3" style="color: var(--color-secondary-light-pink);"></i>
                                <blockquote class="blockquote fs-5 fst-italic mb-3"><?php the_content(); ?></blockquote>
                                <div class="mb-2" style="color: var(--color-secondary-light-pink);">
                                    <?php
                                    for ( $i = 1; $i <= 5; $i++ ) {
                                        echo $i <= $rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                    }
                                    ?>
                                </div>
                                <footer class="blockquote-footer fw-bold" style="color: var(--color-heading);"><?php the_title(); ?></footer>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <!-- Swiper Navigation -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>
    </div>

    <img class="testimonial-decor" src="<?php echo esc_url( get_theme_mod( 'front_testimonial_image', get_template_directory_uri() . '/assets/images/reviews-image.png' ) ); ?>" alt="<?php esc_attr_e( 'Testimonial image', 'happiness-is-pets' ); ?>" />
</section>

<!-- Swiper Init Script -->
<script>
// document.addEventListener('DOMContentLoaded', function () {
//     new Swiper('.testimonial-swiper', {
//         loop: true,
//         slidesPerView: 1,
//         spaceBetween: 30,
//         pagination: {
//             el: '.swiper-pagination',
//             clickable: true,
//         },
//         navigation: {
//             nextEl: '.swiper-button-next',
//             prevEl: '.swiper-button-prev',
//         },
//     });
// });
</script>


<?php // --- Concierge Level Care Section --- ?>
    <section class="front-page-section py-5 bg-light mb-5" id="concierge-care">
        <div class="container text-center">
            <h2 class="section-title text-center mb-5">
                <?php echo esc_html( get_theme_mod( 'front_concierge_heading', __( 'concierge level care', 'happiness-is-pets' ) ) ); ?>
            </h2>
            <div class="concierge-care-content mx-auto">
                <p class="lead"><?php echo esc_html( get_theme_mod( 'front_concierge_lead', __( 'Our service and environment are designed to match the high quality of puppies and kittens in our store and meet your expectations.', 'happiness-is-pets' ) ) ); ?></p>
                <p><?php echo esc_html( get_theme_mod( 'front_concierge_desc', __( 'We think the puppies and kittens are worth it and so are you!', 'happiness-is-pets' ) ) ); ?></p>
                <a href="<?php echo esc_url( get_theme_mod( 'front_concierge_button_url', '/about/' ) ); ?>" class="btn mt-3" style="background-color: var(--color-button); color: var(--color-button-text);">
                    <i class="fas fa-info-circle me-1"></i> <?php echo esc_html( get_theme_mod( 'front_concierge_button_text', __( 'Learn more about Happiness Is Pets Boutique', 'happiness-is-pets' ) ) ); ?>
                </a>
            </div>
        </div>
    </section>

<?php // Optional: WP Editor Content for Front Page
// while ( have_posts() ) : the_post();
//  echo '<div class="container py-5 wp-content">';
//  the_content();
//  echo '</div>';
// endwhile;
?>

    </main><?php
get_footer();

