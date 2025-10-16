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
$header_phone = get_theme_mod( 'header_phone_number' );
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
                    the_post_thumbnail( 'woocommerce_thumbnail', array( 'class' => 'object-fit-cover w-100' ) );
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
                    <h5 class="card-title pet-name fw-bold mb-2"><?php echo esc_html( $pet_name . ' - ' . $ref_id ); ?></h5>

                    <div class="card-text">
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
            <a href="<?php the_permalink(); ?>" class="btn btn-primary-theme w-100 mb-2"><?php esc_html_e( 'View Details', 'happiness-is-pets' ); ?></a>
            <a href="#petDetailsModal-<?php echo esc_attr( $product_id ); ?>" data-bs-toggle="modal" class="btn btn-secondary-theme w-100 mb-2" style="background-color: var(--color-primary-dark-teal) !important; color: var(--color-button-text) !important;">
                <?php esc_html_e( 'Get my Details', 'happiness-is-pets' ); ?>
            </a>
            <?php if ( $header_phone ) : ?>
            <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $header_phone ) ); ?>" class="btn btn-outline-primary-theme w-100"><i class="fas fa-phone me-1"></i> <?php echo esc_html( $header_phone ); ?></a>
            <?php endif; ?>
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
