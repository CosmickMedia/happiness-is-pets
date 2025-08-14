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

<?php // --- Available Puppies Slider --- ?>
    <section class="front-page-section py-5 my-5" id="available-puppies">
        <div class="container">
            <h2 class="section-title text-center fw-bold mb-5"><?php esc_html_e( 'Available Puppies', 'happiness-is-pets' ); ?></h2>

            <?php
            $products = wc_get_products(
                array(
                    'status'   => 'publish',
                    'limit'    => -1,
                    'category' => array( 'puppies-for-sale' ),
                )
            );

            usort(
                $products,
                function( $a, $b ) {
                    $get_breed = function ( $product_id ) {
                        $breed       = '';
                        $categories  = get_the_terms( $product_id, 'product_cat' );
                        if ( $categories && ! is_wp_error( $categories ) ) {
                            foreach ( $categories as $cat ) {
                                if ( 'puppies-for-sale' !== $cat->slug ) {
                                    $breed = $cat->name;
                                    break;
                                }
                            }
                        }
                        return $breed;
                    };

                    return strcasecmp( $get_breed( $a->get_id() ), $get_breed( $b->get_id() ) );
                }
            );
            ?>

            <div class="swiper available-puppies-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ( $products as $product ) :
                        $product_id = $product->get_id();
                        $sku        = $product->get_sku();
                        $gender     = get_field( 'gender', $product_id );
                        $pet        = ( function_exists( 'wc_ukm_get_pet' ) && ( $p = wc_ukm_get_pet( $product_id ) ) ) ? $p : new stdClass();
                        $birth_date = ! empty( $pet->dob ) && strtotime( $pet->dob ) ? date( 'm-d-Y', strtotime( $pet->dob ) ) : '';
                        $location   = ! empty( $pet->location ) ? $pet->location : '';
                        $breed      = '';
                        $categories = get_the_terms( $product_id, 'product_cat' );
                        if ( $categories && ! is_wp_error( $categories ) ) {
                            foreach ( $categories as $cat ) {
                                if ( 'puppies-for-sale' !== $cat->slug ) {
                                    $breed = $cat->name;
                                    break;
                                }
                            }
                        }
                        ?>
                        <div class="swiper-slide">
                            <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="available-puppy-card d-block position-relative">
                                <?php echo $product->get_image( 'medium', array( 'class' => 'img-fluid rounded' ) ); ?>
                                <div class="available-puppy-info position-absolute top-0 start-0 w-100 h-100 p-3 d-flex flex-column justify-content-center align-items-center text-center">
                                    <?php if ( $breed ) : ?>
                                        <h5 class="fw-bold mb-2"><?php echo esc_html( $breed ); ?></h5>
                                    <?php endif; ?>
                                    <ul class="list-unstyled small mb-0">
                                        <?php if ( $sku ) : ?>
                                            <li><strong><?php esc_html_e( 'Ref:', 'happiness-is-pets' ); ?></strong> #<?php echo esc_html( $sku ); ?></li>
                                        <?php endif; ?>
                                        <?php if ( $birth_date ) : ?>
                                            <li><strong><?php esc_html_e( 'DOB:', 'happiness-is-pets' ); ?></strong> <?php echo esc_html( $birth_date ); ?></li>
                                        <?php endif; ?>
                                        <?php if ( $gender ) : ?>
                                            <li><strong><?php esc_html_e( 'Gender:', 'happiness-is-pets' ); ?></strong> <?php echo esc_html( $gender ); ?></li>
                                        <?php endif; ?>
                                        <?php if ( $location ) : ?>
                                            <li><strong><?php esc_html_e( 'Location:', 'happiness-is-pets' ); ?></strong> <?php echo esc_html( $location ); ?></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

<script>
document.addEventListener( 'DOMContentLoaded', function() {
    new Swiper( '.available-puppies-swiper', {
        slidesPerView: 6,
        spaceBetween: 20,
        navigation: {
            nextEl: '.available-puppies-swiper .swiper-button-next',
            prevEl: '.available-puppies-swiper .swiper-button-prev',
        },
        breakpoints: {
            0: { slidesPerView: 1 },
            576: { slidesPerView: 2 },
            768: { slidesPerView: 3 },
            992: { slidesPerView: 4 },
            1200: { slidesPerView: 6 },
        },
    } );
} );
</script>

<?php // --- Canine Care Certified Section --- ?>
    <section class="front-page-section canine-care-certified py-5 my-5" id="canine-care-certified">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0 text-center text-md-start">
                    <h2 class="section-title fw-bold mb-3"><?php esc_html_e( 'Canine Care Certified', 'happiness-is-pets' ); ?></h2>
                    <p class="text-muted mb-3"><?php esc_html_e( 'Administered by Purdue University', 'happiness-is-pets' ); ?></p>
                    <p><?php esc_html_e( 'With an emphasis on offering the best puppies possible from the healthiest and most socially adjusted dogs, we visit many of our breeders on a monthly basis. If you are looking for a purebred puppy with us, you can do so with confidence. We are proud to be Canine Care Certified – Canine Care Certified goes above and beyond currently available canine welfare standards programs. The program sets forth rigorous, science-based, expert-reviewed standards for canine physical and behavioral welfare in areas such as nutrition, veterinary care, housing, handling and exercise.', 'happiness-is-pets' ); ?></p>
                    <a href="/breeders/" class="btn btn-primary mt-3"><?php esc_html_e( 'Learn More', 'happiness-is-pets' ); ?></a>
                </div>
                <div class="col-md-6 text-center">
                    <img src="/media/background/caninecare.webp" alt="<?php esc_attr_e( 'Canine Care Certified', 'happiness-is-pets' ); ?>" class="img-fluid rounded" />
                </div>
            </div>
        </div>
    </section>

    <div class="d-none d-md-block">
        <div class="info-preview-section py-3 infofirst-4991">
            <div class="info-layout-wrap no-hover">
                <div class="info-layout-wrap-inner">
                    <div class="info-layout-box container">
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-4 order-1 order-md-1 d-flex align-items-center">
                                <div class="info-layout-content-img">
                                    <img src="/media/background/ourpuppies.webp" alt="" class="info_placeholder img-fluid infofirst-img" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-8 align-items-center d-flex order-2 order-md-2">
                                <div class="info-layout-content">
                                    <div class="info-layout-content-inner">
                                        <h2 class="h2-theme-bold mb-3 infofirst-title">
                                            <p>Take Home Your Newest Addition!</p>
                                        </h2>
                                        <div class="h3-theme-bold mb-3 infofirst-sub-title infofirst-sub-title"></div>
                                        <div class="infofirst-message">
                                            <p><span style="color:#B8B8B8;font-size:20px;">Healthy, Happy, and Adorable</span></p>
                                            <p>Quick and easy financing is available. Apply Now!</p>
                                        </div>
                                        <div class="info-layout-action d-flex btn-flex">
                                            <a style="white-space:nowrap" class="mt-1 F2 sw-100" href="https://app.formpiper.com/outside-form/happiness-is-pets-schererville/fTlGP4zm9zLqayP9FI9M1nB4JACHgY?qr=true" target="_blank">
                                                Apply Now - Schereville
                                            </a>
                                            <a style="white-space:nowrap" class="mt-1 F2 sw-100" href="https://app.formpiper.com/outside-form/happiness-is-pets-indianapolis/tjtpqDXcm2p4H4oOd37nKRsg5xFwGa" target="_blank">
                                                Apply Now - Indianapolis
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- half image and half text and button area -->

    </div>

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

