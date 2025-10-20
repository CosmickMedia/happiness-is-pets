<?php
/**
 * Happiness Is Pets functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package happiness-is-pets
 */

if ( ! defined( 'HAPPINESS_IS_PETS_VERSION' ) ) {
    // Replace the version number of the theme on each release.
    define( 'HAPPINESS_IS_PETS_VERSION', '1.4.1' ); // Added placeholder images for products without photos
}

/**
 * Determine if catalog mode is enabled.
 * This allows toggling WooCommerce into a browse-only mode.
 * The customizer saves this as '1' or '0' string, so we need to check for that.
 */
function is_catalog_mode() {
    $catalog_mode = get_theme_mod( 'enable_catalog_mode', '0' );
    // Check for various possible truthy values since customizer saves as string
    return $catalog_mode === '1' || $catalog_mode === 1 || $catalog_mode === true;
}

/**
 * Output breadcrumbs with YoastSEO support.
 */
function happiness_is_pets_breadcrumb() {
    if ( function_exists( 'yoast_breadcrumb' ) ) {
        yoast_breadcrumb( '<nav class="breadcrumb-wrapper mb-3">', '</nav>' );
    } elseif ( function_exists( 'woocommerce_breadcrumb' ) ) {
        woocommerce_breadcrumb();
    }
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function happiness_is_pets_setup() {
    // Make theme available for translation.
    load_theme_textdomain( 'happiness-is-pets', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title.
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support( 'post-thumbnails' );

    // Register navigation menus.
    register_nav_menus(
            array(
                    'primary' => esc_html__( 'Primary Menu', 'happiness-is-pets' ),
                    'footer'  => esc_html__( 'Footer Menu', 'happiness-is-pets' ),
                    'new-primary'  => esc_html__( 'New Primary Menu', 'happiness-is-pets' ),
                    'admin'  => esc_html__( 'Admin', 'happiness-is-pets' ),
            )
    );

    // Switch default core markup for search form, comment form, and comments to output valid HTML5.
    add_theme_support(
            'html5',
            array(
                    'search-form',
                    'comment-form',
                    'comment-list',
                    'gallery',
                    'caption',
                    'style',
                    'script',
            )
    );

    // Add theme support for selective refresh for widgets.
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Add support for core custom logo.
    add_theme_support(
            'custom-logo',
            array(
                    'height'      => 80, // Adjusted example height
                    'width'       => 300, // Adjusted example width
                    'flex-width'  => true,
                    'flex-height' => true,
                    'header-text' => array( 'site-title', 'site-description' ),
            )
    );

    // Add support for Block Styles.
    add_theme_support( 'wp-block-styles' );

    // Add support for full and wide align images.
    add_theme_support( 'align-wide' );

    // Add support for editor styles and load main stylesheet in the editor.
    add_theme_support( 'editor-styles' );
    add_editor_style( 'style.css' );

    // Additional Gutenberg features.
    add_theme_support( 'custom-line-height' );
    add_theme_support( 'custom-spacing' );
    add_theme_support( 'custom-units' );
    add_theme_support( 'editor-font-sizes', array(
            array(
                    'name'      => __( 'Small', 'happiness-is-pets' ),
                    'shortName' => 'S',
                    'size'      => 14,
                    'slug'      => 'small',
            ),
            array(
                    'name'      => __( 'Normal', 'happiness-is-pets' ),
                    'shortName' => 'M',
                    'size'      => 18,
                    'slug'      => 'normal',
            ),
            array(
                    'name'      => __( 'Large', 'happiness-is-pets' ),
                    'shortName' => 'L',
                    'size'      => 24,
                    'slug'      => 'large',
            ),
    ) );
    add_theme_support( 'editor-color-palette', array(
            array(
                    'name'  => __( 'Primary', 'happiness-is-pets' ),
                    'slug'  => 'primary',
                    'color' => '#3D5155',
            ),
            array(
                    'name'  => __( 'Accent', 'happiness-is-pets' ),
                    'slug'  => 'accent',
                    'color' => '#F1E6B2',
            ),
            array(
                    'name'  => __( 'Secondary', 'happiness-is-pets' ),
                    'slug'  => 'secondary',
                    'color' => '#F7BCAC',
            ),
    ) );

    // Add support for responsive embedded content.
    add_theme_support( 'responsive-embeds' );

    // Add WooCommerce Support
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    // Yoast SEO breadcrumbs
    add_theme_support( 'yoast-seo-breadcrumbs' );

    // Add support for site icon (favicon)
    add_theme_support( 'custom-header' );
    add_theme_support( 'site-icon' );

}
add_action( 'after_setup_theme', 'happiness_is_pets_setup' );

/**
 * Add favicon and site icons
 */
function happiness_is_pets_add_favicons() {
    $favicon_url = get_site_icon_url( 32 );

    if ( ! $favicon_url ) {
        // Fallback to custom favicon if site icon not set
        $favicon_url = get_template_directory_uri() . '/assets/images/favicon.ico';
    }

    ?>
    <link rel="icon" type="image/x-icon" href="<?php echo esc_url( $favicon_url ); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( get_site_icon_url( 32 ) ); ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo esc_url( get_site_icon_url( 192 ) ); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url( get_site_icon_url( 180 ) ); ?>">
    <?php
}
add_action( 'wp_head', 'happiness_is_pets_add_favicons', 5 );

/**
 * Step 1 & 2: Add SEO meta tags + Open Graph for WooCommerce category pages
 */
function happiness_is_pets_woocommerce_category_seo() {
    // Only run on WooCommerce shop or category pages
    if ( ! function_exists( 'is_shop' ) || ! function_exists( 'is_product_category' ) ) {
        return;
    }

    if ( ! is_shop() && ! is_product_category() && ! is_product_taxonomy() ) {
        return;
    }

    // Get proper URL based on page type
    if ( is_shop() ) {
        $site_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : get_permalink();
    } elseif ( is_product_category() ) {
        $site_url = get_term_link( get_queried_object() );
        if ( is_wp_error( $site_url ) ) {
            $site_url = get_permalink();
        }
    } else {
        $site_url = get_permalink();
    }
    $site_name = get_bloginfo( 'name' );

    // Get default image (logo or hero image)
    $default_image = get_theme_mod( 'hero_image' );
    if ( ! $default_image ) {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        if ( $custom_logo_id ) {
            $default_image = wp_get_attachment_image_url( $custom_logo_id, 'full' );
        } else {
            $default_image = get_template_directory_uri() . '/assets/images/logo_horizontal.png';
        }
    }

    // Build meta description based on page type
    if ( is_shop() ) {
        $meta_title = 'Available Puppies for Sale | ' . $site_name . ' | Indianapolis & Schererville, IN';
        $meta_description = 'Browse our available puppies for sale at Happiness Is Pets. Healthy, happy puppies from trusted Canine Care Certified breeders in Indianapolis and Schererville, Indiana.';
        $og_image = $default_image;
    } elseif ( is_product_category() ) {
        $category = get_queried_object();
        $category_name = $category->name;
        $meta_title = $category_name . ' Puppies for Sale | ' . $site_name . ' | Indianapolis & Schererville, IN';
        $meta_description = 'Find ' . $category_name . ' puppies for sale at Happiness Is Pets in Indianapolis and Schererville, Indiana. Healthy, well-socialized puppies from Canine Care Certified breeders.';

        // Try to get category thumbnail image
        $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
        if ( $thumbnail_id ) {
            $og_image = wp_get_attachment_url( $thumbnail_id );
        } else {
            $og_image = $default_image;
        }
    } else {
        return; // Exit if not a recognized page type
    }

    ?>
    <!-- WooCommerce Category SEO Meta Tags -->
    <meta name="description" content="<?php echo esc_attr( $meta_description ); ?>">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <link rel="canonical" href="<?php echo esc_url( $site_url ); ?>">

    <!-- Open Graph Meta Tags -->
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo esc_attr( $meta_title ); ?>">
    <meta property="og:description" content="<?php echo esc_attr( $meta_description ); ?>">
    <meta property="og:url" content="<?php echo esc_url( $site_url ); ?>">
    <meta property="og:site_name" content="<?php echo esc_attr( $site_name ); ?>">
    <meta property="og:image" content="<?php echo esc_url( $og_image ); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr( $meta_title ); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr( $meta_description ); ?>">
    <meta name="twitter:image" content="<?php echo esc_url( $og_image ); ?>">

    <!-- Schema.org Structured Data for Product Categories -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CollectionPage",
        "name": "<?php echo esc_js( $meta_title ); ?>",
        "description": "<?php echo esc_js( $meta_description ); ?>",
        "url": "<?php echo esc_url( $site_url ); ?>",
        "isPartOf": {
            "@type": "WebSite",
            "name": "<?php echo esc_js( $site_name ); ?>",
            "url": "<?php echo esc_url( home_url( '/' ) ); ?>"
        },
        "breadcrumb": {
            "@type": "BreadcrumbList",
            "itemListElement": [
                {
                    "@type": "ListItem",
                    "position": 1,
                    "name": "Home",
                    "item": "<?php echo esc_url( home_url( '/' ) ); ?>"
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "name": "<?php echo is_shop() ? 'Shop' : esc_js( $category_name ); ?>",
                    "item": "<?php echo esc_url( $site_url ); ?>"
                }
            ]
        }
    }
    </script>
    <?php
}
add_action( 'wp_head', 'happiness_is_pets_woocommerce_category_seo', 1 );

/**
 * Complete SEO: Meta tags + Open Graph + Schema.org for homepage
 */
function happiness_is_pets_basic_seo() {
    // Only add these on the homepage
    if ( ! is_front_page() ) {
        return;
    }

    $site_name = get_bloginfo( 'name' );
    $site_description = get_bloginfo( 'description' );
    $site_url = home_url( '/' );

    // Get logo
    $logo_url = '';
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    if ( $custom_logo_id ) {
        $logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
    } else {
        $logo_url = get_template_directory_uri() . '/assets/images/logo_horizontal.png';
    }

    // Get hero image for Open Graph
    $hero_image = get_theme_mod( 'hero_image' );
    if ( ! $hero_image ) {
        $hero_image = $logo_url;
    }

    // Get location phone numbers
    $indy_phone = get_theme_mod( 'location_1_phone', '317-537-2480' );
    $schererville_phone = get_theme_mod( 'location_2_phone', '219-865-3078' );

    // Get social media URLs
    $facebook = get_theme_mod( 'social_facebook', '' );
    $instagram = get_theme_mod( 'social_instagram', '' );

    // Natural-sounding meta description (no AI patterns, no dashes)
    $meta_description = 'Find your perfect puppy companion at Happiness Is Pets. We connect loving families with healthy, happy puppies from trusted breeders in Indianapolis and Schererville.';
    $meta_title = $site_name . ' | Puppies for Sale in Indianapolis & Schererville, Indiana';

    ?>
    <!-- Basic SEO Meta Tags -->
    <meta name="description" content="<?php echo esc_attr( $meta_description ); ?>">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <link rel="canonical" href="<?php echo esc_url( $site_url ); ?>">

    <!-- Open Graph Meta Tags -->
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo esc_attr( $meta_title ); ?>">
    <meta property="og:description" content="<?php echo esc_attr( $meta_description ); ?>">
    <meta property="og:url" content="<?php echo esc_url( $site_url ); ?>">
    <meta property="og:site_name" content="<?php echo esc_attr( $site_name ); ?>">
    <meta property="og:image" content="<?php echo esc_url( $hero_image ); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr( $meta_title ); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr( $meta_description ); ?>">
    <meta name="twitter:image" content="<?php echo esc_url( $hero_image ); ?>">

    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "Organization",
                "@id": "<?php echo esc_url( $site_url ); ?>#organization",
                "name": "<?php echo esc_js( $site_name ); ?>",
                "url": "<?php echo esc_url( $site_url ); ?>",
                "logo": {
                    "@type": "ImageObject",
                    "@id": "<?php echo esc_url( $site_url ); ?>#logo",
                    "url": "<?php echo esc_url( $logo_url ); ?>",
                    "contentUrl": "<?php echo esc_url( $logo_url ); ?>",
                    "caption": "<?php echo esc_js( $site_name ); ?>"
                },
                "image": {
                    "@id": "<?php echo esc_url( $site_url ); ?>#logo"
                }<?php if ( $facebook || $instagram ) : ?>,
                "sameAs": [
                    <?php
                    $social_links = array_filter( array( $facebook, $instagram ) );
                    echo '"' . implode( '","', array_map( 'esc_url', $social_links ) ) . '"';
                    ?>
                ]
                <?php endif; ?>
            },
            {
                "@type": "PetStore",
                "@id": "<?php echo esc_url( $site_url ); ?>#petstore",
                "name": "<?php echo esc_js( $site_name ); ?>",
                "description": "<?php echo esc_js( $meta_description ); ?>",
                "url": "<?php echo esc_url( $site_url ); ?>",
                "image": "<?php echo esc_url( $hero_image ); ?>",
                "priceRange": "$$",
                "telephone": "<?php echo esc_js( $indy_phone ); ?>"
            },
            {
                "@type": "LocalBusiness",
                "@id": "<?php echo esc_url( $site_url ); ?>#location-indianapolis",
                "name": "<?php echo esc_js( $site_name ); ?> - Indianapolis",
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "8980 Wesleyan Rd",
                    "addressLocality": "Indianapolis",
                    "addressRegion": "IN",
                    "postalCode": "46268",
                    "addressCountry": "US"
                },
                "telephone": "<?php echo esc_js( $indy_phone ); ?>",
                "url": "<?php echo esc_url( $site_url ); ?>",
                "priceRange": "$$"
            },
            {
                "@type": "LocalBusiness",
                "@id": "<?php echo esc_url( $site_url ); ?>#location-schererville",
                "name": "<?php echo esc_js( $site_name ); ?> - Schererville",
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "1525 US-41",
                    "addressLocality": "Schererville",
                    "addressRegion": "IN",
                    "postalCode": "46375",
                    "addressCountry": "US"
                },
                "telephone": "<?php echo esc_js( $schererville_phone ); ?>",
                "url": "<?php echo esc_url( $site_url ); ?>",
                "priceRange": "$$"
            },
            {
                "@type": "WebSite",
                "@id": "<?php echo esc_url( $site_url ); ?>#website",
                "url": "<?php echo esc_url( $site_url ); ?>",
                "name": "<?php echo esc_js( $site_name ); ?>",
                "description": "<?php echo esc_js( $site_description ); ?>",
                "publisher": {
                    "@id": "<?php echo esc_url( $site_url ); ?>#organization"
                },
                "inLanguage": "en-US"
            },
            {
                "@type": "WebPage",
                "@id": "<?php echo esc_url( $site_url ); ?>#webpage",
                "url": "<?php echo esc_url( $site_url ); ?>",
                "name": "<?php echo esc_js( $meta_title ); ?>",
                "description": "<?php echo esc_js( $meta_description ); ?>",
                "isPartOf": {
                    "@id": "<?php echo esc_url( $site_url ); ?>#website"
                },
                "about": {
                    "@id": "<?php echo esc_url( $site_url ); ?>#organization"
                },
                "primaryImageOfPage": {
                    "@type": "ImageObject",
                    "url": "<?php echo esc_url( $hero_image ); ?>"
                },
                "inLanguage": "en-US"
            }
        ]
    }
    </script>
    <?php
}
add_action( 'wp_head', 'happiness_is_pets_basic_seo', 1 );

