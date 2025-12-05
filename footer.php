<?php
/**
 * The template for displaying the footer
 * Fully integrated with customizer settings
 *
 * @package happiness-is-pets
 */
?>

</div><!-- #content -->

<footer id="footer" class="site-footer">
    <div class="footer-main py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
        <div class="container">
            <div class="row g-4">
                <!-- Locations Column -->
                <div class="col-lg-4">
                    <h5 class="footer-heading mb-4" style="color: #00c8ba; font-weight: 700; font-size: 1.25rem; border-bottom: 3px solid #00c8ba; padding-bottom: 0.5rem; display: inline-block;">
                        <?php esc_html_e( 'Our Locations', 'happiness-is-pets' ); ?>
                    </h5>

                    <!-- Indianapolis Location -->
                    <div class="location-block mb-4 p-3 rounded-3" style="background: white; border-left: 4px solid #00c8ba;">
                        <!-- Top Row: Location Details and Map -->
                        <div class="d-flex flex-column flex-md-row gap-3 mb-3">
                            <!-- Column 1: Location Details -->
                            <div class="flex-grow-1" style="max-width: 160px">
                                <h6 class="location-name mb-2" style="color: #2d3748; font-weight: 600; font-size: 1.1rem;">
                                    <i class="fas fa-map-marker-alt me-2" style="color: #00c8ba;"></i>Indianapolis
                                </h6>
                                <ul class="list-unstyled mb-0" style="font-size: 0.95rem; color: #6c757d;">
                                    <?php if ( $phone1 = get_theme_mod( 'location_1_phone', '317-537-2480' ) ) : ?>
                                        <li class="mb-1">
                                            <i class="fas fa-phone me-2" style="color: #00c8ba; font-size: 0.85rem;"></i>
                                            <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone1 ) ); ?>" class="text-decoration-none" style="color: #6c757d;">
                                                <?php echo esc_html( $phone1 ); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ( $address1 = get_theme_mod( 'location_1_address', "5905 E 82nd St,\nIndianapolis, IN 46250" ) ) : ?>
                                        <li class="mb-0" style="line-height: 1.6;">
                                            <i class="fas fa-location-dot me-2" style="color: #00c8ba; font-size: 0.85rem;"></i>
                                            <?php echo nl2br( esc_html( $address1 ) ); ?>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <!-- Column 2: Map -->
                            <div class="location-map d-none d-md-block" style="min-width: 130px; max-width: 130px; height: 100px; border: 2px solid #00c8ba; border-radius: 4px; overflow: hidden; flex-shrink: 0; align-self: flex-start;">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3060.641183801884!2d-86.06677990000001!3d39.9046652!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x886b4d524022b1fd%3A0xcaef2e2b07700a5e!2sHappiness%20is%20Pets!5e0!3m2!1sen!2sus!4v1764887494007!5m2!1sen!2sus" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                            <div class="location-map d-block d-md-none" style="width: 100%; height: 150px; border: 2px solid #00c8ba; border-radius: 4px; overflow: hidden;">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3060.641183801884!2d-86.06677990000001!3d39.9046652!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x886b4d524022b1fd%3A0xcaef2e2b07700a5e!2sHappiness%20is%20Pets!5e0!3m2!1sen!2sus!4v1764887494007!5m2!1sen!2sus" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                        <!-- Bottom Row: Social Media (Full Width) -->
                        <div class="location-social d-flex gap-2">
                            <a href="https://www.facebook.com/p/Happiness-is-Pets-Indianapolis-61574225536509/"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="social-icon d-flex align-items-center justify-content-center rounded-circle"
                               style="width: 36px; height: 36px; background: #00c8ba; color: white; text-decoration: none; transition: all 0.3s ease;"
                               aria-label="Indianapolis Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.instagram.com/hipindianapolis/"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="social-icon d-flex align-items-center justify-content-center rounded-circle"
                               style="width: 36px; height: 36px; background: #00c8ba; color: white; text-decoration: none; transition: all 0.3s ease;"
                               aria-label="Indianapolis Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://www.youtube.com/channel/UCbZRIK9Ze0ocj43m82nUybg"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="social-icon d-flex align-items-center justify-content-center rounded-circle"
                               style="width: 36px; height: 36px; background: #00c8ba; color: white; text-decoration: none; transition: all 0.3s ease;"
                               aria-label="Indianapolis YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <a href="https://www.pinterest.com/HappinessispetsIndianapolis/"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="social-icon d-flex align-items-center justify-content-center rounded-circle"
                               style="width: 36px; height: 36px; background: #00c8ba; color: white; text-decoration: none; transition: all 0.3s ease;"
                               aria-label="Indianapolis Pinterest">
                                <i class="fab fa-pinterest"></i>
                            </a>
                            <a href="https://www.threads.com/@hipindianapolis"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="social-icon d-flex align-items-center justify-content-center rounded-circle"
                               style="width: 36px; height: 36px; background: #00c8ba; color: white; text-decoration: none; transition: all 0.3s ease;"
                               aria-label="Indianapolis Threads">
                                <i class="fab fa-threads"></i>
                            </a>
                            <a href="https://www.linkedin.com/company/happiness-is-pets-indianapolis/"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="social-icon d-flex align-items-center justify-content-center rounded-circle"
                               style="width: 36px; height: 36px; background: #00c8ba; color: white; text-decoration: none; transition: all 0.3s ease;"
                               aria-label="Indianapolis Linkedin">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://www.tiktok.com/@hipindy"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="social-icon d-flex align-items-center justify-content-center rounded-circle"
                               style="width: 36px; height: 36px; background: #00c8ba; color: white; text-decoration: none; transition: all 0.3s ease;"
                               aria-label="Indianapolis Tiktok">
                                <i class="fa-brands fa-tiktok"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Schererville Location -->
                    <div class="location-block p-3 rounded-3" style="background: white; border-left: 4px solid #00c8ba;">
                        <!-- Top Row: Location Details and Map -->
                        <div class="d-flex flex-column flex-md-row gap-3 mb-3">
                            <!-- Column 1: Location Details -->
                            <div class="flex-grow-1" style="max-width: 160px">
                                <h6 class="location-name mb-2" style="color: #2d3748; font-weight: 600; font-size: 1.1rem;">
                                    <i class="fas fa-map-marker-alt me-2" style="color: #00c8ba;"></i>Schererville
                                </h6>
                                <ul class="list-unstyled mb-0" style="font-size: 0.95rem; color: #6c757d;">
                                    <?php if ( $phone2 = get_theme_mod( 'location_2_phone', '219-865-3078' ) ) : ?>
                                        <li class="mb-1">
                                            <i class="fas fa-phone me-2" style="color: #00c8ba; font-size: 0.85rem;"></i>
                                            <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone2 ) ); ?>" class="text-decoration-none" style="color: #6c757d;">
                                                <?php echo esc_html( $phone2 ); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ( $address2 = get_theme_mod( 'location_2_address', "1525 US 41,\nSchererville, IN 46375" ) ) : ?>
                                        <li class="mb-0" style="line-height: 1.6;">
                                            <i class="fas fa-location-dot me-2" style="color: #00c8ba; font-size: 0.85rem;"></i>
                                            <?php echo nl2br( esc_html( $address2 ) ); ?>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <!-- Column 2: Map -->
                            <div class="location-map d-none d-md-block" style="min-width: 130px; max-width: 130px; height: 100px; border: 2px solid #00c8ba; border-radius: 4px; overflow: hidden; flex-shrink: 0; align-self: flex-start;">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2988.508641970986!2d-87.4679427!3d41.4932511!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8811e3e42124b0c7%3A0xdef300b0abd103a5!2sHappiness%20is%20Pets!5e0!3m2!1sen!2sus!4v1764887580678!5m2!1sen!2sus" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                            <div class="location-map d-block d-md-none" style="width: 100%; height: 150px; border: 2px solid #00c8ba; border-radius: 4px; overflow: hidden;">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2988.508641970986!2d-87.4679427!3d41.4932511!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8811e3e42124b0c7%3A0xdef300b0abd103a5!2sHappiness%20is%20Pets!5e0!3m2!1sen!2sus!4v1764887580678!5m2!1sen!2sus" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                        <!-- Bottom Row: Social Media (Full Width) -->
                        <div class="location-social d-flex gap-2">
                            <a href="https://www.facebook.com/HIPScher/"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="social-icon d-flex align-items-center justify-content-center rounded-circle"
                               style="width: 36px; height: 36px; background: #00c8ba; color: white; text-decoration: none; transition: all 0.3s ease;"
                               aria-label="Schererville Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.instagram.com/hipschererville/"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="social-icon d-flex align-items-center justify-content-center rounded-circle"
                               style="width: 36px; height: 36px; background: #00c8ba; color: white; text-decoration: none; transition: all 0.3s ease;"
                               aria-label="Schererville Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://www.youtube.com/channel/UCbZRIK9Ze0ocj43m82nUybg"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="social-icon d-flex align-items-center justify-content-center rounded-circle"
                               style="width: 36px; height: 36px; background: #00c8ba; color: white; text-decoration: none; transition: all 0.3s ease;"
                               aria-label="Schererville YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <a href="https://in.pinterest.com/happinessispetsschererville/"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="social-icon d-flex align-items-center justify-content-center rounded-circle"
                               style="width: 36px; height: 36px; background: #00c8ba; color: white; text-decoration: none; transition: all 0.3s ease;"
                               aria-label="Schererville Pinterest">
                                <i class="fab fa-pinterest"></i>
                            </a>
                            <a href="https://www.threads.com/@hipschererville"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="social-icon d-flex align-items-center justify-content-center rounded-circle"
                               style="width: 36px; height: 36px; background: #00c8ba; color: white; text-decoration: none; transition: all 0.3s ease;"
                               aria-label="Schererville Threads">
                                <i class="fab fa-threads"></i>
                            </a>
                            <a href="https://www.linkedin.com/company/happiness-is-pets-schererville/"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="social-icon d-flex align-items-center justify-content-center rounded-circle"
                               style="width: 36px; height: 36px; background: #00c8ba; color: white; text-decoration: none; transition: all 0.3s ease;"
                               aria-label="Schererville Linkedin">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://www.tiktok.com/@happinessispetssch"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="social-icon d-flex align-items-center justify-content-center rounded-circle"
                               style="width: 36px; height: 36px; background: #00c8ba; color: white; text-decoration: none; transition: all 0.3s ease;"
                               aria-label="Schererville Tiktok">
                                <i class="fa-brands fa-tiktok"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Store Hours Column -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-heading mb-4" style="color: #00c8ba; font-weight: 700; font-size: 1.25rem; border-bottom: 3px solid #00c8ba; padding-bottom: 0.5rem; display: inline-block;">
                        <?php esc_html_e( 'Store Hours', 'happiness-is-pets' ); ?>
                    </h5>
                    <ul class="list-unstyled" style="font-size: 0.95rem; color: #495057;">
                        <?php
                        $hours = get_theme_mod(
                            'store_hours',
                            "Monday: 11:00 AM - 09:00 PM\n" .
                            "Tuesday: 11:00 AM - 09:00 PM\n" .
                            "Wednesday: 11:00 AM - 09:00 PM\n" .
                            "Thursday: 11:00 AM - 09:00 PM\n" .
                            "Friday: 11:00 AM - 09:00 PM\n" .
                            "Saturday: 11:00 AM - 06:00 PM\n" .
                            "Sunday: 11:00 AM - 05:00 PM"
                        );

                        foreach ( explode( "\n", $hours ) as $line ) :
                            if ( trim( $line ) ) :
                                $parts = explode( ':', $line, 2 );
                                ?>
                                <li class="mb-2 d-flex justify-content-between">
                                    <strong style="color: #2d3748;"><?php echo esc_html( trim( $parts[0] ) ); ?>:</strong>
                                    <span class="ms-2"><?php echo esc_html( trim( $parts[1] ?? '' ) ); ?></span>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Quick Links Column -->
                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-heading mb-4" style="color: #00c8ba; font-weight: 700; font-size: 1.25rem; border-bottom: 3px solid #00c8ba; padding-bottom: 0.5rem; display: inline-block;">
                        <?php esc_html_e( 'Quick Links', 'happiness-is-pets' ); ?>
                    </h5>
                    <?php
                    if ( has_nav_menu( 'footer' ) ) {
                        wp_nav_menu(
                            array(
                                'theme_location' => 'footer',
                                'menu_class'     => 'list-unstyled footer-links-menu',
                                'container'      => false,
                                'depth'          => 1,
                            )
                        );
                    }
                    ?>
                </div>

                <!-- Logo & Trust Badges Column -->
                <div class="col-lg-3 col-md-12 text-lg-center">
                    <?php if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) : ?>
                        <div class="footer-logo mb-4">
                            <?php the_custom_logo(); ?>
                        </div>
                    <?php endif; ?>
                    <div class="trust-badges">
                        <div class="badge-item mb-2">
                            <i class="fas fa-shield-alt me-2" style="color: #00c8ba;"></i>
                            <span style="font-size: 0.9rem; color: #495057;">2-Year Health Warranty</span>
                        </div>
                        <div class="badge-item mb-2">
                            <i class="fas fa-certificate me-2" style="color: #00c8ba;"></i>
                            <span style="font-size: 0.9rem; color: #495057;">Canine Care Certified</span>
                        </div>
                        <div class="badge-item">
                            <i class="fas fa-heart me-2" style="color: #00c8ba;"></i>
                            <span style="font-size: 0.9rem; color: #495057;">Trusted Breeders</span>
                        </div>
                    </div>
                </div>
            </div><!-- .row -->
        </div><!-- .container -->
    </div><!-- .footer-main -->

    <style>
        .footer-main .social-icon:hover {
            background: #00a89c !important;
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,200,186,0.3);
        }

        .footer-links-menu {
            list-style: none;
            padding: 0;
        }

        .footer-links-menu li {
            margin-bottom: 0.75rem;
        }

        .footer-links-menu a {
            color: #495057;
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .footer-links-menu a:hover {
            color: #00c8ba;
            padding-left: 5px;
        }

        .footer-logo img {
            max-width: 200px;
            height: auto;
        }

        @media (max-width: 991px) {
            .col-lg-3.col-md-12.text-lg-center {
                text-align: center !important;
                margin-top: 2rem;
            }
        }

        .location-map {
            flex-shrink: 0;
        }

        @media (max-width: 767px) {
            .location-map {
                min-width: 100% !important;
                max-width: 100% !important;
                height: 150px !important;
            }
        }
    </style>

    <div class="footer-bottom py-3" style="background: #2d3748; border-top: 3px solid #00c8ba;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                    <p class="mb-0" style="color: #e2e8f0; font-size: 0.9rem;">
                        <?php if ( $copyright_url = get_theme_mod( 'footer_copyright_url', 'https://www.cosmickmedia.com' ) ) : ?>
                            <a href="<?php echo esc_url( $copyright_url ); ?>" target="_blank" rel="noopener" class="text-decoration-none" style="color: #00c8ba; transition: all 0.3s ease;">
                                <?php echo esc_html( get_theme_mod( 'footer_copyright_text', 'Cosmick Media & Happiness Is Pets Indianapolis' ) ); ?>
                            </a>
                        <?php else : ?>
                            <?php echo esc_html( get_theme_mod( 'footer_copyright_text', 'Cosmick Media & Happiness Is Pets Indianapolis' ) ); ?>
                        <?php endif; ?>
                        &copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?>
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <ul class="list-inline mb-0">
                        <?php if ( $privacy_url = get_theme_mod( 'footer_privacy_url', '/privacy-policy' ) ) : ?>
                            <li class="list-inline-item">
                                <a href="<?php echo esc_url( $privacy_url ); ?>" class="text-decoration-none" style="color: #e2e8f0; font-size: 0.9rem; transition: all 0.3s ease;">
                                    <?php esc_html_e( 'Privacy Policy', 'happiness-is-pets' ); ?>
                                </a>
                            </li>
                            <li class="list-inline-item" style="color: #718096;">|</li>
                        <?php endif; ?>
                        <?php if ( $contact_url = get_theme_mod( 'footer_contact_url', '/contact/' ) ) : ?>
                            <li class="list-inline-item">
                                <a href="<?php echo esc_url( $contact_url ); ?>" class="text-decoration-none" style="color: #e2e8f0; font-size: 0.9rem; transition: all 0.3s ease;">
                                    <?php esc_html_e( 'Contact', 'happiness-is-pets' ); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <style>
        .footer-bottom a:hover {
            color: #00c8ba !important;
        }
    </style>
</footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>