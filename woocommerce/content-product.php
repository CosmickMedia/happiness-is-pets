<?php
defined( 'ABSPATH' ) || exit;

global $product;

$product_id = $product->get_id();
// $gender     = $product->get_attribute( 'gender' );
$sku        = $product->get_sku();
$categories = get_the_terms( $product_id, 'product_cat' );
$first_cat  = $categories && ! is_wp_error( $categories ) ? array_shift( $categories ) : false;
$gender = get_field('gender');
// echo $gender;
// exit;
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
$location_phone = get_theme_mod('header_phone_number', '317-537-2480'); // Default fallback to Indianapolis
if (!empty($location)) {
    $location_lower = strtolower(trim($location));
    if (strpos($location_lower, 'indianapolis') !== false) {
        $location_phone = get_theme_mod('location_1_phone', '317-537-2480');
    } elseif (strpos($location_lower, 'schererville') !== false) {
        $location_phone = get_theme_mod('location_2_phone', '219-865-3078');
    }
}

$reservation_url = get_theme_mod( 'header_book_button_url', '#' );
?>
<div <?php wc_product_class( 'col' ); ?> >
    <div class="card pet-card shadow-sm border-0 rounded-3 overflow-hidden transition-hover h-100">
        <a href="<?php the_permalink(); ?>" class="text-decoration-none position-relative" aria-label="<?php echo esc_attr( sprintf( __( 'View details for %s', 'happiness-is-pets' ), get_the_title() ) ); ?>">
            <div class="position-relative">
                <?php if ( ! is_catalog_mode() ) : ?>
                <!--div class="position-absolute top-0 end-0 m-2 z-index-1">
                    <div class="product-price-tag badge bg-gold rounded-pill fs-6 py-2 px-3 shadow-sm">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                </div--> 
                <?php endif; ?>
                <?php
                if ( has_post_thumbnail() ) {
                    // Force immediate loading without lazy loading attribute
                    $image_id = get_post_thumbnail_id();
                    $image_url = wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' );
                    $image_srcset = wp_get_attachment_image_srcset( $image_id, 'woocommerce_thumbnail' );
                    $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );

                    printf(
                        '<img src="%s" srcset="%s" sizes="(max-width: 300px) 100vw, 300px" alt="%s" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail object-fit-cover w-100" decoding="async">',
                        esc_url( $image_url ),
                        esc_attr( $image_srcset ),
                        esc_attr( $image_alt ?: get_the_title() )
                    );
                } else {
                    echo wc_placeholder_img( 'woocommerce_thumbnail' );
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

                    <!-- <h5 class="card-title pet-name fw-bold mb-2"><?php //echo sprintf( esc_html__( 'Hi, my name is %s!', 'happiness-is-pets' ), get_the_title() ); ?></h5> -->


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

                <!-- <div class="col-auto d-flex flex-column align-items-stretch justify-content-center gap-2">
                    <a href="tel:" class="btn btn-light btn-sm d-flex flex-column align-items-center gap-1 z-2 action-icon" title="<?php //esc_attr_e( 'Call Us', 'happiness-is-pets' ); ?>">
                        <i class="fas fa-phone-alt"></i>
                        <small><?php //esc_html_e( 'Call', 'happiness-is-pets' ); ?></small>
                    </a>

                    <a href="mailto:<?php //echo antispambot( get_option( 'admin_email' ) ); ?>" class="btn btn-dark btn-sm d-flex flex-column align-items-center gap-1 z-2 action-icon" title="<?php //esc_attr_e( 'Email Us', 'happiness-is-pets' ); ?>">
                        <i class="fas fa-envelope"></i>
                        <small><?php //esc_html_e( 'Email', 'happiness-is-pets' ); ?></small>
                    </a>
                </div> -->
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
                       data-product-url="<?php echo esc_url( get_permalink() ); ?>">
                        <i class="fas fa-paper-plane me-md-1"></i><span class="d-none d-md-inline"><?php esc_html_e( 'Contact', 'happiness-is-pets' ); ?></span>
                    </a>
                </div>
            </div>
            <a href="<?php the_permalink(); ?>" class="btn btn-primary-theme w-100"><?php esc_html_e( 'Learn More', 'happiness-is-pets' ); ?></a>
        </div>
    </div>
</div>

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

<script>
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
