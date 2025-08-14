<?php
/**
 * The template for displaying the footer
 * Fully integrated with customizer settings
 *
 * @package happiness-is-pets
 */
?>

</div><!-- #content -->

<footer id="footer" class="site-footer cssFooter">
    <div class="container">
        <div class="footer-top">
            <div class="row">
                <div class="col-sm-6 col-lg-2 col-xl-3 logo-section">
                    <?php if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) : ?>
                        <div class="footer-logo">
                            <?php the_custom_logo(); ?>
                        </div>
                    <?php endif; ?>

                    <?php // Social Media Icons under logo ?>
                    <div class="footer-social-links mt-3">
                        <?php if ( $facebook = get_theme_mod( 'social_facebook', '#' ) ) : ?>
                            <a href="<?php echo esc_url( $facebook ); ?>" class="social-link me-2" aria-label="<?php esc_attr_e( 'Facebook', 'happiness-is-pets' ); ?>">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        <?php endif; ?>
                        <?php if ( $instagram = get_theme_mod( 'social_instagram', '#' ) ) : ?>
                            <a href="<?php echo esc_url( $instagram ); ?>" class="social-link me-2" aria-label="<?php esc_attr_e( 'Instagram', 'happiness-is-pets' ); ?>">
                                <i class="fab fa-instagram"></i>
                            </a>
                        <?php endif; ?>
                        <?php if ( $twitter = get_theme_mod( 'social_twitter' ) ) : ?>
                            <a href="<?php echo esc_url( $twitter ); ?>" class="social-link me-2" aria-label="<?php esc_attr_e( 'Twitter', 'happiness-is-pets' ); ?>">
                                <i class="fab fa-twitter"></i>
                            </a>
                        <?php endif; ?>
                        <?php if ( $youtube = get_theme_mod( 'social_youtube' ) ) : ?>
                            <a href="<?php echo esc_url( $youtube ); ?>" class="social-link me-2" aria-label="<?php esc_attr_e( 'YouTube', 'happiness-is-pets' ); ?>">
                                <i class="fab fa-youtube"></i>
                            </a>
                        <?php endif; ?>
                        <?php if ( $tiktok = get_theme_mod( 'social_tiktok' ) ) : ?>
                            <a href="<?php echo esc_url( $tiktok ); ?>" class="social-link" aria-label="<?php esc_attr_e( 'TikTok', 'happiness-is-pets' ); ?>">
                                <i class="fab fa-tiktok"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-4 col-xl-3 contact-section">
                    <?php // Location 1 - Indianapolis ?>
                    <h5 class="footer-title"><?php echo esc_html( get_theme_mod( 'location_1_name', 'Happiness Is Pets Indianapolis' ) ); ?></h5>
                    <ul class="footer-contact-list">
                        <?php if ( $phone1 = get_theme_mod( 'location_1_phone', '317-537-2480' ) ) : ?>
                            <li><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone1 ) ); ?>"><?php echo esc_html( $phone1 ); ?></a></li>
                        <?php endif; ?>
                        <?php if ( $email1 = get_theme_mod( 'location_1_email', 'Karenhip7330@gmail.com' ) ) : ?>
                            <li><a href="mailto:<?php echo esc_attr( $email1 ); ?>"><?php echo esc_html( $email1 ); ?></a></li>
                        <?php endif; ?>
                        <?php if ( $address1 = get_theme_mod( 'location_1_address', "5905 E 82nd St,\nIndianapolis, IN 46250" ) ) : ?>
                            <li><?php echo nl2br( esc_html( $address1 ) ); ?></li>
                        <?php endif; ?>
                    </ul>

                    <?php // Location 2 - Schererville ?>
                    <h5 class="footer-title mt-4"><?php echo esc_html( get_theme_mod( 'location_2_name', 'Happiness Is Pets Schererville' ) ); ?></h5>
                    <ul class="footer-contact-list">
                        <?php if ( $phone2 = get_theme_mod( 'location_2_phone', '219-865-3078' ) ) : ?>
                            <li><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone2 ) ); ?>"><?php echo esc_html( $phone2 ); ?></a></li>
                        <?php endif; ?>
                        <?php if ( $email2 = get_theme_mod( 'location_2_email', 'Erika.Happinessispets@gmail.com' ) ) : ?>
                            <li><a href="mailto:<?php echo esc_attr( $email2 ); ?>"><?php echo esc_html( $email2 ); ?></a></li>
                        <?php endif; ?>
                        <?php if ( $address2 = get_theme_mod( 'location_2_address', "1525 US 41,\nSchererville, IN 46375" ) ) : ?>
                            <li><?php echo nl2br( esc_html( $address2 ) ); ?></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="col-sm-6 col-lg-4 col-xl-3 hours-section">
                    <h5 class="footer-title"><?php esc_html_e( 'Store Hours', 'happiness-is-pets' ); ?></h5>
                    <ul class="footer-hours-list">
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
                            ?>
                            <li><?php echo esc_html( $line ); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="col-sm-6 col-lg-2 col-xl-3 links-section">
                    <h5 class="footer-title"><?php esc_html_e( 'Quick Links', 'happiness-is-pets' ); ?></h5>
                    <?php
                    if ( has_nav_menu( 'footer' ) ) {
                        wp_nav_menu(
                                array(
                                        'theme_location' => 'footer',
                                        'menu_class'     => 'footer-menu',
                                        'container'      => false,
                                        'depth'          => 1,
                                )
                        );
                    }
                    ?>
                </div>
            </div><!-- .row -->
        </div><!-- .footer-top -->
    </div><!-- .container -->

    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <p class="mb-0">
                        <?php if ( $copyright_url = get_theme_mod( 'footer_copyright_url', 'https://www.cosmickmedia.com' ) ) : ?>
                            <a href="<?php echo esc_url( $copyright_url ); ?>" target="_blank" rel="noopener">
                                <?php echo esc_html( get_theme_mod( 'footer_copyright_text', 'Cosmick Media & Happiness Is Pets Indianapolis' ) ); ?>
                            </a>
                        <?php else : ?>
                            <?php echo esc_html( get_theme_mod( 'footer_copyright_text', 'Cosmick Media & Happiness Is Pets Indianapolis' ) ); ?>
                        <?php endif; ?>
                        &copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?>
                    </p>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <ul class="footer-bottom-links">
                        <?php if ( $privacy_url = get_theme_mod( 'footer_privacy_url', '/privacy-policy' ) ) : ?>
                            <li><a href="<?php echo esc_url( $privacy_url ); ?>"><?php esc_html_e( 'Privacy Policy', 'happiness-is-pets' ); ?></a></li>
                        <?php endif; ?>
                        <?php if ( $contact_url = get_theme_mod( 'footer_contact_url', '/contact/' ) ) : ?>
                            <li><a href="<?php echo esc_url( $contact_url ); ?>"><?php esc_html_e( 'Contact', 'happiness-is-pets' ); ?></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>