/**
 * Enqueue scripts and styles.
 */
function happiness_is_pets_scripts() {
    // Bootstrap CSS (CDN)
    wp_enqueue_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '5.3.3' );

    // Font Awesome CSS (CDN)
    wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css', array(), '6.5.2' );

    // Enqueue main theme stylesheet after Bootstrap
    wp_enqueue_style( 'happiness-is-pets-style', get_stylesheet_uri(), array('bootstrap'), HAPPINESS_IS_PETS_VERSION );

    // Enqueue comment reply script (Essential for threaded comments)
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    // Bootstrap JS Bundle (Includes Popper.js) (CDN) - Load in footer (last arg true)
    wp_enqueue_script( 'bootstrap-bundle', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), '5.3.3', true );

    // Custom JS for theme interactions (e.g., mobile menu if not using Bootstrap's data attributes alone)
    // wp_enqueue_script( 'happiness-is-pets-custom', get_template_directory_uri() . '/assets/js/custom.js', array('jquery', 'bootstrap-bundle'), HAPPINESS_IS_PETS_VERSION, true );

}
add_action( 'wp_enqueue_scripts', 'happiness_is_pets_scripts' );


/**
 * Register widget area.
 */
function happiness_is_pets_widgets_init() {
    // Footer Widget Areas (Using Bootstrap column classes)
    register_sidebar(
            array(
                    'name'          => esc_html__( 'Footer Column 1', 'happiness-is-pets' ),
                    'id'            => 'footer-1',
                    'description'   => esc_html__( 'Add widgets here for the first footer column.', 'happiness-is-pets' ),
                    'before_widget' => '<div id="%1$s" class="widget %2$s">', // Removed default section tag
                    'after_widget'  => '</div>',
                    'before_title'  => '<h5 class="widget-title">', // Use h5 for footer widget titles
                    'after_title'   => '</h5>',
            )
    );
    register_sidebar(
            array(
                    'name'          => esc_html__( 'Footer Column 2 (Info)', 'happiness-is-pets' ),
                    'id'            => 'footer-2',
                    'description'   => esc_html__( 'Add widgets here for the second footer column (e.g., address, hours).', 'happiness-is-pets' ),
                    'before_widget' => '<div id="%1$s" class="widget %2$s footer-info">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<h5 class="widget-title">',
                    'after_title'   => '</h5>',
            )
    );
    register_sidebar(
            array(
                    'name'          => esc_html__( 'Footer Column 3', 'happiness-is-pets' ),
                    'id'            => 'footer-3',
                    'description'   => esc_html__( 'Add widgets here for the third footer column.', 'happiness-is-pets' ),
                    'before_widget' => '<div id="%1$s" class="widget %2$s">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<h5 class="widget-title">',
                    'after_title'   => '</h5>',
            )
    );
    register_sidebar(
            array(
                    'name'          => esc_html__( 'Footer Column 4', 'happiness-is-pets' ),
                    'id'            => 'footer-4',
                    'description'   => esc_html__( 'Add widgets here for the fourth footer column.', 'happiness-is-pets' ),
                    'before_widget' => '<div id="%1$s" class="widget %2$s">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<h5 class="widget-title">',
                    'after_title'   => '</h5>',
            )
    );

    // WooCommerce Sidebar
    register_sidebar( array(
            'name'          => esc_html__( 'Shop Sidebar', 'happiness-is-pets' ),
            'id'            => 'shop-sidebar',
            'description'   => esc_html__( 'Widgets for WooCommerce product archives.', 'happiness-is-pets' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
    ) );

    // Offcanvas filter sidebar for WooCommerce archives
    register_sidebar( array(
            'name'          => esc_html__( 'WooCommerce Filters', 'happiness-is-pets' ),
            'id'            => 'woocommerce-pets',
            'description'   => esc_html__( 'Widgets displayed in the offcanvas filter on shop and category pages.', 'happiness-is-pets' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h5 class="widget-title">',
            'after_title'   => '</h5>',
    ) );
}
add_action( 'widgets_init', 'happiness_is_pets_widgets_init' );

/**
 * Add WooCommerce Wrappers.
 * This ensures WooCommerce content is wrapped with your theme's structure.
 */
// Remove default WooCommerce wrappers
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

// Add theme-specific wrappers
add_action('woocommerce_before_main_content', 'happiness_is_pets_woocommerce_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'happiness_is_pets_woocommerce_wrapper_end', 10);

function happiness_is_pets_woocommerce_wrapper_start() {
    // Container and row for WooCommerce pages
    echo '<main id="primary" class="site-main py-5"><div class="main-container"><div class="row">';
}

function happiness_is_pets_woocommerce_wrapper_end() {
    // Close row and container
    echo '</div></div></main>';
}

/**
 * Catalog Mode adjustments.
 * Hide prices and add-to-cart buttons when enabled.
 */
add_filter( 'woocommerce_is_purchasable', function( $purchasable ) {
    return is_catalog_mode() ? false : $purchasable;
} );

add_filter( 'woocommerce_get_price_html', function( $price ) {
    return is_catalog_mode() ? '' : $price;
} );

add_action( 'wp', function() {
    if ( is_catalog_mode() ) {
        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

        // Also hide cart widget if it exists
        add_filter( 'woocommerce_widget_cart_is_hidden', '__return_true' );

        // Hide cart menu items
        add_filter( 'woocommerce_get_cart_url', '__return_false' );

        // Redirect cart page to shop
        add_action( 'template_redirect', function() {
            if( is_cart() ) {
                wp_redirect( get_permalink( wc_get_page_id( 'shop' ) ) );
                exit;
            }
        });
    }
} );

/**
 * Add Bootstrap classes to navigation menus.
 * Optional: Walker class for more complex menu structures.
 */
// Basic filter to add nav-link class to menu items
function happiness_is_pets_add_menu_link_class( $atts, $item, $args ) {
    if (isset($args->theme_location) && $args->theme_location === 'primary') { // Target only primary menu
        $atts['class'] = 'nav-link'; // Bootstrap 5 nav link class
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'happiness_is_pets_add_menu_link_class', 1, 3 );

// Filter to add nav-item class to menu list items
function happiness_is_pets_add_menu_li_class( $classes, $item, $args ) {
    if (isset($args->theme_location) && $args->theme_location === 'primary') {
        $classes[] = 'nav-item'; // Bootstrap 5 nav item class
    }
    return $classes;
}
add_filter( 'nav_menu_css_class', 'happiness_is_pets_add_menu_li_class', 1, 3 );


// Include template tags file (optional, for helper functions)
// require get_template_directory() . '/inc/template-tags.php';

// Include customizer additions (for theme options)
require get_template_directory() . '/inc/customizer.php';

// If using a Walker Class for Bootstrap Nav:
// require get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php'; // Download and place NavWalker class if needed

/**
 * Register the "reviews" custom post type.
 */
function happiness_is_pets_register_reviews_cpt() {
    $labels = array(
            'name'               => _x( 'Reviews', 'post type general name', 'happiness-is-pets' ),
            'singular_name'      => _x( 'Review', 'post type singular name', 'happiness-is-pets' ),
            'menu_name'          => _x( 'Reviews', 'admin menu', 'happiness-is-pets' ),
            'name_admin_bar'     => _x( 'Review', 'add new on admin bar', 'happiness-is-pets' ),
            'add_new'            => _x( 'Add New', 'review', 'happiness-is-pets' ),
            'add_new_item'       => __( 'Add New Review', 'happiness-is-pets' ),
            'new_item'           => __( 'New Review', 'happiness-is-pets' ),
            'edit_item'          => __( 'Edit Review', 'happiness-is-pets' ),
            'view_item'          => __( 'View Review', 'happiness-is-pets' ),
            'all_items'          => __( 'All Reviews', 'happiness-is-pets' ),
            'search_items'       => __( 'Search Reviews', 'happiness-is-pets' ),
            'parent_item_colon'  => __( 'Parent Reviews:', 'happiness-is-pets' ),
            'not_found'          => __( 'No reviews found.', 'happiness-is-pets' ),
            'not_found_in_trash' => __( 'No reviews found in Trash.', 'happiness-is-pets' )
    );

    $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'review' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-star-filled',
            'supports'           => array( 'title', 'editor', 'thumbnail' ),
    );

    register_post_type( 'reviews', $args );
}
add_action( 'init', 'happiness_is_pets_register_reviews_cpt' );

/**
 * Add meta box for star rating.
 */
function happiness_is_pets_reviews_add_meta_box() {
    add_meta_box(
            'happiness_is_pets_review_rating',
            __( 'Star Rating', 'happiness-is-pets' ),
            'happiness_is_pets_reviews_rating_meta_box_cb',
            'reviews',
            'side',
            'default'
    );
}
add_action( 'add_meta_boxes_reviews', 'happiness_is_pets_reviews_add_meta_box' );

/**
 * Callback to display star rating field.
 */
function happiness_is_pets_reviews_rating_meta_box_cb( $post ) {
    $rating = get_post_meta( $post->ID, '_happiness_is_pets_review_rating', true );
    wp_nonce_field( 'happiness_is_pets_save_review_rating', 'happiness_is_pets_review_rating_nonce' );
    echo '<select name="happiness_is_pets_review_rating" id="happiness_is_pets_review_rating" class="widefat">';
    for ( $i = 1; $i <= 5; $i++ ) {
        printf( '<option value="%1$d" %2$s>%1$d</option>', $i, selected( $rating, $i, false ) );
    }
    echo '</select>';
}

/**
 * Save star rating.
 */
function happiness_is_pets_reviews_save_meta( $post_id ) {
    if ( ! isset( $_POST['happiness_is_pets_review_rating_nonce'] ) || ! wp_verify_nonce( $_POST['happiness_is_pets_review_rating_nonce'], 'happiness_is_pets_save_review_rating' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( isset( $_POST['happiness_is_pets_review_rating'] ) ) {
        update_post_meta( $post_id, '_happiness_is_pets_review_rating', intval( $_POST['happiness_is_pets_review_rating'] ) );
    }
}
add_action( 'save_post_reviews', 'happiness_is_pets_reviews_save_meta' );

/**
 * Add rating column to admin list.
 */
function happiness_is_pets_reviews_columns( $columns ) {
    $columns['review_rating'] = __( 'Rating', 'happiness-is-pets' );
    return $columns;
}
add_filter( 'manage_reviews_posts_columns', 'happiness_is_pets_reviews_columns' );

function happiness_is_pets_reviews_custom_column( $column, $post_id ) {
    if ( 'review_rating' === $column ) {
        $rating = intval( get_post_meta( $post_id, '_happiness_is_pets_review_rating', true ) );
        echo str_repeat( '★', $rating );
    }
}
add_action( 'manage_reviews_posts_custom_column', 'happiness_is_pets_reviews_custom_column', 10, 2 );

// Disable WooCommerce page elements globally
add_action( 'init', function() {

    // 🔹 Disable WooCommerce Page Title
    add_filter( 'woocommerce_show_page_title', '__return_false' );

    // 🔹 Disable Product Result Count (e.g. "Showing 1–9 of 20 results")
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

    // 🔹 Disable Product Sorting Dropdown (e.g. "Default sorting")
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

    // 🔹 Disable Category and Shop Descriptions
    remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
    remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );

    // 🔹 Disable WooCommerce Pagination (for infinite scroll)
    remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );

});

/**
 * Set WooCommerce loop columns to 4 (matches Bootstrap grid)
 */
function happiness_is_pets_loop_columns() {
    return 4;
}
add_filter( 'loop_shop_columns', 'happiness_is_pets_loop_columns', 999 );

/**
 * Set WooCommerce products per page to 20
 */
function happiness_is_pets_products_per_page() {
    return 20;
}
add_filter( 'loop_shop_per_page', 'happiness_is_pets_products_per_page', 20 );

/**
 * Remove WooCommerce column classes that conflict with Bootstrap grid
 * This ensures consistent 4-column layout with row-cols-* classes
 */
function happiness_is_pets_product_class_filter( $classes, $product ) {
    // Remove WooCommerce default column classes
    $classes = array_diff( $classes, array( 'first', 'last' ) );

    // Remove any col-* or column width classes that WooCommerce might add
    $classes = array_filter( $classes, function( $class ) {
        // Remove col-1, col-2, col-3, etc.
        if ( preg_match( '/^col-\d/', $class ) ) {
            return false;
        }
        // Remove column width classes
        if ( strpos( $class, 'columns-' ) === 0 ) {
            return false;
        }
        return true;
    });

    return $classes;
}
add_filter( 'woocommerce_post_class', 'happiness_is_pets_product_class_filter', 999, 2 );


// Testimonial slider
function happiness_is_pets_enqueue_swiper_assets() {
    wp_enqueue_style( 'swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css' );
    wp_enqueue_script( 'swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), null, true );
}
add_action( 'wp_enqueue_scripts', 'happiness_is_pets_enqueue_swiper_assets' );





add_action('admin_footer', 'force_always_show_admin_menus');

function force_always_show_admin_menus() {
    if (!current_user_can('administrator')) return;
    ?>
    <script>
        (function ensureAdminMenusVisible() {
            function addMenuToConfig(id, name) {
                if (
                    window.ukmMenu &&
                    Array.isArray(window.ukmMenu.lastUsedConfig)
                ) {
                    const config = window.ukmMenu.lastUsedConfig;
                    const exists = config.some(item => item.id === id);
                    if (!exists) {
                        config.push({
                            id: id,
                            newName: name
                        });
                    }
                }
            }

            function forceShowMenu(id) {
                const item = document.getElementById(id);
                if (item) {
                    item.style.display = 'block';
                    item.classList.remove('hidden');
                    item.classList.add('wp-has-submenu');
                }
            }

            function overrideSearchFilter() {
                if (!window.ukmMenu || !window.ukmMenu.updateSearch) return;

                const originalSearch = window.ukmMenu.updateSearch;
                window.ukmMenu.updateSearch = function () {
                    originalSearch.apply(this, arguments);
                    forceShowMenu('menu-appearance');
                    forceShowMenu('menu-posts-review');
                    forceShowMenu('menu-plugins');
                };
            }

            function retryUntilReady() {
                if (typeof window.ukmMenu?.applyCustomMenu !== 'function') {
                    return setTimeout(retryUntilReady, 50);
                }

                addMenuToConfig('menu-appearance', 'Appearance');
                addMenuToConfig('menu-posts-review', 'Reviews');
                addMenuToConfig('menu-plugins', 'Plugins');

                overrideSearchFilter();
                forceShowMenu('menu-appearance');
                forceShowMenu('menu-posts-review');
                forceShowMenu('menu-plugins');

                window.ukmMenu.customMenuApplied = false;
                window.ukmMenu.applyCustomMenu();
            }

            retryUntilReady();
        })();
    </script>
    <?php
}


add_action('wp_footer', 'custom_add_jquery_script');
function custom_add_jquery_script() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#post-420').addClass('pet-toggle-cust');
            $('#post-418').addClass('pet-toggle-cust');
        });
        /* Slider script  start*/
        document.addEventListener('DOMContentLoaded', function () {
            new Swiper('.testimonial-swiper', {
                loop: true,
                slidesPerView: 1,
                spaceBetween: 30,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
            });
        });
        /* Slider script  end*/
    </script>

    <?php
}


add_filter('template_include', function($template) {
    if (is_singular('product')) {
        error_log('🔥 Template being used: ' . $template);
    }
    return $template;
});

/* Shortcode for the reviews slider */

function happiness_is_pets_testimonial_slider_shortcode() {
    ob_start();
    ?>
    <section class="front-page-section py-5" id="happy-tails">
        <div class="container">
            <h2 class="section-title text-center mb-5">
                <?php echo esc_html( get_theme_mod( 'front_testimonial_heading', __( 'happy tails start here', 'happiness-is-pets' ) ) ); ?>
            </h2>

            <?php
            $review_query = new WP_Query( array(
                    'post_type'      => 'reviews',
                    'posts_per_page' => -1,
            ) );

            if ( $review_query->have_posts() ) :
                ?>
                <div class="swiper testimonial-swiper">
                    <div class="swiper-wrapper">
                        <?php while ( $review_query->have_posts() ) : $review_query->the_post();
                            $rating = intval( get_post_meta( get_the_ID(), '_happiness_is_pets_review_rating', true ) );
                            ?>
                            <div class="swiper-slide">
                                <div class="text-center testimonial-content px-4">
                                    <i class="fas fa-quote-left fa-2x mb-3" style="color: var(--color-primary-light-peach);"></i>
                                    <blockquote class="blockquote fs-5 fst-italic mb-3"><?php the_content(); ?></blockquote>
                                    <div class="mb-2" style="color: var(--color-primary-light-peach);">
                                        <?php
                                        for ( $i = 1; $i <= 5; $i++ ) {
                                            echo $i <= $rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                        }
                                        ?>
                                    </div>
                                    <footer class="blockquote-footer fw-bold" style="color: var(--color-heading);"><?php the_title(); ?></footer>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <!-- Swiper Navigation -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-pagination"></div>
                </div>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>
        </div>

        <!-- <img class="testimonial-decor" src="<?php //echo esc_url( get_theme_mod( 'front_testimonial_image', get_template_directory_uri() . '/assets/images/reviews-image.png' ) ); ?>" alt="<?php //esc_attr_e( 'Testimonial image', 'happiness-is-pets' ); ?>" /> -->
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode( 'happiness_is_pets_testimonials', 'happiness_is_pets_testimonial_slider_shortcode' );


function dt_featured_pets_shortcode() {
    ob_start(); ?>

    <section class="front-page-section py-5 bg-light" id="featured-pets">
        <div class="container">
            <h2 class="section-title text-center mb-4">
                <?php echo esc_html( get_theme_mod( 'front_featured_pets_heading', __( 'featured dream pets', 'happiness-is-pets' ) ) ); ?>
            </h2>
            <div class="row featured-pets-row align-items-center">
                <div class="col-12 col-md-9">
                    <div class="featured-pets-images d-flex flex-wrap flex-md-nowrap justify-content-between">
                        <?php
                        for ( $i = 1; $i <= 3; $i++ ) {
                            $image = get_theme_mod( 'front_featured_pet_image' . $i, get_template_directory_uri() . '/assets/images/pet-placeholder-' . $i . '.jpg' );
                            $link  = get_theme_mod( 'front_featured_pet_link' . $i, '' );
                            ?>
                            <div class="featured-pet-image mb-3 mb-md-0 text-center">
                                <?php if ( $link ) : ?>
                                <a href="<?php echo esc_url( $link ); ?>">
                                    <?php endif; ?>
                                    <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( sprintf( __( 'Featured Pet %d', 'happiness-is-pets' ), $i ) ); ?>" class="img-fluid">
                                    <?php if ( $link ) : ?>
                                </a>
                            <?php endif; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-12 col-md-3 text-md-start text-center mt-3 mt-md-0 d-flex align-items-center justify-content-center">
                    <a href="/view-pets/" class="view-all-pets-link d-inline-flex align-items-center">
                        <span class="me-2"><?php esc_html_e('View all Dream Pets', 'happiness-is-pets'); ?></span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php
    return ob_get_clean();
}
add_shortcode( 'featured_pets_section', 'dt_featured_pets_shortcode' );

/**
 * Add custom product statuses: B2B, Just-in, Weight Watch.
 */
function wc_ukm_add_custom_product_statuses( $product_statuses ) {

    //B2B
    $product_statuses['b2b'] = array(
        'label'                     => _x( 'B2B', 'Pet Status', 'wc-unified-km' ),
        'public'                    => false,
        'exclude_from_search'       => true,
        'show_in_admin_all_list'    => false,
        'show_in_admin_status_list' => true,
        'post_type'                 => array( 'product' ),
        'label_count'               => _n_noop(
            'B2B <span class="count">(%s)</span>',
            'B2B <span class="count">(%s)</span>',
            'wc-unified-km'
        ),
    );

    //Justin
    $product_statuses['just_in'] = array(
        'label'                     => _x( 'Justin', 'Pet Status', 'wc-unified-km' ),
        'public'                    => false,
        'exclude_from_search'       => true,
        'show_in_admin_all_list'    => false,
        'show_in_admin_status_list' => true,
        'post_type'                 => array( 'product' ),
        'label_count'               => _n_noop(
            'Justin <span class="count">(%s)</span>',
            'Justin <span class="count">(%s)</span>',
            'wc-unified-km'
        ),
    );

    //Weight Watch
    $product_statuses['weight_watch'] = array(
        'label'                     => _x( 'Weight Watch', 'Pet Status', 'wc-unified-km' ),
        'public'                    => false,
        'exclude_from_search'       => true,
        'show_in_admin_all_list'    => false,
        'show_in_admin_status_list' => true,
        'post_type'                 => array( 'product' ),
        'label_count'               => _n_noop(
            'Weight Watch <span class="count">(%s)</span>',
            'Weight Watch <span class="count">(%s)</span>',
            'wc-unified-km'
        ),
    );

    return $product_statuses;
}
add_filter( 'wc_ukm_get_custom_product_statuses', 'wc_ukm_add_custom_product_statuses' );

/**
 * ================================
 * INFINITE SCROLL & LAZY LOADING
 * ================================
 */

// Add infinite scroll JavaScript and AJAX handler
function happiness_is_pets_infinite_scroll_scripts() {
    if ( ! is_shop() && ! is_product_taxonomy() ) {
        return;
    }

    wp_enqueue_script(
        'happiness-infinite-scroll',
        get_template_directory_uri() . '/assets/js/infinite-scroll.js',
        array( 'jquery' ),
        HAPPINESS_IS_PETS_VERSION,
        true
    );

    // Get current page number
    $current_page = max( 1, get_query_var( 'paged' ) );
    $max_pages = $GLOBALS['wp_query']->max_num_pages;

    wp_localize_script(
        'happiness-infinite-scroll',
        'infiniteScrollParams',
        array(
            'ajaxurl'        => admin_url( 'admin-ajax.php' ),
            'current_page'   => $current_page,
            'max_pages'      => $max_pages,
            'loading_text'   => __( 'Loading more puppies...', 'happiness-is-pets' ),
            'no_more_text'   => __( 'No more puppies to show', 'happiness-is-pets' ),
            'error_text'     => __( 'Unable to load more puppies. Please refresh the page.', 'happiness-is-pets' ),
            'placeholder_url'=> wc_placeholder_img_src(),
            'query_vars'     => json_encode( $GLOBALS['wp_query']->query_vars ),
        )
    );
}
add_action( 'wp_enqueue_scripts', 'happiness_is_pets_infinite_scroll_scripts' );

// AJAX handler for loading more products - SIMPLIFIED
function happiness_is_pets_load_more_products() {
    // Get page number
    $paged = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;

    // Get the query vars and decode
    $query_vars = isset( $_POST['query_vars'] ) ? json_decode( stripslashes( $_POST['query_vars'] ), true ) : array();

    // Build simple args
    $args = array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'paged'          => $paged,
        'posts_per_page' => 20,
    );

    // Add category if it exists
    if ( ! empty( $query_vars['product_cat'] ) ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $query_vars['product_cat'],
            ),
        );
    }

    // Add location filter if it exists (from AJAX POST data)
    if ( isset( $_POST['location'] ) && ! empty( $_POST['location'] ) ) {
        $matching_products = happiness_is_pets_get_products_by_location( $_POST['location'] );
        $args['post__in'] = $matching_products;
    }

    // Run the query
    $products = new WP_Query( $args );

    if ( $products->have_posts() ) {
        ob_start();

        while ( $products->have_posts() ) {
            $products->the_post();
            global $product;
            $product = wc_get_product( get_the_ID() );

            if ( $product ) {
                wc_get_template_part( 'content', 'product' );
            }
        }

        $html = ob_get_clean();
        wp_reset_postdata();

        wp_send_json_success( array(
            'html'      => $html,
            'max_pages' => $products->max_num_pages,
            'page'      => $paged,
        ) );
    } else {
        wp_send_json_error( array( 'message' => 'No more products' ) );
    }

    wp_die();
}
add_action( 'wp_ajax_load_more_products', 'happiness_is_pets_load_more_products' );
add_action( 'wp_ajax_nopriv_load_more_products', 'happiness_is_pets_load_more_products' );

