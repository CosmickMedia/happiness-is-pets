<?php
/**
 * Theme Customizer additions for Dream Tails.
 */

function dreamtails_customize_register( $wp_customize ) {
    // Panel for front page settings
    $wp_customize->add_panel( 'dreamtails_front_page', array(
            'title'    => __( 'Front Page', 'dreamtails' ),
            'priority' => 160,
    ) );

    /* Hero Section */
    $wp_customize->add_section( 'dreamtails_hero', array(
            'title' => __( 'Hero Section', 'dreamtails' ),
            'panel' => 'dreamtails_front_page',
    ) );

    $wp_customize->add_setting( 'front_hero_image', array(
            'default'           => get_template_directory_uri() . '/assets/images/homepage_hero.png',
            'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'front_hero_image', array(
            'label'   => __( 'Hero Image', 'dreamtails' ),
            'section' => 'dreamtails_hero',
    ) ) );

    $wp_customize->add_setting( 'front_hero_heading', array(
            'default'           => __( 'where pets find their people', 'dreamtails' ),
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'front_hero_heading', array(
            'label'   => __( 'Hero Heading', 'dreamtails' ),
            'section' => 'dreamtails_hero',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'front_hero_button_text', array(
            'default'           => __( 'Book an Appointment', 'dreamtails' ),
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'front_hero_button_text', array(
            'label'   => __( 'Hero Button Text', 'dreamtails' ),
            'section' => 'dreamtails_hero',
            'type'    => 'text',
    ) );

    // Background options for Hero Section
    $wp_customize->add_setting( 'front_hero_bg_image', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'front_hero_bg_image', array(
            'label'   => __( 'Hero Background Image', 'dreamtails' ),
            'section' => 'dreamtails_hero',
    ) ) );

    $wp_customize->add_setting( 'front_hero_bg_color', array(
            'default'           => '#ffcfcd',
            'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'front_hero_bg_color', array(
            'label'   => __( 'Hero Background Color', 'dreamtails' ),
            'section' => 'dreamtails_hero',
    ) ) );

    /* Header Settings */
    $wp_customize->add_section( 'dreamtails_header_settings', array(
            'title'    => __( 'Header Settings', 'dreamtails' ),
            'priority' => 30,
    ) );

    $wp_customize->add_setting( 'header_phone_number', array(
            'default'           => '941-203-1196',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'header_phone_number', array(
            'label'   => __( 'Phone Number', 'dreamtails' ),
            'section' => 'dreamtails_header_settings',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'header_book_button_url', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'header_book_button_url', array(
            'label'   => __( 'Reservation Button URL', 'dreamtails' ),
            'section' => 'dreamtails_header_settings',
            'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'header_book_button_text', array(
            'default'           => __( 'Make a Reservation', 'dreamtails' ),
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'header_book_button_text', array(
            'label'   => __( 'Reservation Button Text', 'dreamtails' ),
            'section' => 'dreamtails_header_settings',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'header_book_button_icon', array(
            'default'           => 'fa-regular fa-calendar-check',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'header_book_button_icon', array(
            'label'       => __( 'Reservation Button Icon', 'dreamtails' ),
            'section'     => 'dreamtails_header_settings',
            'type'        => 'text',
            'description' => __( 'Font Awesome class, e.g. fa-calendar-check', 'dreamtails' ),
    ) );

    /* Shop Settings */
    $wp_customize->add_section( 'dreamtails_shop_settings', array(
            'title' => __( 'Shop Settings', 'dreamtails' ),
            'panel' => 'dreamtails_front_page',
    ) );

    // FIXED: Changed to boolean handling for checkbox
    $wp_customize->add_setting( 'enable_catalog_mode', array(
            'default'           => false,
            'sanitize_callback' => function( $value ) {
                return (bool) $value;
            },
    ) );

    $wp_customize->add_control( 'enable_catalog_mode', array(
            'label'   => __( 'Enable Catalog Mode', 'dreamtails' ),
            'section' => 'dreamtails_shop_settings',
            'type'    => 'checkbox',
    ) );

    /* Icon Section */
    $wp_customize->add_section( 'dreamtails_icons', array(
            'title' => __( 'Icon Section', 'dreamtails' ),
            'panel' => 'dreamtails_front_page',
    ) );

    $wp_customize->add_setting( 'front_icon1_img', array(
            'default'           => get_template_directory_uri() . '/assets/images/puppy_ico.png',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'front_icon1_img', array(
            'label'   => __( 'First Icon Image', 'dreamtails' ),
            'section' => 'dreamtails_icons',
    ) ) );

    $wp_customize->add_setting( 'front_icon1_link', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'front_icon1_link', array(
            'label'   => __( 'First Icon Link', 'dreamtails' ),
            'section' => 'dreamtails_icons',
            'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'front_icon1_text', array(
            'default'           => __( 'puppies dreaming of you', 'dreamtails' ),
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'front_icon1_text', array(
            'label'   => __( 'First Icon Text', 'dreamtails' ),
            'section' => 'dreamtails_icons',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'front_icon2_img', array(
            'default'           => get_template_directory_uri() . '/assets/images/kittens_ico.png',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'front_icon2_img', array(
            'label'   => __( 'Second Icon Image', 'dreamtails' ),
            'section' => 'dreamtails_icons',
    ) ) );

    $wp_customize->add_setting( 'front_icon2_link', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'front_icon2_link', array(
            'label'   => __( 'Second Icon Link', 'dreamtails' ),
            'section' => 'dreamtails_icons',
            'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'front_icon2_text', array(
            'default'           => __( 'kittens dreaming of you', 'dreamtails' ),
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'front_icon2_text', array(
            'label'   => __( 'Second Icon Text', 'dreamtails' ),
            'section' => 'dreamtails_icons',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'front_icon3_img', array(
            'default'           => get_template_directory_uri() . '/assets/images/concierge.png',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'front_icon3_img', array(
            'label'   => __( 'Third Icon Image', 'dreamtails' ),
            'section' => 'dreamtails_icons',
    ) ) );

    $wp_customize->add_setting( 'front_icon3_link', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'front_icon3_link', array(
            'label'   => __( 'Third Icon Link', 'dreamtails' ),
            'section' => 'dreamtails_icons',
            'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'front_icon3_text', array(
            'default'           => __( 'concierge service', 'dreamtails' ),
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'front_icon3_text', array(
            'label'   => __( 'Third Icon Text', 'dreamtails' ),
            'section' => 'dreamtails_icons',
            'type'    => 'text',
    ) );

    /* Featured Pets */
    $wp_customize->add_section( 'dreamtails_featured_pets', array(
            'title' => __( 'Featured Pets', 'dreamtails' ),
            'panel' => 'dreamtails_front_page',
    ) );

    $wp_customize->add_setting( 'front_featured_pets_heading', array(
            'default'           => __( 'featured dream pets', 'dreamtails' ),
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'front_featured_pets_heading', array(
            'label'   => __( 'Section Heading', 'dreamtails' ),
            'section' => 'dreamtails_featured_pets',
            'type'    => 'text',
    ) );

    // Featured Pet Images
    for ( $i = 1; $i <= 3; $i++ ) {
        $wp_customize->add_setting( "front_featured_pet_image{$i}", array(
                'default'           => get_template_directory_uri() . "/assets/images/pet-placeholder-{$i}.jpg",
                'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, "front_featured_pet_image{$i}", array(
                'label'   => sprintf( __( 'Featured Pet Image %d', 'dreamtails' ), $i ),
                'section' => 'dreamtails_featured_pets',
        ) ) );

        $wp_customize->add_setting( "front_featured_pet_link{$i}", array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( "front_featured_pet_link{$i}", array(
                'label'   => sprintf( __( 'Featured Pet Link %d', 'dreamtails' ), $i ),
                'section' => 'dreamtails_featured_pets',
                'type'    => 'url',
        ) );
    }

    /* Testimonials */
    $wp_customize->add_section( 'dreamtails_testimonials', array(
            'title' => __( 'Testimonials', 'dreamtails' ),
            'panel' => 'dreamtails_front_page',
    ) );

    $wp_customize->add_setting( 'front_testimonial_heading', array(
            'default'           => __( 'happy tails start here', 'dreamtails' ),
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'front_testimonial_heading', array(
            'label'   => __( 'Section Heading', 'dreamtails' ),
            'section' => 'dreamtails_testimonials',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'front_testimonial_image', array(
            'default'           => get_template_directory_uri() . '/assets/images/reviews-image.png',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'front_testimonial_image', array(
            'label'   => __( 'Testimonial Image', 'dreamtails' ),
            'section' => 'dreamtails_testimonials',
    ) ) );

    // Background options for Testimonials Section
    $wp_customize->add_setting( 'front_testimonial_bg_image', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'front_testimonial_bg_image', array(
            'label'   => __( 'Testimonials Background Image', 'dreamtails' ),
            'section' => 'dreamtails_testimonials',
    ) ) );

    $wp_customize->add_setting( 'front_testimonial_bg_color', array(
            'default'           => '#ffcfcd',
            'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'front_testimonial_bg_color', array(
            'label'   => __( 'Testimonials Background Color', 'dreamtails' ),
            'section' => 'dreamtails_testimonials',
    ) ) );

    /* Concierge Section */
    $wp_customize->add_section( 'dreamtails_concierge', array(
            'title' => __( 'Concierge Section', 'dreamtails' ),
            'panel' => 'dreamtails_front_page',
    ) );

    $wp_customize->add_setting( 'front_concierge_heading', array(
            'default'           => __( 'concierge level care', 'dreamtails' ),
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'front_concierge_heading', array(
            'label'   => __( 'Section Heading', 'dreamtails' ),
            'section' => 'dreamtails_concierge',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'front_concierge_lead', array(
            'default'           => __( 'Our service and environment are designed to match the high quality of puppies and kittens in our store and meet your expectations.', 'dreamtails' ),
            'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'front_concierge_lead', array(
            'label'   => __( 'Lead Text', 'dreamtails' ),
            'section' => 'dreamtails_concierge',
            'type'    => 'textarea',
    ) );

    $wp_customize->add_setting( 'front_concierge_desc', array(
            'default'           => __( 'We think the puppies and kittens are worth it and so are you!', 'dreamtails' ),
            'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'front_concierge_desc', array(
            'label'   => __( 'Secondary Text', 'dreamtails' ),
            'section' => 'dreamtails_concierge',
            'type'    => 'textarea',
    ) );

    $wp_customize->add_setting( 'front_concierge_button_text', array(
            'default'           => __( 'Learn more about Dream Tails Boutique', 'dreamtails' ),
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'front_concierge_button_text', array(
            'label'   => __( 'Button Text', 'dreamtails' ),
            'section' => 'dreamtails_concierge',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'front_concierge_button_url', array(
            'default'           => '/about/',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'front_concierge_button_url', array(
            'label'   => __( 'Button URL', 'dreamtails' ),
            'section' => 'dreamtails_concierge',
            'type'    => 'url',
    ) );

    /* Footer Panel */
    $wp_customize->add_panel( 'dreamtails_footer', array(
            'title'    => __( 'Footer', 'dreamtails' ),
            'priority' => 200,
    ) );

    /* Footer Column 1 */
    $wp_customize->add_section( 'dreamtails_footer_col1', array(
            'title' => __( 'Column 1', 'dreamtails' ),
            'panel' => 'dreamtails_footer',
    ) );

    $wp_customize->add_setting( 'footer_col1_heading', array(
            'default'           => __( 'Navigation', 'dreamtails' ),
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'footer_col1_heading', array(
            'label'   => __( 'Heading', 'dreamtails' ),
            'section' => 'dreamtails_footer_col1',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'footer_col1_text', array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
    ) );
    $wp_customize->add_control( 'footer_col1_text', array(
            'label'   => __( 'Additional Text', 'dreamtails' ),
            'section' => 'dreamtails_footer_col1',
            'type'    => 'textarea',
    ) );

    /* Footer Column 2 */
    $wp_customize->add_section( 'dreamtails_footer_col2', array(
            'title' => __( 'Column 2', 'dreamtails' ),
            'panel' => 'dreamtails_footer',
    ) );

    $wp_customize->add_setting( 'footer_col2_heading', array(
            'default'           => __( 'Dream Tails Sarasota', 'dreamtails' ),
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'footer_col2_heading', array(
            'label'   => __( 'Heading', 'dreamtails' ),
            'section' => 'dreamtails_footer_col2',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'footer_col2_address', array(
            'default'           => "6453 Lockwood Ridge Rd\nSarasota, FL 34243",
            'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'footer_col2_address', array(
            'label'   => __( 'Address', 'dreamtails' ),
            'section' => 'dreamtails_footer_col2',
            'type'    => 'textarea',
    ) );

    $wp_customize->add_setting( 'footer_col2_phone', array(
            'default'           => '941-203-1196',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'footer_col2_phone', array(
            'label'   => __( 'Phone Number', 'dreamtails' ),
            'section' => 'dreamtails_footer_col2',
            'type'    => 'text',
    ) );

    /* Footer Column 3 */
    $wp_customize->add_section( 'dreamtails_footer_col3', array(
            'title' => __( 'Column 3', 'dreamtails' ),
            'panel' => 'dreamtails_footer',
    ) );

    $wp_customize->add_setting( 'footer_col3_heading', array(
            'default'           => __( 'Store Hours', 'dreamtails' ),
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'footer_col3_heading', array(
            'label'   => __( 'Heading', 'dreamtails' ),
            'section' => 'dreamtails_footer_col3',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'footer_col3_hours', array(
            'default'           => "Mon - Sat: 10am - 8pm\nSun: 11am - 7pm",
            'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'footer_col3_hours', array(
            'label'   => __( 'Hours', 'dreamtails' ),
            'section' => 'dreamtails_footer_col3',
            'type'    => 'textarea',
    ) );

    /* Footer Column 4 */
    $wp_customize->add_section( 'dreamtails_footer_col4', array(
            'title' => __( 'Column 4', 'dreamtails' ),
            'panel' => 'dreamtails_footer',
    ) );

    $wp_customize->add_setting( 'footer_col4_heading', array(
            'default'           => __( 'About Us', 'dreamtails' ),
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'footer_col4_heading', array(
            'label'   => __( 'Heading', 'dreamtails' ),
            'section' => 'dreamtails_footer_col4',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'footer_col4_text', array(
            'default'           => __( 'Part of the Petland family of stores.', 'dreamtails' ),
            'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'footer_col4_text', array(
            'label'   => __( 'Text', 'dreamtails' ),
            'section' => 'dreamtails_footer_col4',
            'type'    => 'textarea',
    ) );

    $wp_customize->add_setting( 'footer_facebook_url', array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'footer_facebook_url', array(
            'label'   => __( 'Facebook URL', 'dreamtails' ),
            'section' => 'dreamtails_footer_col4',
            'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'footer_instagram_url', array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'footer_instagram_url', array(
            'label'   => __( 'Instagram URL', 'dreamtails' ),
            'section' => 'dreamtails_footer_col4',
            'type'    => 'url',
    ) );
}
add_action( 'customize_register', 'dreamtails_customize_register' );

/**
 * Output dynamic CSS based on customizer settings.
 */
function dreamtails_customizer_css() {
    $hero            = get_theme_mod( 'front_hero_image', get_template_directory_uri() . '/assets/images/homepage_hero.png' );
    $hero_bg_image   = get_theme_mod( 'front_hero_bg_image', '' );
    $hero_bg_color   = get_theme_mod( 'front_hero_bg_color', '#ffcfcd' );
    $test_bg_image   = get_theme_mod( 'front_testimonial_bg_image', '' );
    $test_bg_color   = get_theme_mod( 'front_testimonial_bg_color', '#ffcfcd' );
    ?>
    <style type="text/css">
        @media (min-width: 768px) {
            .hero-image { background-image: url('<?php echo esc_url( $hero ); ?>'); }
        }

        .front-page-hero {
            background-color: <?php echo esc_attr( $hero_bg_color ); ?>;
        <?php if ( $hero_bg_image ) : ?>
            background-image: url('<?php echo esc_url( $hero_bg_image ); ?>');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        <?php endif; ?>
        }

        #happy-tails {
            background-color: <?php echo esc_attr( $test_bg_color ); ?>;
        <?php if ( $test_bg_image ) : ?>
            background-image: url('<?php echo esc_url( $test_bg_image ); ?>');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        <?php endif; ?>
        }
    </style>
    <?php
}
add_action( 'wp_head', 'dreamtails_customizer_css' );