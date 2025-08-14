<?php
/**
 * The header for the Happiness Is Pets theme
 * Fully integrated with customizer settings
 *
 * @package happiness-is-pets
 */

// Get all available locations for the phone dropdown
$locations = happiness_is_pets_get_locations();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php
    // Default image meta tags for SEO/social sharing.
    $seo_image = '';

    if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail() ) {
        $seo_image = wp_get_attachment_image_url( get_post_thumbnail_id(), 'full' );
    }

    if ( ! $seo_image ) {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        if ( $custom_logo_id ) {
            $seo_image = wp_get_attachment_image_url( $custom_logo_id, 'full' );
        } else {
            $seo_image = get_template_directory_uri() . '/assets/images/logo_horizontal.png';
        }
    }

    $seo_image_alt = get_bloginfo( 'name' );

    if ( $seo_image ) :
        ?>
        <meta property="og:image" content="<?php echo esc_url( $seo_image ); ?>">
        <meta name="twitter:image" content="<?php echo esc_url( $seo_image ); ?>">
        <meta property="og:image:alt" content="<?php echo esc_attr( $seo_image_alt ); ?>">
        <meta name="twitter:image:alt" content="<?php echo esc_attr( $seo_image_alt ); ?>">
    <?php endif; ?>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'happiness-is-pets' ); ?></a>

    <header id="masthead" class="site-header cssHeader">

        <?php // --- Top Header Bar with Hamburger Menu --- ?>
        <div class="top-header-bar py-3">
            <div class="container d-flex align-items-center">

                <?php // Left side: Hamburger + Logo ?>
                <div class="d-flex align-items-center">
                    <?php // Hamburger Menu Toggle ?>
                    <button
                            class="navbar-toggler happiness-is-pets-toggler me-2 me-md-3 d-md-none mobile-menu-toggle"
                            type="button"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#mobileNavOffcanvas"
                            aria-controls="mobileNavOffcanvas"
                            aria-label="<?php esc_attr_e( 'Toggle navigation', 'happiness-is-pets' ); ?>">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <?php // Site Branding Logo ?>
                    <div class="site-branding">
                        <div class="top-bar-logo">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                                <?php
                                if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
                                    the_custom_logo();
                                } else {
                                    $logo_url = get_template_directory_uri() . '/assets/images/logo_horizontal.png';
                                    ?>
                                    <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php bloginfo( 'name' ); ?> Logo">
                                <?php } ?>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="navbar-horizontal-menu d-none d-md-flex flex-grow-1 justify-content-center">
                    <?php
                    wp_nav_menu(
                            array(
                                    'theme_location' => 'primary',
                                    'menu_class'     => 'navbar-nav-horizontal',
                                    'container'      => false,
                                    'fallback_cb'    => false,
                                    'depth'          => 1,
                            )
                    );
                    ?>
                </div>

                <?php // Right side: Contact info and CTA button ?>
                <div class="header-top-button ms-auto">
                    <div class="header-contact d-flex align-items-center justify-content-end">
                        <?php if ( ! empty( $locations ) ) : ?>
                            <div class="dropdown">
                                <a class="header-icon header-phone-icon me-3" href="#" role="button" id="headerPhoneDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-phone"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="headerPhoneDropdown">
                                    <?php foreach ( $locations as $loc ) :
                                        if ( ! empty( $loc['phone'] ) ) :
                                            $tel = preg_replace( '/[^0-9+]/', '', $loc['phone'] );
                                            ?>
                                            <li><a class="dropdown-item" href="tel:<?php echo esc_attr( $tel ); ?>"><?php echo esc_html( $loc['name'] ); ?></a></li>
                                        <?php endif;
                                    endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : '#' ); ?>" class="header-icon header-account-icon me-3" aria-label="<?php esc_attr_e( 'My Account', 'happiness-is-pets' ); ?>">
                            <i class="fas fa-user"></i>
                        </a>
                        <?php if ( function_exists( 'wc_get_cart_url' ) && ! is_catalog_mode() ) : ?>
                            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="header-icon header-cart-icon me-3" aria-label="<?php esc_attr_e( 'Shopping Cart', 'happiness-is-pets' ); ?>">
                                <i class="fas fa-shopping-cart"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

        <?php // --- Offcanvas Menu (Triggered by hamburger in top bar) --- ?>
        <div class="offcanvas offcanvas-start happiness-is-pets-offcanvas" tabindex="-1" id="mobileNavOffcanvas" aria-labelledby="mobileNavOffcanvasLabel">
            <div class="offcanvas-header">
                <div class="offcanvas-logo">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                        <?php
                        if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
                            the_custom_logo();
                        } else {
                            ?>
                            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo_horizontal.png' ); ?>" alt="<?php bloginfo( 'name' ); ?>">
                        <?php } ?>
                    </a>
                </div>
                <button type="button" class="btn-close happiness-is-pets-close" data-bs-dismiss="offcanvas" aria-label="<?php esc_attr_e( 'Close', 'happiness-is-pets' ); ?>"></button>
            </div>
            <div class="offcanvas-body">
                <?php // Full Navigation Menu in Offcanvas ?>
                <nav class="mobile-nav-menu">
                    <?php
                    wp_nav_menu(
                            array(
                                    'theme_location' => 'primary',
                                    'menu_class'     => 'mobile-menu-list',
                                    'container'      => false,
                                    'fallback_cb'    => false,
                                    'depth'          => 2,
                            )
                    );
                    ?>
                </nav>

                <?php // Mobile Quick Links ?>
                <div class="mobile-quick-links">
                    <h6 class="quick-links-title"><?php esc_html_e( 'Quick Links', 'happiness-is-pets' ); ?></h6>
                    <div class="quick-links-grid">
                        <a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : '#' ); ?>" class="quick-link-item">
                            <i class="fas fa-user"></i>
                            <span><?php esc_html_e( 'My Account', 'happiness-is-pets' ); ?></span>
                        </a>
                        <?php if ( function_exists( 'wc_get_cart_url' ) && ! is_catalog_mode() ) : ?>
                            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="quick-link-item">
                                <i class="fas fa-shopping-cart"></i>
                                <span><?php esc_html_e( 'Cart', 'happiness-is-pets' ); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php // Mobile Contact Info ?>
                <div class="mobile-contact-info">
                    <?php foreach ( $locations as $loc ) : ?>
                        <div class="contact-item">
                            <strong><?php echo esc_html( $loc['name'] ); ?></strong>
                        </div>
                        <?php if ( ! empty( $loc['phone'] ) ) : ?>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $loc['phone'] ) ); ?>"><?php echo esc_html( $loc['phone'] ); ?></a>
                            </div>
                        <?php endif; ?>
                        <?php if ( ! empty( $loc['address'] ) ) : ?>
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?php echo nl2br( esc_html( $loc['address'] ) ); ?></span>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <?php // Mobile Social Links ?>
                <div class="mobile-social-links">
                    <?php if ( $facebook = get_theme_mod( 'social_facebook', '#' ) ) : ?>
                        <a href="<?php echo esc_url( $facebook ); ?>" class="social-link" aria-label="<?php esc_attr_e( 'Facebook', 'happiness-is-pets' ); ?>">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    <?php endif; ?>
                    <?php if ( $instagram = get_theme_mod( 'social_instagram', '#' ) ) : ?>
                        <a href="<?php echo esc_url( $instagram ); ?>" class="social-link" aria-label="<?php esc_attr_e( 'Instagram', 'happiness-is-pets' ); ?>">
                            <i class="fab fa-instagram"></i>
                        </a>
                    <?php endif; ?>
                    <?php if ( $twitter = get_theme_mod( 'social_twitter' ) ) : ?>
                        <a href="<?php echo esc_url( $twitter ); ?>" class="social-link" aria-label="<?php esc_attr_e( 'Twitter', 'happiness-is-pets' ); ?>">
                            <i class="fab fa-twitter"></i>
                        </a>
                    <?php endif; ?>
                    <?php if ( $youtube = get_theme_mod( 'social_youtube' ) ) : ?>
                        <a href="<?php echo esc_url( $youtube ); ?>" class="social-link" aria-label="<?php esc_attr_e( 'YouTube', 'happiness-is-pets' ); ?>">
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
        </div>

    </header>

    <div id="content" class="site-content">