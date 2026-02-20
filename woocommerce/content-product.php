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

<div <?php wc_product_class( 'col' ); ?> data-product-id="<?php echo esc_attr( $product_id ); ?>" data-ref-id="<?php echo esc_attr( $ref_id ); ?>">
    <div class="card pet-card shadow-sm border-0 rounded-3 overflow-hidden transition-hover h-100">
        <div class="position-relative">
            <?php if ( $product->get_status() === 'coming_soon' ) :?>
                <div class="position-absolute top-0 end-1 m-2" style="z-index: 10;">
                    <a href="#petDetailsModal"
                       data-bs-toggle="modal"
                       class="onsale badge shadow text-bg-info text-uppercase rounded-pill fs-6 py-2 px-3 shadow-sm pet-details-trigger text-decoration-none"
                       style="background-color: #00c8ba !important; color: #fff !important; cursor: pointer;"
                       data-product-id="<?php echo esc_attr( $product_id ); ?>"
                       data-pet-name="<?php echo esc_attr( $pet_name ); ?>"
                       data-ref-id="<?php echo esc_attr( $ref_id ); ?>"
                       data-breed="<?php echo esc_attr( $first_cat ? $first_cat->name : '' ); ?>"
                       data-gender="<?php echo esc_attr( $gender ); ?>"
                       data-birth-date="<?php echo esc_attr( $birth_date ); ?>"
                       data-location="<?php echo esc_attr( $location ); ?>"
                       data-product-url="<?php echo esc_url( get_permalink() ); ?>">
                        Reserve Now
                    </a>
                </div>
            <?php endif; ?>
            <?php if ( $product->get_status() === 'reserved_puppy' ) :?>
                <div class="position-absolute top-0 end-1 m-2" style="z-index: 10;">
                    <div class="badge shadow text-uppercase rounded-pill fs-6 py-2 px-3 shadow-sm"
                         style="background-color: #fbbf24 !important; color: #78350f !important;">
                        Reserved
                    </div>
                </div>
            <?php endif; ?>
            <?php if ( $product->get_status() !== 'reserved_puppy' ) : ?>
            <a href="<?php the_permalink(); ?>" class="text-decoration-none position-relative" aria-label="<?php echo esc_attr( sprintf( __( 'View details for %s', 'happiness-is-pets' ), get_the_title() ) ); ?>">
                <?php endif; ?>
                <div class="position-relative">
                    <?php
                    if ( has_post_thumbnail() ) {
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
                        // Custom placeholder for products without images
                        ?>
                        <div class="product-image-placeholder d-flex align-items-center justify-content-center bg-light w-100 h-100" style="min-height: 300px;">
                            <div class="text-center">
                                <i class="fas fa-paw fa-4x text-muted mb-3"></i>
                                <p class="text-muted mb-0"><?php esc_html_e( 'Photo Coming Soon', 'happiness-is-pets' ); ?></p>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php if ( $product->get_status() !== 'reserved_puppy' ) : ?>
            </a>
        <?php endif; ?>
        </div>

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

                    <h5 class="card-title pet-name fw-bold mb-2">
                        <?php if ( $product->get_status() !== 'reserved_puppy' ) : ?>
                            <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark"><?php echo esc_html( $pet_name ); ?></a>
                        <?php else : ?>
                            <?php echo esc_html( $pet_name ); ?>
                        <?php endif; ?>
                    </h5>

                    <div class="card-text">
                        <?php if ( $ref_id ) : ?>
                            <div class="pet-detail pet-ref-id d-flex align-items-center mb-1">
                                <strong class="me-1"><?php esc_html_e( 'Ref ID:', 'happiness-is-pets' ); ?></strong><span> <?php echo esc_html( $ref_id ); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ( $breed ) : ?>
                            <div class="pet-detail pet-breed-detail d-flex align-items-center mb-1">
                                <strong class="me-1"><?php esc_html_e( 'Breed:', 'happiness-is-pets' ); ?></strong>
                                <span>
                                <?php if ( $product->get_status() !== 'reserved_puppy' ) : ?>
                                    <a href="<?php the_permalink(); ?>" class="text-decoration-none" style="color: inherit;"><?php echo esc_html( $breed ); ?></a>
                                <?php else : ?>
                                    <?php echo esc_html( $breed ); ?>
                                <?php endif; ?>
                            </span>
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
                    <a href="#petDetailsModal"
                       data-bs-toggle="modal"
                       class="btn btn-secondary-theme w-100 pet-details-trigger"
                       style="background-color: var(--color-primary-dark-teal) !important; color: var(--color-button-text) !important;"
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
