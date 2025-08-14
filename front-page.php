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

<?php // --- Reviews Section --- ?>
    <section class="front-page-section py-5" id="reviews">
        <div class="container text-center" style="background-color: #FAF7F1; border-radius: 4px; margin-bottom: 90px; margin-top: 10px; padding-top: 81px; padding-bottom: 1px;">
            <div class="container carousel-5007 carousel-plugin px-4 mb-80">
                <div class="h2-theme-bold carous-title-5007 text-center title_text">See What Our Customers Say</div>
                <div class="mt-4">
                    <div class="carousel-layout-wrap">
                        <?php
                        $reviews = array(
                            array(
                                'title'  => 'Happy Family!',
                                'text'   => 'I have been on a waiting list for a standard poodle for two years. Kaitlyn was the one who notified me when a red standard poodle litter finally came into the store. She helped us thoroughly with the financial options as well as giving us more than enough supplies for our new puppy. Her care with us and the puppy was so helpful. We are so thrilled with Louie; he’s happy, healthy, and living his best life! Also, Louie is from a breeder in Indiana, we were given the information on the breeder and Louie’s parents.',
                                'author' => 'The Vincenzo Family',
                            ),
                            array(
                                'title'  => 'What a Great Job!',
                                'text'   => 'Great experience purchasing a pup here, their health guarantee and coverage for health issues that may arise after purchasing is above and beyond. Their staff cares for these pups like their own and it shows. Great job and at the end of the day it is about finding great homes for these animals aside from where they came from.',
                                'author' => 'Richard Roy',
                            ),
                            array(
                                'title'  => 'Great Experience',
                                'text'   => 'These guys are great they really know the breeds and get to know the dogs on an individual basis too. There are no high pressure sales just a genuine desire to see each dog go to a good home.',
                                'author' => 'The Plachno Family',
                            ),
                        );
                        ?>
                        <div class="row justify-content-center">
                            <?php foreach ( $reviews as $review ) : ?>
                                <div class="col-md-4 mb-4">
                                    <div class="carousel-item-box carousel-item-box-style-6 h-100">
                                        <div class="carousel-item-box-desc mx-4">
                                            <div class="carousel-item-box-desc-title"><?php echo esc_html( $review['title'] ); ?></div>
                                            <div class="carousel-item-box-desc-desc"><?php echo esc_html( $review['text'] ); ?></div>
                                            <div class="carousel-item-box-desc-auth">- <?php echo esc_html( $review['author'] ); ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="carousel-layout-wrap-footer text-center pt-3">
                            <button class="mt-2 theme-primary-btn" onclick="onNavigationClick('/testimonials/')">
                                Submit Your Own Story
                            </button>
                        </div>
                    </div>
                </div>
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