/**
 * Add native lazy loading to all product images - DISABLED (was breaking images)
 */
// function happiness_is_pets_add_lazy_loading( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
//     // Add loading="lazy" to product images
//     if ( get_post_type( $post_id ) === 'product' ) {
//         $html = str_replace( '<img', '<img loading="lazy"', $html );
//     }
//     return $html;
// }
// add_filter( 'post_thumbnail_html', 'happiness_is_pets_add_lazy_loading', 10, 5 );

/**
 * ================================
 * PAGESPEED OPTIMIZATIONS
 * ================================
 */

// Defer JavaScript loading for better PageSpeed - DISABLED (was causing JS errors)
// function happiness_is_pets_defer_scripts( $tag, $handle, $src ) {
//     // Don't defer critical scripts
//     $skip_defer = array(
//         'jquery',
//         'jquery-core',
//         'jquery-migrate',
//         'happiness-infinite-scroll',
//         'wp-util',
//         'wp-api',
//         'wp-i18n',
//         'lodash'
//     );
//
//     if ( in_array( $handle, $skip_defer ) || is_admin() ) {
//         return $tag;
//     }
//
//     // Don't defer WooCommerce core scripts
//     if ( strpos( $handle, 'wc-' ) === 0 || strpos( $handle, 'woocommerce' ) !== false ) {
//         return $tag;
//     }
//
//     // Add defer attribute
//     return str_replace( ' src', ' defer src', $tag );
// }
// add_filter( 'script_loader_tag', 'happiness_is_pets_defer_scripts', 10, 3 );

