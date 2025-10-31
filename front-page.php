<?php
/**
 * The template for displaying the static front page.
 * Fully integrated with customizer settings
 *
 * @package happiness-is-pets
 */

get_header();

// Preload hero image for LCP optimization
$hero_image = get_theme_mod( 'hero_image' );
if ( $hero_image ) {
    echo '<link rel="preload" as="image" href="' . esc_url( $hero_image ) . '" fetchpriority="high">' . "\n";
}
?>

    <style>
        /* Critical CSS for Hero Section - Inline for faster LCP */
        .hero-img {
            width: 100%;
            height: auto;
            object-fit: cover;
            display: block;
        }
        @media (max-width: 767px) {
            .hero-img {
                max-height: 400px;
            }
        }
    </style>

    <main id="primary" class="site-main">

        <?php // --- Hero Section --- ?>
        <section class="front-page-hero">
            <div class="paw-background" aria-hidden="true">
                <i class="fas fa-paw"></i>
                <i class="fas fa-paw"></i>
                <i class="fas fa-paw"></i>
            </div>
            <div class="container">
                <div class="row align-items-center">
                    <?php
                    $hero_image        = get_theme_mod( 'hero_image' );
                    $hero_image_mobile = get_theme_mod( 'hero_image_mobile' );
                    ?>
                    <div class="col-12 col-md-6 p-0 hero-image mb-4 mb-md-0">
                        <?php if ( $hero_image ) : ?>
                            <picture>
                                <?php if ( $hero_image_mobile ) : ?>
                                    <source media="(max-width: 767px)" srcset="<?php echo esc_url( $hero_image_mobile ); ?>" type="image/webp">
                                <?php endif; ?>
                                <img src="<?php echo esc_url( $hero_image ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) . ' - ' . get_theme_mod( 'hero_heading', 'Welcome' ) ); ?>" width="800" height="600" fetchpriority="high" loading="eager" class="hero-img">
                            </picture>
                        <?php endif; ?>
                    </div>
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
                            $categories = get_the_terms( $product_id, 'product_cat' );
                            $first_cat  = $categories && ! is_wp_error( $categories ) ? array_shift( $categories ) : false;
                            $gender = get_field('gender', $product_id);

                            $gender_class = '';
                            if ( strtolower($gender) === 'female' ) {
                                $gender_class = 'gender-female';
                            } elseif ( strtolower($gender) === 'male' ) {
                                $gender_class = 'gender-male';
                            }

                            $pet = ( function_exists( 'wc_ukm_get_pet' ) && ( $p = wc_ukm_get_pet( $product_id ) ) ) ? $p : new stdClass();
                            $pet_name = $product->get_meta( 'pet_name' ) ?: $product->get_name();
                            $ref_id = $product->get_meta('reference_number') ?: $sku;
                            $birth_date = ! empty( $pet->dob ) && strtotime( $pet->dob ) ? date( 'm-d-Y', strtotime( $pet->dob ) ) : '';
                            $location = ! empty( $pet->location ) ? $pet->location : '';
                            $breed = ! empty( $pet->breed ) ? $pet->breed : ( $first_cat ? $first_cat->name : '' );

                            // Dynamic phone number based on location
                            $location_phone = get_theme_mod('header_phone_number', '317-537-2480');
                            if (!empty($location)) {
                                $location_lower = strtolower(trim($location));
                                if (strpos($location_lower, 'indianapolis') !== false) {
                                    $location_phone = get_theme_mod('location_1_phone', '317-537-2480');
                                } elseif (strpos($location_lower, 'schererville') !== false) {
                                    $location_phone = get_theme_mod('location_2_phone', '219-865-3078');
                                }
                            }
                            ?>
                            <div class="swiper-slide">
                                <div class="card pet-card shadow-sm border-0 rounded-3 overflow-hidden transition-hover h-100">
                                    <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="text-decoration-none position-relative" aria-label="<?php echo esc_attr( sprintf( __( 'View details for %s', 'happiness-is-pets' ), $pet_name ) ); ?>">
                                        <div class="position-relative">
                                            <?php
                                            // Get the product image - use exact same method as the working slider
                                            $image_id = $product->get_image_id();
                                            if ( $image_id ) {
                                                echo wp_get_attachment_image(
                                                    $image_id,
                                                    'medium',
                                                    false,
                                                    array(
                                                        'class' => 'img-fluid w-100',
                                                        'alt' => esc_attr( $breed ? $breed . ' puppy' : $pet_name )
                                                    )
                                                );
                                            } else {
                                                // Show placeholder if no image
                                                echo '<img src="' . esc_url( wc_placeholder_img_src() ) . '" alt="' . esc_attr( $pet_name ) . '" class="img-fluid w-100" />';
                                            }
                                            ?>
                                        </div>
                                    </a>

                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col">
                                                <div class="pet-breed mb-1">
                                                    <?php if ( $first_cat ) : ?>
                                                        <span class="badge rounded-pill fs-6">
                                                            <a class="text-reset" href="<?php echo esc_url( get_term_link( $first_cat ) ); ?>" rel="tag">
                                                                <?php echo esc_html( $first_cat->name ); ?>
                                                            </a>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>

                                                <h5 class="card-title pet-name fw-bold mb-2"><?php echo esc_html( $pet_name . ' - ' . $ref_id ); ?></h5>

                                                <div class="card-text">
                                                    <?php if ( $breed ) : ?>
                                                    <div class="pet-detail pet-breed-detail d-flex align-items-center mb-1">
                                                        <strong class="me-1"><?php esc_html_e( 'Breed:', 'happiness-is-pets' ); ?></strong><span> <?php echo esc_html( $breed ); ?></span>
                                                    </div>
                                                    <?php endif; ?>

                                                    <?php if ( $gender ) : ?>
                                                    <div class="pet-detail pet-gender d-flex align-items-center mb-1">
                                                        <strong class="me-1"><?php esc_html_e( 'Gender:', 'happiness-is-pets' ); ?></strong><span class="<?php echo esc_html($gender_class); ?>"> <?php echo esc_html( $gender ); ?></span>
                                                    </div>
                                                    <?php endif; ?>

                                                    <?php if ( $birth_date ) : ?>
                                                    <div class="pet-detail pet-dob d-flex align-items-center mb-1">
                                                        <strong class="me-1"><?php esc_html_e( 'DOB:', 'happiness-is-pets' ); ?></strong><span> <?php echo esc_html( $birth_date ); ?></span>
                                                    </div>
                                                    <?php endif; ?>

                                                    <?php if ( $location ) : ?>
                                                    <div class="pet-detail pet-location d-flex align-items-center mb-1">
                                                        <i class="fas fa-map-marker-alt me-1"></i><span> <?php echo esc_html( $location ); ?></span>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-footer bg-transparent border-top-0 text-center p-3">
                                        <div class="row g-2 mb-2">
                                            <div class="col-6">
                                                <?php if ( $location_phone ) : ?>
                                                <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $location_phone ) ); ?>"
                                                   class="btn w-100"
                                                   style="background: white; color: var(--color-primary-dark-grey); border: 3px solid var(--color-primary-dark-grey);">
                                                    <i class="fas fa-phone me-md-1"></i><span class="d-none d-md-inline"><?php esc_html_e( 'Call', 'happiness-is-pets' ); ?></span>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-6">
                                                <a href="#petDetailsModal-<?php echo esc_attr( $product_id ); ?>"
                                                   data-bs-toggle="modal"
                                                   class="btn btn-secondary-theme w-100 pet-details-trigger"
                                                   style="background-color: var(--color-primary-dark-teal) !important; color: var(--color-button-text) !important;"
                                                   data-product-id="<?php echo esc_attr( $product_id ); ?>"
                                                   data-pet-name="<?php echo esc_attr( $pet_name ); ?>"
                                                   data-ref-id="<?php echo esc_attr( $ref_id ); ?>"
                                                   data-breed="<?php echo esc_attr( $first_cat ? $first_cat->name : '' ); ?>"
                                                   data-gender="<?php echo esc_attr( $gender ); ?>"
                                                   data-birth-date="<?php echo esc_attr( $birth_date ); ?>"
                                                   data-location="<?php echo esc_attr( $location ); ?>"
                                                   data-product-url="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
                                                    <i class="fas fa-paper-plane me-md-1"></i><span class="d-none d-md-inline"><?php esc_html_e( 'Contact', 'happiness-is-pets' ); ?></span>
                                                </a>
                                            </div>
                                        </div>
                                        <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="btn btn-primary-theme w-100"><?php esc_html_e( 'Learn More', 'happiness-is-pets' ); ?></a>
                                    </div>
                                </div>
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

                <?php // Modal section - outside swiper to avoid z-index stacking context issues ?>
                <?php foreach ( $products as $product ) :
                    $product_id = $product->get_id();
                    $pet = ( function_exists( 'wc_ukm_get_pet' ) && ( $p = wc_ukm_get_pet( $product_id ) ) ) ? $p : new stdClass();
                    $pet_name = $product->get_meta( 'pet_name' ) ?: $product->get_name();
                ?>
                    <!-- Pet Details Modal -->
                    <div class="modal fade" id="petDetailsModal-<?php echo esc_attr( $product_id ); ?>" tabindex="-1" aria-labelledby="petDetailsModalLabel-<?php echo esc_attr( $product_id ); ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="petDetailsModalLabel-<?php echo esc_attr( $product_id ); ?>">Get Details About <?php echo esc_html( $pet_name ); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <?php if ( shortcode_exists( 'gravityform' ) ) {
                                        echo do_shortcode( '[gravityform id="3" title="false" description="false" ajax="true"]' );
                                    } else {
                                        echo '<p style="color: #ef4444;">Contact form unavailable.</p>';
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <script>
            document.addEventListener( 'DOMContentLoaded', function() {
                // Only initialize Swiper on tablet and desktop (768px and up)
                // Mobile uses native horizontal scroll
                if (window.innerWidth >= 768) {
                    new Swiper( '.available-puppies-swiper', {
                        slidesPerView: 6,
                        spaceBetween: 20,
                        navigation: {
                            nextEl: '.available-puppies-swiper .swiper-button-next',
                            prevEl: '.available-puppies-swiper .swiper-button-prev',
                        },
                        grabCursor: true,
                        speed: 400,
                        breakpoints: {
                            768: { slidesPerView: 4 },
                            992: { slidesPerView: 5 },
                            1200: { slidesPerView: 6 },
                        },
                    } );
                } else {
                    // Setup mobile horizontal scroll
                    var swiperContainer = document.querySelector('.available-puppies-swiper');
                    var swiperWrapper = document.querySelector('.available-puppies-swiper .swiper-wrapper');
                    if (swiperContainer && swiperWrapper) {
                        // Force horizontal scroll on mobile
                        swiperContainer.style.overflowX = 'scroll';
                        swiperContainer.style.overflowY = 'hidden';
                        swiperContainer.style.WebkitOverflowScrolling = 'touch';

                        swiperWrapper.style.display = 'flex';
                        swiperWrapper.style.flexWrap = 'nowrap';
                        swiperWrapper.style.width = 'auto';
                        swiperWrapper.style.transform = 'none';

                        // Set each slide width
                        var slides = swiperWrapper.querySelectorAll('.swiper-slide');
                        slides.forEach(function(slide) {
                            slide.style.flexShrink = '0';
                            slide.style.width = '280px';
                        });
                    }
                }
                new Swiper( '.reviews-swiper', {
                    slidesPerView: 3,
                    spaceBetween: 20,
                    navigation: {
                        nextEl: '.reviews-swiper .swiper-button-next',
                        prevEl: '.reviews-swiper .swiper-button-prev',
                    },
                    breakpoints: {
                        0: { slidesPerView: 1.2 },
                        768: { slidesPerView: 2 },
                        992: { slidesPerView: 3 },
                    },
                } );
            } );

            // Pet Details Modal Handler for Homepage
            (function() {
                'use strict';

                // Prevent multiple initializations
                if (window.petDetailsModalHandlerInitialized) {
                    return;
                }
                window.petDetailsModalHandlerInitialized = true;

                // Function to populate form fields
                function populatePetDetailsForm(modal, button) {
                    console.log('🔍 populatePetDetailsForm called');
                    console.log('Modal:', modal);
                    console.log('Button:', button);

                    if (!button || !button.classList.contains('pet-details-trigger')) {
                        console.warn('⚠️ Button is missing or does not have pet-details-trigger class');
                        return;
                    }

                    // Extract product data from button's data attributes
                    const productData = {
                        productId: button.getAttribute('data-product-id'),
                        petName: button.getAttribute('data-pet-name'),
                        refId: button.getAttribute('data-ref-id'),
                        breed: button.getAttribute('data-breed'),
                        gender: button.getAttribute('data-gender'),
                        birthDate: button.getAttribute('data-birth-date'),
                        location: button.getAttribute('data-location'),
                        productUrl: button.getAttribute('data-product-url')
                    };

                    console.log('📦 Product data extracted:', productData);

                    // Wait a brief moment for Gravity Forms to fully render
                    setTimeout(function() {
                        const modalBody = modal.querySelector('.modal-body');
                        if (!modalBody) {
                            console.error('❌ Modal body not found');
                            return;
                        }

                        console.log('✅ Modal body found, looking for form fields...');

                        // Map of CSS classes to product data
                        const fieldMap = {
                            'gf-pet-name': productData.petName,
                            'gf-ref-id': productData.refId,
                            'gf-breed': productData.breed,
                            'gf-gender': productData.gender,
                            'gf-birth-date': productData.birthDate,
                            'gf-location': productData.location,
                            'gf-product-url': productData.productUrl
                        };

                        // Try multiple selector methods to find fields
                        let foundCount = 0;
                        Object.keys(fieldMap).forEach(function(className) {
                            const value = fieldMap[className];
                            let field = null;

                            // Method 1: Look for gfield wrapper with the CSS class (most common in Gravity Forms)
                            let gfieldWrapper = modalBody.querySelector('.gfield.' + className);
                            if (gfieldWrapper) {
                                console.log('   Found gfield wrapper for ' + className);
                                field = gfieldWrapper.querySelector('input, textarea, select');
                            }

                            // Method 2: Look for the class on any li element
                            if (!field) {
                                let liWrapper = modalBody.querySelector('li.' + className);
                                if (liWrapper) {
                                    console.log('   Found li wrapper for ' + className);
                                    field = liWrapper.querySelector('input, textarea, select');
                                }
                            }

                            // Method 3: Look for ginput_container with the class
                            if (!field) {
                                let fieldContainer = modalBody.querySelector('.ginput_container.' + className);
                                if (fieldContainer) {
                                    console.log('   Found ginput_container for ' + className);
                                    field = fieldContainer.querySelector('input, textarea, select');
                                }
                            }

                            // Method 4: Direct input/textarea/select with the class
                            if (!field) {
                                field = modalBody.querySelector('input.' + className + ', textarea.' + className + ', select.' + className);
                                if (field) {
                                    console.log('   Found direct input for ' + className);
                                }
                            }

                            if (field) {
                                console.log('🔍 Found field for ' + className + ':', field);
                                console.log('   Field type:', field.tagName, field.type);
                                console.log('   Field ID:', field.id);
                                console.log('   Setting value to:', value);

                                // Set the value using multiple methods
                                field.value = value;

                                // Use setAttribute as well for good measure
                                field.setAttribute('value', value);

                                foundCount++;

                                // Trigger events using both native JS and jQuery
                                if (window.jQuery && jQuery(field).length) {
                                    console.log('   Using jQuery to set value');
                                    jQuery(field).val(value).trigger('input').trigger('change').trigger('blur');
                                } else {
                                    console.log('   Using native JS to trigger events');
                                    field.dispatchEvent(new Event('input', { bubbles: true }));
                                    field.dispatchEvent(new Event('change', { bubbles: true }));
                                    field.dispatchEvent(new Event('blur', { bubbles: true }));
                                }

                                console.log('✅ Populated field:', className, '=', value);
                                console.log('   Field value after setting:', field.value);
                            } else {
                                console.warn('⚠️ Field not found for class:', className);
                                console.log('   Tried selectors:');
                                console.log('   - .gfield.' + className + ' input/textarea/select');
                                console.log('   - li.' + className + ' input/textarea/select');
                                console.log('   - .ginput_container.' + className + ' input/textarea/select');
                                console.log('   - input/textarea/select.' + className);
                            }
                        });

                        console.log('📊 Summary: Populated ' + foundCount + ' out of ' + Object.keys(fieldMap).length + ' fields');

                        if (foundCount === 0) {
                            console.error('❌ NO FIELDS WERE POPULATED! Check:');
                            console.log('1. Are fields added to Gravity Form #3?');
                            console.log('2. Do they have CSS classes in the "Custom CSS Class" field: gf-product-id, gf-pet-name, etc.?');
                            console.log('3. All form fields in modal:');
                            console.log('   Inputs:', modalBody.querySelectorAll('input'));
                            console.log('   Textareas:', modalBody.querySelectorAll('textarea'));
                            console.log('   Selects:', modalBody.querySelectorAll('select'));
                            console.log('   All li elements:', modalBody.querySelectorAll('li[class*="gfield"]'));
                        }

                    }, 500); // Increased delay to 500ms for Gravity Forms AJAX
                }

                // Listen for Bootstrap modal show event
                document.addEventListener('show.bs.modal', function(event) {
                    const modal = event.target;

                    // Only process pet details modals
                    if (!modal.id || !modal.id.startsWith('petDetailsModal-')) {
                        return;
                    }

                    console.log('🎯 Pet Details Modal opening:', modal.id);

                    // Get the button that triggered the modal
                    const button = event.relatedTarget;
                    populatePetDetailsForm(modal, button);
                });

                console.log('✅ Pet Details Modal Handler Initialized');
            })();
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
                            <?php echo esc_html( get_theme_mod( 'canine_care_button_text', 'Learn More About Our Canine Care Certified Breeders' ) ); ?>
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
                                        <img src="<?php echo esc_url( get_theme_mod( 'financing_image', get_template_directory_uri() . '/assets/images/ourpuppies.webp' ) ); ?>" alt="<?php echo esc_attr( get_theme_mod( 'financing_title', 'Take Home Your Newest Addition!' ) ); ?>" class="info_placeholder img-fluid infofirst-img" />
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
                            <img src="<?php echo esc_url( get_theme_mod( 'guarantee_1_image', get_template_directory_uri() . '/assets/images/health-warranty.webp__88.0x77.0_subsampling-2.webp' ) ); ?>" alt="<?php echo esc_attr( get_theme_mod( 'guarantee_1_title', '2 year health warranty' ) ); ?>" class="img-fluid" />
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
                            <img src="<?php echo esc_url( get_theme_mod( 'guarantee_2_image', get_template_directory_uri() . '/assets/images/veterinary-check.webp__81.0x68.0_subsampling-2.webp' ) ); ?>" alt="<?php echo esc_attr( get_theme_mod( 'guarantee_2_title', '7 day veterinary check' ) ); ?>" class="img-fluid" />
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
                            <div class="swiper reviews-swiper">
                                <div class="swiper-wrapper">
                                    <?php foreach ( $reviews as $review ) : ?>
                                        <div class="swiper-slide">
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
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
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

        <?php // --- Latest Blog Section --- ?>
        <section class="front-page-section py-5" id="latest-blog">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center mb-5">
                        <h2 class="h2-theme-bold title_text"><?php echo esc_html( get_theme_mod( 'blog_title', 'Latest from Our Blog' ) ); ?></h2>
                        <p class="lead text-muted"><?php echo esc_html( get_theme_mod( 'blog_subtitle', 'Stay updated with our latest pet care tips, news, and stories' ) ); ?></p>
                    </div>
                </div>
                
                <div class="row">
                    <?php
                    // Get latest blog posts
                    $latest_posts = get_posts(array(
                        'numberposts' => 3,
                        'post_status' => 'publish',
                        'orderby' => 'date',
                        'order' => 'DESC'
                    ));
                    
                    if ($latest_posts) :
                        foreach ($latest_posts as $post) : setup_postdata($post);
                    ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <article class="blog-card h-100">
                                <div class="blog-card-image">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium', array('class' => 'img-fluid')); ?>
                                        </a>
                                    <?php else : ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <div class="blog-card-placeholder">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="blog-card-content">
                                    <div class="blog-card-meta mb-2">
                                        <span class="blog-card-date">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            <?php echo get_the_date(); ?>
                                        </span>
                                        <?php if (has_category()) : ?>
                                            <span class="blog-card-category ms-3">
                                                <i class="fas fa-folder me-1"></i>
                                                <?php the_category(', '); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <h3 class="blog-card-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    
                                    <div class="blog-card-excerpt">
                                        <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                                    </div>
                                    
                                    <div class="blog-card-footer">
                                        <a href="<?php the_permalink(); ?>" class="blog-read-more-link">
                                            Read More <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    <?php 
                        endforeach; 
                        wp_reset_postdata();
                    else :
                    ?>
                        <div class="col-12 text-center">
                            <p class="text-muted">No blog posts found.</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($latest_posts) : ?>
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="theme-primary-btn">
                            <?php echo esc_html( get_theme_mod( 'blog_button_text', 'View All Posts' ) ); ?>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </section>

    </main>
<?php
get_footer();