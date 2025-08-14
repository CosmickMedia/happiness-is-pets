<?php
/**
 * The template for displaying the footer
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
                    </div>

                    <div class="col-sm-6 col-lg-4 col-xl-3 contact-section">
                        <h5 class="footer-title">Happiness Is Pets Indianapolis</h5>
                        <ul class="footer-contact-list">
                            <li><a href="tel:317-537-2480">317-537-2480</a></li>
                            <li><a href="mailto:Karenhip7330@gmail.com">Karenhip7330@gmail.com</a></li>
                            <li>5905 E 82nd St,<br>Indianapolis, IN 46250</li>
                        </ul>

                        <h5 class="footer-title mt-4">Happiness Is Pets Schererville</h5>
                        <ul class="footer-contact-list">
                            <li><a href="tel:219-865-3078">219-865-3078</a></li>
                            <li><a href="mailto:Erika.Happinessispets@gmail.com">Erika.Happinessispets@gmail.com</a></li>
                            <li>1525 US 41,<br>Schererville, IN 46375</li>
                        </ul>
                    </div>

                    <div class="col-sm-6 col-lg-4 col-xl-3 hours-section">
                        <h5 class="footer-title">Store Hours</h5>
                        <ul class="footer-hours-list">
                        <?php
                        $hours = get_theme_mod(
                            'footer_col3_hours',
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
                        <h5 class="footer-title">Quick Links</h5>
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
                        <p class="mb-0"><a href="https://www.cosmickmedia.com" target="_blank" rel="noopener">Cosmick Media</a> &amp; Happiness Is Pets Indianapolis &copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?></p>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <ul class="footer-bottom-links">
                            <li><a href="/privacy-policy">Privacy Policy</a></li>
                            <li><a href="/contact/">Contact</a></li>
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