// Preload critical assets - DISABLED for now to prevent CSS conflicts
// function happiness_is_pets_preload_assets() {
//     // Preload Bootstrap CSS
//     echo '<link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
//
//     // Preload Font Awesome
//     echo '<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
//
//     // Fallback for no-JS
//     echo '<noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"></noscript>' . "\n";
//     echo '<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"></noscript>' . "\n";
// }
// add_action( 'wp_head', 'happiness_is_pets_preload_assets', 1 );

// Add DNS prefetch for external resources
function happiness_is_pets_dns_prefetch() {
    echo '<link rel="dns-prefetch" href="//cdn.jsdelivr.net">' . "\n";
    echo '<link rel="dns-prefetch" href="//cdnjs.cloudflare.com">' . "\n";
    echo '<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>' . "\n";
}
add_action( 'wp_head', 'happiness_is_pets_dns_prefetch', 0 );

// Enable WebP support
function happiness_is_pets_webp_support( $mimes ) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter( 'upload_mimes', 'happiness_is_pets_webp_support' );

// Add WebP srcset support
function happiness_is_pets_webp_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
    foreach ( $sources as &$source ) {
        $webp_url = preg_replace( '/\.(jpg|jpeg|png)$/i', '.webp', $source['url'] );

        // Check if WebP version exists
        $upload_dir = wp_upload_dir();
        $webp_path = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $webp_url );

        if ( file_exists( $webp_path ) ) {
            $source['url'] = $webp_url;
        }
    }
    return $sources;
}
add_filter( 'wp_calculate_image_srcset', 'happiness_is_pets_webp_srcset', 10, 5 );

