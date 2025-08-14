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
    define( 'HAPPINESS_IS_PETS_VERSION', '1.1' ); // Updated version
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

}
add_action( 'after_setup_theme', 'happiness_is_pets_setup' );

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

});


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