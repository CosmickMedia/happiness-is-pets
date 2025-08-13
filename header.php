<?php
/**
 * The header for the Dream Tails theme (Two-Bar Structure with Top Offcanvas)
 *
 * Displays all of the <head> section and everything up till <div id="content">
 * Implements a two-bar header:
 * 1) Top bar: Hamburger + Logo left, Contact info and Button right
 * 2) Nav bar: Menu items displayed horizontally on all devices (no hamburger)
 *
 * @package Dream_Tails
 */
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
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'dreamtails' ); ?></a>

    <header id="masthead" class="site-header">

        <?php // --- Top Header Bar with Hamburger Menu --- ?>
        <div class="top-header-bar py-3" style="background-color: var(--color-header-bg);">
            <div class="container d-flex align-items-center">

                <?php // Left side: Hamburger + Logo ?>
                <div class="d-flex align-items-center">
                    <?php // Hamburger Menu Toggle ?>
                    <button
                            class="navbar-toggler dream-tails-toggler me-2 me-md-3 d-md-none"
                            type="button"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#mobileNavOffcanvas"
                            aria-controls="mobileNavOffcanvas"
                            aria-label="<?php esc_attr_e( 'Toggle navigation', 'dreamtails' ); ?>">
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
                        <?php
                        $phone = get_theme_mod( 'header_phone_number', '941-203-1196' );
                        if ( $phone ) : ?>
                            <a class="header-phone-number me-3" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" aria-label="<?php echo esc_attr( $phone ); ?>">
                                <i class="fas fa-phone me-2 header-icon"></i><span class="phone-text d-none d-md-inline"><?php echo esc_html( $phone ); ?></span>
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : '#' ); ?>" class="header-icon header-account-icon me-3" aria-label="<?php esc_attr_e( 'My Account', 'dreamtails' ); ?>">
                            <i class="fas fa-user"></i>
                        </a>
                        <?php if ( function_exists( 'wc_get_cart_url' ) && ! is_catalog_mode() ) : ?>
                            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="header-icon header-cart-icon me-3" aria-label="<?php esc_attr_e( 'Shopping Cart', 'dreamtails' ); ?>">
                                <i class="fas fa-shopping-cart"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

        <?php // --- Offcanvas Menu (Triggered by hamburger in top bar) --- ?>
        <div class="offcanvas offcanvas-start dream-tails-offcanvas" tabindex="-1" id="mobileNavOffcanvas" aria-labelledby="mobileNavOffcanvasLabel">
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
                <button type="button" class="btn-close dream-tails-close" data-bs-dismiss="offcanvas" aria-label="<?php esc_attr_e( 'Close', 'dreamtails' ); ?>"></button>
            </div>
            <div class="offcanvas-body">
                <?php // Full Navigation Menu in Offcanvas ?>
                <nav class="mobile-nav-menu">
                    <?php
                    // You can use a different menu here if needed, like 'primary' instead of 'new-primary'
                    wp_nav_menu(
                            array(
                                    'theme_location' => 'primary', // Using full primary menu in offcanvas
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
                    <h6 class="quick-links-title"><?php esc_html_e( 'Quick Links', 'dreamtails' ); ?></h6>
                    <div class="quick-links-grid">
                        <a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : '#' ); ?>" class="quick-link-item">
                            <i class="fas fa-user"></i>
                            <span><?php esc_html_e( 'My Account', 'dreamtails' ); ?></span>
                        </a>
                        <?php if ( function_exists( 'wc_get_cart_url' ) && ! is_catalog_mode() ) : ?>
                            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="quick-link-item">
                                <i class="fas fa-shopping-cart"></i>
                                <span><?php esc_html_e( 'Cart', 'dreamtails' ); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php // Mobile Contact Info ?>
                <div class="mobile-contact-info">
                    <?php
                    $phone = get_theme_mod( 'header_phone_number' );
                    $address = get_theme_mod( 'footer_col2_address', "6453 Lockwood Ridge Rd\nSarasota, FL 34243" );
                    ?>
                    <?php if ( $phone ) : ?>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
                        </div>
                    <?php endif; ?>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?php echo nl2br( esc_html( $address ) ); ?></span>
                    </div>
                </div>

                <?php // Mobile Social Links ?>
                <div class="mobile-social-links">
                    <a href="<?php echo esc_url( get_theme_mod( 'footer_facebook_url', '#' ) ); ?>" class="social-link" aria-label="<?php esc_attr_e( 'Facebook', 'dreamtails' ); ?>">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="<?php echo esc_url( get_theme_mod( 'footer_instagram_url', '#' ) ); ?>" class="social-link" aria-label="<?php esc_attr_e( 'Instagram', 'dreamtails' ); ?>">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>

                <?php // Mobile CTA Button removed ?>
            </div>
        </div>

    </header>

    <div id="content" class="site-content">