// Optimize images on upload
function happiness_is_pets_optimize_image( $image_data ) {
    if ( ! function_exists( 'wp_get_image_editor' ) ) {
        return $image_data;
    }

    // wp_handle_upload passes an array with 'file', 'url', 'type' keys
    // We need the 'file' path for wp_get_image_editor
    $file_path = is_array( $image_data ) && isset( $image_data['file'] ) ? $image_data['file'] : $image_data;

    // If we still don't have a string path, return early
    if ( ! is_string( $file_path ) ) {
        return $image_data;
    }

    $image = wp_get_image_editor( $file_path );

    if ( is_wp_error( $image ) ) {
        return $image_data;
    }

    // Set quality to 82% (optimal for web)
    $image->set_quality( 82 );
    $image->save( $file_path );

    return $image_data;
}
add_filter( 'wp_handle_upload', 'happiness_is_pets_optimize_image' );

// Remove query strings from static resources
function happiness_is_pets_remove_query_strings( $src ) {
    if ( strpos( $src, '?ver=' ) ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}
add_filter( 'style_loader_src', 'happiness_is_pets_remove_query_strings', 10, 1 );
add_filter( 'script_loader_src', 'happiness_is_pets_remove_query_strings', 10, 1 );

/**
 * ================================
 * LOCATION FILTER WIDGET
 * ================================
 */

// Custom Location Filter Widget
class Happiness_Is_Pets_Location_Filter_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'happiness_location_filter',
            __( 'Pet Location Filter', 'happiness-is-pets' ),
            array( 'description' => __( 'Filter puppies by location (Indianapolis or Schererville)', 'happiness-is-pets' ) )
        );
    }

    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Filter by Location', 'happiness-is-pets' );

        // Get current location from URL
        $current_location = isset( $_GET['location'] ) ? sanitize_text_field( $_GET['location'] ) : '';

        echo $args['before_widget'];
        if ( ! empty( $title ) ) {
            echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
        }
        ?>
        <div class="location-filter-widget">
            <div class="location-options">
                <div class="form-check location-option">
                    <input class="form-check-input location-filter-checkbox" type="checkbox" value="Indianapolis" id="location-indianapolis" data-location="Indianapolis" <?php checked( $current_location, 'Indianapolis' ); ?>>
                    <label class="form-check-label" for="location-indianapolis">
                        <i class="fas fa-map-marker-alt me-2"></i>Indianapolis
                    </label>
                </div>
                <div class="form-check location-option">
                    <input class="form-check-input location-filter-checkbox" type="checkbox" value="Schererville" id="location-schererville" data-location="Schererville" <?php checked( $current_location, 'Schererville' ); ?>>
                    <label class="form-check-label" for="location-schererville">
                        <i class="fas fa-map-marker-alt me-2"></i>Schererville
                    </label>
                </div>
            </div>
            <?php if ( $current_location ) : ?>
            <button type="button" class="btn btn-sm btn-outline-secondary mt-3 w-100 clear-location-filter">
                <i class="fas fa-times me-1"></i><?php esc_html_e( 'Clear Filter', 'happiness-is-pets' ); ?>
            </button>
            <?php endif; ?>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Filter by Location', 'happiness-is-pets' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'happiness-is-pets' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        return $instance;
    }
}

