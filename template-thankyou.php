<?php
/**
 * Template Name: Thank You Page
 *
 * A beautiful thank you page for form submissions
 *
 * @package happiness-is-pets
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="thank-you-page">
        <!-- Hero Section -->
        <section class="thank-you-hero text-center py-5">
            <div class="container">
                <div class="thank-you-icon mb-4">
                    <i class="fas fa-heart fa-5x"></i>
                </div>
                <h1 class="thank-you-title display-3 fw-bold mb-4">Thank You!</h1>
                <p class="thank-you-subtitle lead mb-4">
                    We've received your inquiry and we're excited to help you find your new furry family member!
                </p>
                <p class="thank-you-message fs-5 mb-5">
                    A member of our Happiness Is Pets family will be reaching out to you very soon.
                    We can't wait to help you meet your perfect companion!
                </p>

                <!-- CTA Button -->
                <div class="thank-you-cta mb-5">
                    <a href="/puppies-for-sale/" class="btn btn-lg btn-primary-theme px-5 py-3">
                        <i class="fas fa-paw me-2"></i>
                        Continue Browsing Our Puppies
                    </a>
                </div>
            </div>
        </section>

        <!-- What Happens Next Section -->
        <section class="next-steps-section py-5 bg-light">
            <div class="container">
                <h2 class="text-center mb-5">What Happens Next?</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="next-step-card text-center p-4 h-100 bg-white rounded-3 shadow-sm">
                            <div class="step-icon mb-3">
                                <i class="fas fa-envelope-open-text fa-3x"></i>
                            </div>
                            <h3 class="h5 mb-3">1. We Review Your Inquiry</h3>
                            <p>Our team will carefully review your message and gather all the information we need to help you.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="next-step-card text-center p-4 h-100 bg-white rounded-3 shadow-sm">
                            <div class="step-icon mb-3">
                                <i class="fas fa-phone-alt fa-3x"></i>
                            </div>
                            <h3 class="h5 mb-3">2. We'll Contact You</h3>
                            <p>A friendly team member will reach out to answer your questions and schedule a visit if you'd like.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="next-step-card text-center p-4 h-100 bg-white rounded-3 shadow-sm">
                            <div class="step-icon mb-3">
                                <i class="fas fa-heart fa-3x"></i>
                            </div>
                            <h3 class="h5 mb-3">3. Meet Your New Friend</h3>
                            <p>Come visit us and meet the puppy of your dreams! We'll help make the perfect match.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Business Information Section -->
        <section class="business-info-section py-5">
            <div class="container">
                <h2 class="text-center mb-5">Visit Us in Person</h2>
                <div class="row g-4">
                    <!-- Indianapolis Location -->
                    <div class="col-md-6">
                        <div class="location-card p-4 h-100 bg-white rounded-3 shadow">
                            <div class="d-flex align-items-start mb-3">
                                <i class="fas fa-map-marker-alt fa-2x me-3"></i>
                                <div>
                                    <h3 class="h4 mb-3"><?php echo esc_html( get_theme_mod( 'location_1_name', 'Happiness Is Pets Indianapolis' ) ); ?></h3>
                                </div>
                            </div>

                            <div class="location-details">
                                <?php if ( $address1 = get_theme_mod( 'location_1_address', "5905 E 82nd St,\nIndianapolis, IN 46250" ) ) : ?>
                                    <p class="mb-3">
                                        <i class="fas fa-building me-2"></i>
                                        <?php echo nl2br( esc_html( $address1 ) ); ?>
                                    </p>
                                <?php endif; ?>

                                <?php if ( $phone1 = get_theme_mod( 'location_1_phone', '317-537-2480' ) ) : ?>
                                    <p class="mb-3">
                                        <i class="fas fa-phone me-2"></i>
                                        <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone1 ) ); ?>" class="text-decoration-none">
                                            <?php echo esc_html( $phone1 ); ?>
                                        </a>
                                    </p>
                                <?php endif; ?>

                                <?php if ( $email1 = get_theme_mod( 'location_1_email', 'Karenhip7330@gmail.com' ) ) : ?>
                                    <p class="mb-3">
                                        <i class="fas fa-envelope me-2"></i>
                                        <a href="mailto:<?php echo esc_attr( $email1 ); ?>" class="text-decoration-none">
                                            <?php echo esc_html( $email1 ); ?>
                                        </a>
                                    </p>
                                <?php endif; ?>

                                <div class="mt-4">
                                    <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone1 ) ); ?>" class="btn btn-outline-primary-theme w-100">
                                        <i class="fas fa-phone me-2"></i>Call Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schererville Location -->
                    <div class="col-md-6">
                        <div class="location-card p-4 h-100 bg-white rounded-3 shadow">
                            <div class="d-flex align-items-start mb-3">
                                <i class="fas fa-map-marker-alt fa-2x me-3"></i>
                                <div>
                                    <h3 class="h4 mb-3"><?php echo esc_html( get_theme_mod( 'location_2_name', 'Happiness Is Pets Schererville' ) ); ?></h3>
                                </div>
                            </div>

                            <div class="location-details">
                                <?php if ( $address2 = get_theme_mod( 'location_2_address', "1525 US 41,\nSchererville, IN 46375" ) ) : ?>
                                    <p class="mb-3">
                                        <i class="fas fa-building me-2"></i>
                                        <?php echo nl2br( esc_html( $address2 ) ); ?>
                                    </p>
                                <?php endif; ?>

                                <?php if ( $phone2 = get_theme_mod( 'location_2_phone', '219-865-3078' ) ) : ?>
                                    <p class="mb-3">
                                        <i class="fas fa-phone me-2"></i>
                                        <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone2 ) ); ?>" class="text-decoration-none">
                                            <?php echo esc_html( $phone2 ); ?>
                                        </a>
                                    </p>
                                <?php endif; ?>

                                <?php if ( $email2 = get_theme_mod( 'location_2_email', 'Erika.Happinessispets@gmail.com' ) ) : ?>
                                    <p class="mb-3">
                                        <i class="fas fa-envelope me-2"></i>
                                        <a href="mailto:<?php echo esc_attr( $email2 ); ?>" class="text-decoration-none">
                                            <?php echo esc_html( $email2 ); ?>
                                        </a>
                                    </p>
                                <?php endif; ?>

                                <div class="mt-4">
                                    <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone2 ) ); ?>" class="btn btn-outline-primary-theme w-100">
                                        <i class="fas fa-phone me-2"></i>Call Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Store Hours -->
                <div class="store-hours-card mt-4 p-4 bg-white rounded-3 shadow text-center">
                    <h3 class="h4 mb-4">
                        <i class="fas fa-clock me-2"></i>
                        Store Hours
                    </h3>
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
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
                                    <li class="py-1"><?php echo esc_html( $line ); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Social Connection Section -->
        <section class="social-section py-5 bg-light text-center">
            <div class="container">
                <h2 class="mb-4">Stay Connected</h2>
                <p class="lead mb-4">Follow us on social media to see our adorable puppies and happy families!</p>
                <div class="social-links d-flex justify-content-center gap-3">
                    <?php if ( $facebook = get_theme_mod( 'social_facebook', '#' ) ) : ?>
                        <a href="<?php echo esc_url( $facebook ); ?>" class="btn btn-lg btn-outline-primary-theme" aria-label="Facebook">
                            <i class="fab fa-facebook-f fa-2x"></i>
                        </a>
                    <?php endif; ?>
                    <?php if ( $instagram = get_theme_mod( 'social_instagram', '#' ) ) : ?>
                        <a href="<?php echo esc_url( $instagram ); ?>" class="btn btn-lg btn-outline-primary-theme" aria-label="Instagram">
                            <i class="fab fa-instagram fa-2x"></i>
                        </a>
                    <?php endif; ?>
                    <?php if ( $youtube = get_theme_mod( 'social_youtube' ) ) : ?>
                        <a href="<?php echo esc_url( $youtube ); ?>" class="btn btn-lg btn-outline-primary-theme" aria-label="YouTube">
                            <i class="fab fa-youtube fa-2x"></i>
                        </a>
                    <?php endif; ?>
                    <?php if ( $tiktok = get_theme_mod( 'social_tiktok' ) ) : ?>
                        <a href="<?php echo esc_url( $tiktok ); ?>" class="btn btn-lg btn-outline-primary-theme" aria-label="TikTok">
                            <i class="fab fa-tiktok fa-2x"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>
</main>

<style>
#primary.site-main {
    padding-top: 100px !important;
}

.thank-you-page {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.thank-you-hero {
    background: linear-gradient(135deg, var(--color-primary-light-blue-grey) 0%, var(--color-primary-light-peach) 100%);
    padding-top: 100px;
}

.thank-you-icon i {
    color: var(--color-primary-dark-teal);
    animation: heartbeat 1.5s ease-in-out infinite;
}

@keyframes heartbeat {
    0% { transform: scale(1); }
    14% { transform: scale(1.1); }
    28% { transform: scale(1); }
    42% { transform: scale(1.1); }
    70% { transform: scale(1); }
}

.thank-you-title {
    color: var(--color-primary-dark-teal);
    font-weight: 700;
}

.thank-you-subtitle {
    color: var(--color-primary-dark-grey);
    font-weight: 500;
}

.thank-you-message {
    color: var(--color-primary-dark-grey);
    max-width: 800px;
    margin: 0 auto;
}

.btn-primary-theme {
    background-color: var(--color-primary-dark-teal);
    border-color: var(--color-primary-dark-teal);
    color: #fff;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary-theme:hover {
    background-color: #2d3f43;
    border-color: #2d3f43;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(61, 81, 85, 0.3);
}

.btn-outline-primary-theme {
    color: var(--color-primary-dark-teal);
    border-color: var(--color-primary-dark-teal);
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-primary-theme:hover {
    background-color: var(--color-primary-dark-teal);
    border-color: var(--color-primary-dark-teal);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(61, 81, 85, 0.3);
}

.next-step-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.next-step-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
}

.step-icon i {
    color: var(--color-primary-dark-teal);
}

.next-step-card h3 {
    color: var(--color-primary-dark-teal);
    font-weight: 600;
}

.location-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.location-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
}

.location-card h3 {
    color: var(--color-primary-dark-teal);
    font-weight: 600;
}

.location-card i {
    color: var(--color-primary-dark-teal);
}

.location-details a {
    color: var(--color-primary-dark-grey);
    transition: color 0.3s ease;
}

.location-details a:hover {
    color: var(--color-primary-dark-teal);
}

.store-hours-card {
    background: linear-gradient(135deg, #fff 0%, var(--color-primary-light-peach) 100%);
}

.store-hours-card h3 {
    color: var(--color-primary-dark-teal);
}

.store-hours-card i {
    color: var(--color-primary-dark-teal);
}

h2 {
    color: var(--color-primary-dark-teal);
    font-weight: 700;
}

.social-section h2,
.social-section p {
    color: var(--color-primary-dark-teal);
}

.social-links .btn {
    width: 70px;
    height: 70px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.social-links .btn:hover {
    transform: scale(1.1) rotate(5deg);
}
</style>

<?php
get_footer();
