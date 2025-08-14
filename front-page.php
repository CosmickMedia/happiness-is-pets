<?php
/**
 * The template for displaying the static front page.
 * Fully integrated with customizer settings
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
                    <?php $hero_image = get_theme_mod( 'hero_image' ); ?>
                    <div class="col-12 col-md-6 p-0 hero-image mb-4 mb-md-0"<?php if ( $hero_image ) : ?> style="background-image: url('<?php echo esc_url( $hero_image ); ?>');"<?php endif; ?>></div>
                    <div class="col-md-6">
                        <div class="hero-content p-4 p-md-5 rounded text-center">
                            <?php if ( $hero_heading = get_theme_mod( 'hero_heading' ) ) : ?>
                                <h1 class="display-4 fw-bold mb-3" style="color: var(--color-primary-dark-teal); font-size: 2rem;">
                                    <?php echo esc_html( $hero_heading ); ?>
                                </h1>
                            <?php endif; ?>
                            <?php if ( $hero_text = get_theme_mod( 'hero_text' ) ) : ?>
                                <p class="lead mb-4"><?php echo esc_html( $hero_text ); ?></p>
                            <?php endif; ?>
                            <?php
                            $hero_button_text = get_theme_mod( 'hero_button_text' );
                            $hero_button_url  = get_theme_mod( 'hero_button_url' );
                            if ( $hero_button_text && $hero_button_url ) :
                            ?>
                                <a href="<?php echo esc_url( $hero_button_url ); ?>" class="theme-primary-btn mt-2">
                                    <?php echo esc_html( $hero_button_text ); ?>
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
                <h2 class="section-title text-center fw-bold mb-5"><?php echo esc_html( get_theme_mod( 'puppies_section_title', 'Available Puppies' ) ); ?></h2>

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
                                <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="available-puppy-card d-block text-center">
                                    <?php echo $product->get_image( 'medium', array( 'class' => 'img-fluid rounded' ) ); ?>
                                    <?php if ( $breed ) : ?>
                                        <h5 class="fw-bold mt-2 mb-1"><?php echo esc_html( $breed ); ?></h5>
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
                                    </ul>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
                <div class="text-center">
                    <a href="<?php echo esc_url( get_theme_mod( 'puppies_button_url', '/all-pets/' ) ); ?>" class="see-all-puppies btn">
                        <?php echo esc_html( get_theme_mod( 'puppies_button_text', 'See All Puppies' ) ); ?>
                    </a>
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
                        <h2 class="section-title fw-bold mb-3"><?php echo esc_html( get_theme_mod( 'canine_care_title', 'Canine Care Certified' ) ); ?></h2>
                        <p class="text-muted mb-3"><?php echo esc_html( get_theme_mod( 'canine_care_subtitle', 'Administered by Purdue University' ) ); ?></p>
                        <p><?php echo esc_html( get_theme_mod( 'canine_care_text', 'With an emphasis on offering the best puppies possible from the healthiest and most socially adjusted dogs, we visit many of our breeders on a monthly basis. If you are looking for a purebred puppy with us, you can do so with confidence. We are proud to be Canine Care Certified – Canine Care Certified goes above and beyond currently available canine welfare standards programs. The program sets forth rigorous, science-based, expert-reviewed standards for canine physical and behavioral welfare in areas such as nutrition, veterinary care, housing, handling and exercise.' ) ); ?></p>
                        <a href="<?php echo esc_url( get_theme_mod( 'canine_care_button_url', '/breeders/' ) ); ?>" class="btn btn-primary mt-3">
                            <?php echo esc_html( get_theme_mod( 'canine_care_button_text', 'Learn More' ) ); ?>
                        </a>
                    </div>
                    <div class="col-md-6 text-center">
                        <img src="<?php echo esc_url( get_theme_mod( 'canine_care_image', get_template_directory_uri() . '/assets/images/caninecare.webp' ) ); ?>" alt="<?php esc_attr_e( 'Canine Care Certified', 'happiness-is-pets' ); ?>" class="img-fluid rounded" />
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
                                        <img src="<?php echo esc_url( get_theme_mod( 'financing_image', get_template_directory_uri() . '/assets/images/ourpuppies.webp' ) ); ?>" alt="" class="info_placeholder img-fluid infofirst-img" />
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-8 align-items-center d-flex order-2 order-md-2">
                                    <div class="info-layout-content">
                                        <div class="info-layout-content-inner">
                                            <h2 class="h2-theme-bold mb-3 infofirst-title">
                                                <p><?php echo esc_html( get_theme_mod( 'financing_title', 'Take Home Your Newest Addition!' ) ); ?></p>
                                            </h2>
                                            <div class="h3-theme-bold mb-3 infofirst-sub-title infofirst-sub-title"></div>
                                            <div class="infofirst-message">
                                                <p><span style="color:#B8B8B8;font-size:20px;"><?php echo esc_html( get_theme_mod( 'financing_subtitle', 'Healthy, Happy, and Adorable' ) ); ?></span></p>
                                                <p><?php echo esc_html( get_theme_mod( 'financing_text', 'Quick and easy financing is available. Apply Now!' ) ); ?></p>
                                            </div>
                                            <div class="info-layout-action d-flex btn-flex">
                                                <a style="white-space:nowrap" class="theme-primary-btn mt-1 sw-100" href="<?php echo esc_url( get_theme_mod( 'financing_button1_url', 'https://app.formpiper.com/outside-form/happiness-is-pets-schererville/fTlGP4zm9zLqayP9FI9M1nB4JACHgY?qr=true' ) ); ?>" target="_blank">
                                                    <?php echo esc_html( get_theme_mod( 'financing_button1_text', 'Apply Now - Schereville' ) ); ?>
                                                </a>
                                                <a style="white-space:nowrap" class="theme-primary-btn mt-1 sw-100" href="<?php echo esc_url( get_theme_mod( 'financing_button2_url', 'https://app.formpiper.com/outside-form/happiness-is-pets-indianapolis/tjtpqDXcm2p4H4oOd37nKRsg5xFwGa' ) ); ?>" target="_blank">
                                                    <?php echo esc_html( get_theme_mod( 'financing_button2_text', 'Apply Now - Indianapolis' ) ); ?>
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
        </div>

        <?php // --- Our Guarantee Section --- ?>
        <section class="front-page-section py-5 my-5" id="our-guarantee">
            <div class="container">
                <h2 class="text-center"><?php echo esc_html( get_theme_mod( 'guarantee_title', 'Our Guarantee' ) ); ?></h2>
                <p class="text-center"><span style="font-size: 20px;"><?php echo esc_html( get_theme_mod( 'guarantee_subtitle', 'Peace of mind for you and your puppy' ) ); ?></span></p>
                <div class="row justify-content-center">
                    <div class="col col-12 col-md-4 align-self-center">
                        <div class="container d-flex flex-column justify-content-center align-items-center">
                            <img src="<?php echo esc_url( get_theme_mod( 'guarantee_1_image', get_template_directory_uri() . '/assets/images/health-warranty.webp__88.0x77.0_subsampling-2.webp' ) ); ?>" alt="" class="img-fluid" />
                            <div class="my-2 text-center">
                                <p class="primary1-bold"><?php echo esc_html( get_theme_mod( 'guarantee_1_title', '2 year health warranty' ) ); ?></p>
                                <p><?php echo esc_html( get_theme_mod( 'guarantee_1_text', 'Since many, but not all congenital defects arise within the first year, we go the extra mile to ensure you and your new puppy are covered.' ) ); ?></p>
                            </div>
                            <a href="<?php echo esc_url( get_theme_mod( 'guarantee_1_button_url', '/health-warranty/' ) ); ?>" class="theme-primary-btn" style="display: block; width: fit-content;">
                                <?php echo esc_html( get_theme_mod( 'guarantee_1_button_text', 'Health Warranty' ) ); ?>
                            </a>
                        </div>
                    </div>

                    <div class="col col-12 col-md-4 align-self-center my-5 my-md-0">
                        <div class="container d-flex flex-column justify-content-center align-items-center">
                            <img src="<?php echo esc_url( get_theme_mod( 'guarantee_2_image', get_template_directory_uri() . '/assets/images/veterinary-check.webp__81.0x68.0_subsampling-2.webp' ) ); ?>" alt="" class="img-fluid" />
                            <div class="my-2 text-center">
                                <p class="primary1-bold"><?php echo esc_html( get_theme_mod( 'guarantee_2_title', '7 day veterinary check' ) ); ?></p>
                                <p><?php echo esc_html( get_theme_mod( 'guarantee_2_text', 'Bring your new puppy to any of our in-network clinics within 7 days of purchase for a complimentary wellness check-up.' ) ); ?></p>
                            </div>
                            <a href="<?php echo esc_url( get_theme_mod( 'guarantee_2_button_url', '/veterinary-check/' ) ); ?>" class="theme-primary-btn" style="display: block; width: fit-content;">
                                <?php echo esc_html( get_theme_mod( 'guarantee_2_button_text', 'Veterinary Check' ) ); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php // --- Reviews Section --- ?>
        <section class="front-page-section py-5" id="reviews">
            <div class="container text-center">
                <div class="container carousel-5007 carousel-plugin px-4 mb-80">
                    <div class="h2-theme-bold carous-title-5007 text-center title_text"><?php echo esc_html( get_theme_mod( 'reviews_title', 'See What Our Customers Say' ) ); ?></div>
                    <div class="mt-4">
                        <div class="carousel-layout-wrap">
                            <?php
                            // Static reviews for demonstration - can be replaced with dynamic content later
                            $reviews = array(
                                    array(
                                            'title'  => 'Happy Family!',
                                            'text'   => 'I have been on a waiting list for a standard poodle for two years. Kaitlyn was the one who notified me when a red standard poodle litter finally came into the store. She helped us thoroughly with the financial options as well as giving us more than enough supplies for our new puppy. Her care with us and the puppy was so helpful. We are so thrilled with Louie; he\'s happy, healthy, and living his best life! Also, Louie is from a breeder in Indiana, we were given the information on the breeder and Louie\'s parents.',
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
                                <a href="<?php echo esc_url( get_theme_mod( 'reviews_button_url', '/testimonials/' ) ); ?>" class="mt-2 theme-primary-btn">
                                    <?php echo esc_html( get_theme_mod( 'reviews_button_text', 'Submit Your Own Story' ) ); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>
<?php
get_footer();