// Register the widget
function happiness_is_pets_register_location_widget() {
    register_widget( 'Happiness_Is_Pets_Location_Filter_Widget' );
}
add_action( 'widgets_init', 'happiness_is_pets_register_location_widget' );

// Enqueue JavaScript for location filter
function happiness_is_pets_location_filter_scripts() {
    if ( ! is_shop() && ! is_product_taxonomy() ) {
        return;
    }

    wp_enqueue_script(
        'happiness-location-filter',
        get_template_directory_uri() . '/assets/js/location-filter.js',
        array( 'jquery' ),
        HAPPINESS_IS_PETS_VERSION,
        true
    );

    wp_localize_script(
        'happiness-location-filter',
        'locationFilterParams',
        array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'location_filter_nonce' ),
        )
    );
}
add_action( 'wp_enqueue_scripts', 'happiness_is_pets_location_filter_scripts' );

// Helper function to get product IDs filtered by location
function happiness_is_pets_get_products_by_location( $location ) {
    if ( empty( $location ) ) {
        return array();
    }

    $location = sanitize_text_field( $location );

    // Get all products
    $all_products = get_posts( array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'post_status' => 'publish',
    ) );

    $matching_products = array();

    foreach ( $all_products as $product_id ) {
        // Get pet data from WC Unified KM
        if ( function_exists( 'wc_ukm_get_pet' ) ) {
            $pet = wc_ukm_get_pet( $product_id );
            if ( $pet && ! empty( $pet->location ) ) {
                // Check if location matches (case-insensitive partial match)
                if ( stripos( $pet->location, $location ) !== false ) {
                    $matching_products[] = $product_id;
                }
            }
        }
    }

    // If no matching products, set to array with 0 so no products show
    if ( empty( $matching_products ) ) {
        $matching_products = array( 0 );
    }

    return $matching_products;
}

// AJAX handler for location filtering - Returns filtered products HTML
function happiness_is_pets_ajax_filter_products_by_location() {
    $location = isset( $_POST['location'] ) ? sanitize_text_field( $_POST['location'] ) : '';
    $category = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : '';

    // Build query args
    $args = array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => -1, // Get all products for the filter
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    // Add category filter if exists
    if ( ! empty( $category ) ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $category,
            ),
        );
    }

    // Add location filter if exists
    if ( ! empty( $location ) ) {
        $matching_products = happiness_is_pets_get_products_by_location( $location );
        $args['post__in'] = $matching_products;
    }

    // Run query
    $products = new WP_Query( $args );

    if ( $products->have_posts() ) {
        ob_start();

        while ( $products->have_posts() ) {
            $products->the_post();
            global $product;
            $product = wc_get_product( get_the_ID() );

            if ( $product ) {
                wc_get_template_part( 'content', 'product' );
            }
        }

        $html = ob_get_clean();
        wp_reset_postdata();

        wp_send_json_success( array(
            'html'  => $html,
            'count' => $products->found_posts,
        ) );
    } else {
        wp_send_json_error( array(
            'message' => 'No products found',
            'html'    => '',
        ) );
    }

    wp_die();
}
add_action( 'wp_ajax_filter_products_by_location', 'happiness_is_pets_ajax_filter_products_by_location' );
add_action( 'wp_ajax_nopriv_filter_products_by_location', 'happiness_is_pets_ajax_filter_products_by_location' );

// Filter products by location using post__in (for initial page load)
function happiness_is_pets_filter_products_by_location( $query ) {
    if ( ! is_admin() && $query->is_main_query() && ( is_shop() || is_product_taxonomy() ) ) {
        if ( isset( $_GET['location'] ) && ! empty( $_GET['location'] ) ) {
            $matching_products = happiness_is_pets_get_products_by_location( $_GET['location'] );
            $query->set( 'post__in', $matching_products );
        }
    }
}
add_action( 'pre_get_posts', 'happiness_is_pets_filter_products_by_location' );

