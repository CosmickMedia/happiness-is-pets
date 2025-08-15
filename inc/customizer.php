<?php
/**
 * Theme Customizer - Complete rebuild with all settings
 *
 * @package happiness-is-pets
 */

function happiness_is_pets_customize_register( $wp_customize ) {

    // Remove duplicate default WordPress Homepage Settings section.
    $wp_customize->remove_section( 'static_front_page' );

    // =========================================
    // HEADER SETTINGS PANEL
    // =========================================
    $wp_customize->add_panel( 'happiness_is_pets_header_panel', array(
            'title'       => __( 'Header Settings', 'happiness-is-pets' ),
            'priority'    => 20,
            'description' => __( 'Configure all header elements including logo, locations, and contact info', 'happiness-is-pets' ),
    ) );

    // --- Logo Section ---
    $wp_customize->add_section( 'happiness_is_pets_header_logo', array(
            'title' => __( 'Logo', 'happiness-is-pets' ),
            'panel' => 'happiness_is_pets_header_panel',
            'priority' => 10,
    ) );

    // Logo is handled by WordPress core custom_logo support
    // But we can add additional logo settings if needed

    // --- Header Style Section ---
    $wp_customize->add_section( 'happiness_is_pets_header_style', array(
            'title' => __( 'Header Style', 'happiness-is-pets' ),
            'panel' => 'happiness_is_pets_header_panel',
            'priority' => 20,
    ) );

    $wp_customize->add_setting( 'header_background_color', array(
            'default'           => '#FFFFFF',
            'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_background_color', array(
            'label'   => __( 'Header Background Color', 'happiness-is-pets' ),
            'section' => 'happiness_is_pets_header_style',
    ) ) );

    // --- Locations Section ---
    $wp_customize->add_section( 'happiness_is_pets_locations', array(
            'title' => __( 'Store Locations', 'happiness-is-pets' ),
            'panel' => 'happiness_is_pets_header_panel',
            'priority' => 30,
            'description' => __( 'Configure multiple store locations. Phone numbers will be selectable in header.', 'happiness-is-pets' ),
    ) );

    // Location 1 - Indianapolis
    $wp_customize->add_setting( 'location_1_name', array(
            'default'           => 'Happiness Is Pets Indianapolis',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'location_1_name', array(
            'label'   => __( 'Location 1 Name', 'happiness-is-pets' ),
            'section' => 'happiness_is_pets_locations',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'location_1_phone', array(
            'default'           => '317-537-2480',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'location_1_phone', array(
            'label'   => __( 'Location 1 Phone', 'happiness-is-pets' ),
            'section' => 'happiness_is_pets_locations',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'location_1_email', array(
            'default'           => 'Karenhip7330@gmail.com',
            'sanitize_callback' => 'sanitize_email',
    ) );
    $wp_customize->add_control( 'location_1_email', array(
            'label'   => __( 'Location 1 Email', 'happiness-is-pets' ),
            'section' => 'happiness_is_pets_locations',
            'type'    => 'email',
    ) );

    $wp_customize->add_setting( 'location_1_address', array(
            'default'           => "5905 E 82nd St,\nIndianapolis, IN 46250",
            'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'location_1_address', array(
            'label'   => __( 'Location 1 Address', 'happiness-is-pets' ),
            'section' => 'happiness_is_pets_locations',
            'type'    => 'textarea',
    ) );

    // Location 2 - Schererville
    $wp_customize->add_setting( 'location_2_name', array(
            'default'           => 'Happiness Is Pets Schererville',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'location_2_name', array(
            'label'   => __( 'Location 2 Name', 'happiness-is-pets' ),
            'section' => 'happiness_is_pets_locations',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'location_2_phone', array(
            'default'           => '219-865-3078',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'location_2_phone', array(
            'label'   => __( 'Location 2 Phone', 'happiness-is-pets' ),
            'section' => 'happiness_is_pets_locations',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'location_2_email', array(
            'default'           => 'Erika.Happinessispets@gmail.com',
            'sanitize_callback' => 'sanitize_email',
    ) );
    $wp_customize->add_control( 'location_2_email', array(
            'label'   => __( 'Location 2 Email', 'happiness-is-pets' ),
            'section' => 'happiness_is_pets_locations',
            'type'    => 'email',
    ) );

    $wp_customize->add_setting( 'location_2_address', array(
            'default'           => "1525 US 41,\nSchererville, IN 46375",
            'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'location_2_address', array(
            'label'   => __( 'Location 2 Address', 'happiness-is-pets' ),
            'section' => 'happiness_is_pets_locations',
            'type'    => 'textarea',
    ) );

    // Store Hours
    $wp_customize->add_setting( 'store_hours', array(
            'default'           => "Monday: 11:00 AM - 09:00 PM\nTuesday: 11:00 AM - 09:00 PM\nWednesday: 11:00 AM - 09:00 PM\nThursday: 11:00 AM - 09:00 PM\nFriday: 11:00 AM - 09:00 PM\nSaturday: 11:00 AM - 06:00 PM\nSunday: 11:00 AM - 05:00 PM",
            'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'store_hours', array(
            'label'   => __( 'Store Hours (for all locations)', 'happiness-is-pets' ),
            'section' => 'happiness_is_pets_locations',
            'type'    => 'textarea',
            'description' => __( 'Enter one day per line', 'happiness-is-pets' ),
    ) );

    // =========================================
    // NAVIGATION MENUS SECTION
    // =========================================
    $wp_customize->add_section( 'happiness_is_pets_menus', array(
            'title'       => __( 'Navigation Menus', 'happiness-is-pets' ),
            'priority'    => 25,
            'description' => __( 'Configure menu settings. Assign menus in Appearance > Menus', 'happiness-is-pets' ),
    ) );

    $wp_customize->add_setting( 'menu_text_color', array(
            'default'           => '#333333',
            'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_text_color', array(
            'label'   => __( 'Menu Text Color', 'happiness-is-pets' ),
            'section' => 'happiness_is_pets_menus',
    ) ) );

    $wp_customize->add_setting( 'menu_hover_color', array(
            'default'           => '#00BCD4',
            'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_hover_color', array(
            'label'   => __( 'Menu Hover Color', 'happiness-is-pets' ),
            'section' => 'happiness_is_pets_menus',
    ) ) );

    // =========================================
    // HOMEPAGE SECTIONS PANEL
    // =========================================
    $wp_customize->add_panel( 'happiness_is_pets_homepage', array(
            'title'       => __( 'Homepage Sections', 'happiness-is-pets' ),
            'priority'    => 30,
            'description' => __( 'Configure all homepage sections', 'happiness-is-pets' ),
    ) );

    // --- Hero Section ---
    $wp_customize->add_section( 'homepage_hero', array(
            'title' => __( 'Hero Section', 'happiness-is-pets' ),
            'panel' => 'happiness_is_pets_homepage',
            'priority' => 10,
    ) );

    $wp_customize->add_setting( 'hero_heading', array(
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'hero_heading', array(
            'label'   => __( 'Hero Heading', 'happiness-is-pets' ),
            'section' => 'homepage_hero',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'hero_text', array(
            'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'hero_text', array(
            'label'   => __( 'Hero Text', 'happiness-is-pets' ),
            'section' => 'homepage_hero',
            'type'    => 'textarea',
    ) );

    $wp_customize->add_setting( 'hero_button_text', array(
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'hero_button_text', array(
            'label'   => __( 'Hero Button Text', 'happiness-is-pets' ),
            'section' => 'homepage_hero',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'hero_button_url', array(
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'hero_button_url', array(
            'label'   => __( 'Hero Button URL', 'happiness-is-pets' ),
            'section' => 'homepage_hero',
            'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'hero_image', array(
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'hero_image', array(
            'label'   => __( 'Hero Image', 'happiness-is-pets' ),
            'section' => 'homepage_hero',
    ) ) );

    $wp_customize->add_setting( 'hero_image_mobile', array(
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'hero_image_mobile', array(
            'label'   => __( 'Hero Mobile Image', 'happiness-is-pets' ),
            'section' => 'homepage_hero',
    ) ) );

    $wp_customize->add_setting( 'hero_background_color', array(
            'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'hero_background_color', array(
            'label'   => __( 'Hero Background Color', 'happiness-is-pets' ),
            'section' => 'homepage_hero',
    ) ) );

    $wp_customize->add_setting( 'hero_background_image', array(
            'sanitize_callback' => 'esc_url_raw',
            'default'           => get_template_directory_uri() . '/assets/images/hero.jpg',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'hero_background_image', array(
            'label'   => __( 'Hero Background Image', 'happiness-is-pets' ),
            'section' => 'homepage_hero',
    ) ) );

    $wp_customize->add_setting( 'hero_background_image_mobile', array(
            'sanitize_callback' => 'esc_url_raw',
            'default'           => get_template_directory_uri() . '/assets/images/hero-mobile.jpg',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'hero_background_image_mobile', array(
            'label'   => __( 'Hero Background Image (Mobile)', 'happiness-is-pets' ),
            'section' => 'homepage_hero',
    ) ) );

    // --- Available Puppies Section ---
    $wp_customize->add_section( 'homepage_puppies', array(
            'title' => __( 'Available Puppies Section', 'happiness-is-pets' ),
            'panel' => 'happiness_is_pets_homepage',
            'priority' => 20,
    ) );

    $wp_customize->add_setting( 'puppies_section_title', array(
            'default'           => 'Available Puppies',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'puppies_section_title', array(
            'label'   => __( 'Section Title', 'happiness-is-pets' ),
            'section' => 'homepage_puppies',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'puppies_button_text', array(
            'default'           => 'See All Puppies',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'puppies_button_text', array(
            'label'   => __( 'Button Text', 'happiness-is-pets' ),
            'section' => 'homepage_puppies',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'puppies_button_url', array(
            'default'           => '/all-pets/',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'puppies_button_url', array(
            'label'   => __( 'Button URL', 'happiness-is-pets' ),
            'section' => 'homepage_puppies',
            'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'puppies_background_color', array(
            'default'           => '#F7F7F7',
            'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'puppies_background_color', array(
            'label'   => __( 'Section Background Color', 'happiness-is-pets' ),
            'section' => 'homepage_puppies',
    ) ) );

    // --- Canine Care Section ---
    $wp_customize->add_section( 'homepage_canine_care', array(
            'title' => __( 'Canine Care Certified Section', 'happiness-is-pets' ),
            'panel' => 'happiness_is_pets_homepage',
            'priority' => 30,
    ) );

    $wp_customize->add_setting( 'canine_care_title', array(
            'default'           => 'Canine Care Certified',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'canine_care_title', array(
            'label'   => __( 'Section Title', 'happiness-is-pets' ),
            'section' => 'homepage_canine_care',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'canine_care_subtitle', array(
            'default'           => 'Administered by Purdue University',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'canine_care_subtitle', array(
            'label'   => __( 'Subtitle', 'happiness-is-pets' ),
            'section' => 'homepage_canine_care',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'canine_care_text', array(
            'default'           => 'With an emphasis on offering the best puppies possible from the healthiest and most socially adjusted dogs, we visit many of our breeders on a monthly basis. If you are looking for a purebred puppy with us, you can do so with confidence. We are proud to be Canine Care Certified – Canine Care Certified goes above and beyond currently available canine welfare standards programs. The program sets forth rigorous, science-based, expert-reviewed standards for canine physical and behavioral welfare in areas such as nutrition, veterinary care, housing, handling and exercise.',
            'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'canine_care_text', array(
            'label'   => __( 'Section Text', 'happiness-is-pets' ),
            'section' => 'homepage_canine_care',
            'type'    => 'textarea',
    ) );

    $wp_customize->add_setting( 'canine_care_button_text', array(
            'default'           => 'Learn More',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'canine_care_button_text', array(
            'label'   => __( 'Button Text', 'happiness-is-pets' ),
            'section' => 'homepage_canine_care',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'canine_care_button_url', array(
            'default'           => '/breeders/',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'canine_care_button_url', array(
            'label'   => __( 'Button URL', 'happiness-is-pets' ),
            'section' => 'homepage_canine_care',
            'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'canine_care_image', array(
            'default'           => get_template_directory_uri() . '/assets/images/caninecare.webp',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'canine_care_image', array(
            'label'   => __( 'Section Image', 'happiness-is-pets' ),
            'section' => 'homepage_canine_care',
    ) ) );

    $wp_customize->add_setting( 'canine_care_background_color', array(
            'default'           => '#FFFFFF',
            'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'canine_care_background_color', array(
            'label'   => __( 'Section Background Color', 'happiness-is-pets' ),
            'section' => 'homepage_canine_care',
    ) ) );

    // --- Financing Section ---
    $wp_customize->add_section( 'homepage_financing', array(
            'title' => __( 'Financing Section', 'happiness-is-pets' ),
            'panel' => 'happiness_is_pets_homepage',
            'priority' => 40,
    ) );

    $wp_customize->add_setting( 'financing_title', array(
            'default'           => 'Take Home Your Newest Addition!',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'financing_title', array(
            'label'   => __( 'Section Title', 'happiness-is-pets' ),
            'section' => 'homepage_financing',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'financing_subtitle', array(
            'default'           => 'Healthy, Happy, and Adorable',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'financing_subtitle', array(
            'label'   => __( 'Subtitle', 'happiness-is-pets' ),
            'section' => 'homepage_financing',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'financing_text', array(
            'default'           => 'Quick and easy financing is available. Apply Now!',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'financing_text', array(
            'label'   => __( 'Section Text', 'happiness-is-pets' ),
            'section' => 'homepage_financing',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'financing_image', array(
            'default'           => get_template_directory_uri() . '/assets/images/ourpuppies.webp',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'financing_image', array(
            'label'   => __( 'Section Image', 'happiness-is-pets' ),
            'section' => 'homepage_financing',
    ) ) );

    $wp_customize->add_setting( 'financing_button1_text', array(
            'default'           => 'Apply Now - Schereville',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'financing_button1_text', array(
            'label'   => __( 'Button 1 Text', 'happiness-is-pets' ),
            'section' => 'homepage_financing',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'financing_button1_url', array(
            'default'           => 'https://app.formpiper.com/outside-form/happiness-is-pets-schererville/fTlGP4zm9zLqayP9FI9M1nB4JACHgY?qr=true',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'financing_button1_url', array(
            'label'   => __( 'Button 1 URL', 'happiness-is-pets' ),
            'section' => 'homepage_financing',
            'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'financing_button2_text', array(
            'default'           => 'Apply Now - Indianapolis',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'financing_button2_text', array(
            'label'   => __( 'Button 2 Text', 'happiness-is-pets' ),
            'section' => 'homepage_financing',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'financing_button2_url', array(
            'default'           => 'https://app.formpiper.com/outside-form/happiness-is-pets-indianapolis/tjtpqDXcm2p4H4oOd37nKRsg5xFwGa',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'financing_button2_url', array(
            'label'   => __( 'Button 2 URL', 'happiness-is-pets' ),
            'section' => 'homepage_financing',
            'type'    => 'url',
    ) );

    // --- Our Guarantee Section ---
    $wp_customize->add_section( 'homepage_guarantee', array(
            'title' => __( 'Our Guarantee Section', 'happiness-is-pets' ),
            'panel' => 'happiness_is_pets_homepage',
            'priority' => 50,
    ) );

    $wp_customize->add_setting( 'guarantee_title', array(
            'default'           => 'Our Guarantee',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'guarantee_title', array(
            'label'   => __( 'Section Title', 'happiness-is-pets' ),
            'section' => 'homepage_guarantee',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'guarantee_subtitle', array(
            'default'           => 'Peace of mind for you and your puppy',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'guarantee_subtitle', array(
            'label'   => __( 'Subtitle', 'happiness-is-pets' ),
            'section' => 'homepage_guarantee',
            'type'    => 'text',
    ) );

    // Guarantee Item 1
    $wp_customize->add_setting( 'guarantee_1_title', array(
            'default'           => '2 year health warranty',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'guarantee_1_title', array(
            'label'   => __( 'Guarantee 1 Title', 'happiness-is-pets' ),
            'section' => 'homepage_guarantee',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'guarantee_1_text', array(
            'default'           => 'Since many, but not all congenital defects arise within the first year, we go the extra mile to ensure you and your new puppy are covered.',
            'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'guarantee_1_text', array(
            'label'   => __( 'Guarantee 1 Text', 'happiness-is-pets' ),
            'section' => 'homepage_guarantee',
            'type'    => 'textarea',
    ) );

    $wp_customize->add_setting( 'guarantee_1_button_text', array(
            'default'           => 'Health Warranty',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'guarantee_1_button_text', array(
            'label'   => __( 'Guarantee 1 Button Text', 'happiness-is-pets' ),
            'section' => 'homepage_guarantee',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'guarantee_1_button_url', array(
            'default'           => '/health-warranty/',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'guarantee_1_button_url', array(
            'label'   => __( 'Guarantee 1 Button URL', 'happiness-is-pets' ),
            'section' => 'homepage_guarantee',
            'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'guarantee_1_image', array(
            'default'           => get_template_directory_uri() . '/assets/images/health-warranty.webp__88.0x77.0_subsampling-2.webp',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'guarantee_1_image', array(
            'label'   => __( 'Guarantee 1 Image', 'happiness-is-pets' ),
            'section' => 'homepage_guarantee',
    ) ) );

    // Guarantee Item 2
    $wp_customize->add_setting( 'guarantee_2_title', array(
            'default'           => '7 day veterinary check',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'guarantee_2_title', array(
            'label'   => __( 'Guarantee 2 Title', 'happiness-is-pets' ),
            'section' => 'homepage_guarantee',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'guarantee_2_text', array(
            'default'           => 'Bring your new puppy to any of our in-network clinics within 7 days of purchase for a complimentary wellness check-up.',
            'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'guarantee_2_text', array(
            'label'   => __( 'Guarantee 2 Text', 'happiness-is-pets' ),
            'section' => 'homepage_guarantee',
            'type'    => 'textarea',
    ) );

    $wp_customize->add_setting( 'guarantee_2_button_text', array(
            'default'           => 'Veterinary Check',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'guarantee_2_button_text', array(
            'label'   => __( 'Guarantee 2 Button Text', 'happiness-is-pets' ),
            'section' => 'homepage_guarantee',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'guarantee_2_button_url', array(
            'default'           => '/veterinary-check/',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'guarantee_2_button_url', array(
            'label'   => __( 'Guarantee 2 Button URL', 'happiness-is-pets' ),
            'section' => 'homepage_guarantee',
            'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'guarantee_2_image', array(
            'default'           => get_template_directory_uri() . '/assets/images/veterinary-check.webp__81.0x68.0_subsampling-2.webp',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'guarantee_2_image', array(
            'label'   => __( 'Guarantee 2 Image', 'happiness-is-pets' ),
            'section' => 'homepage_guarantee',
    ) ) );

    $wp_customize->add_setting( 'guarantee_background_color', array(
            'default'           => '#FFFFFF',
            'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'guarantee_background_color', array(
            'label'   => __( 'Section Background Color', 'happiness-is-pets' ),
            'section' => 'homepage_guarantee',
    ) ) );

    // --- Reviews Section ---
    $wp_customize->add_section( 'homepage_reviews', array(
            'title' => __( 'Reviews Section', 'happiness-is-pets' ),
            'panel' => 'happiness_is_pets_homepage',
            'priority' => 60,
    ) );

    $wp_customize->add_setting( 'reviews_title', array(
            'default'           => 'See What Our Customers Say',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'reviews_title', array(
            'label'   => __( 'Section Title', 'happiness-is-pets' ),
            'section' => 'homepage_reviews',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'reviews_button_text', array(
            'default'           => 'Submit Your Own Story',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'reviews_button_text', array(
            'label'   => __( 'Button Text', 'happiness-is-pets' ),
            'section' => 'homepage_reviews',
            'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'reviews_button_url', array(
            'default'           => '/testimonials/',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'reviews_button_url', array(
            'label'   => __( 'Button URL', 'happiness-is-pets' ),
            'section' => 'homepage_reviews',
            'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'reviews_background_color', array(
            'default'           => '#FFFFFF',
            'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'reviews_background_color', array(
            'label'   => __( 'Section Background Color', 'happiness-is-pets' ),
            'section' => 'homepage_reviews',
    ) ) );

    // =========================================
    // FOOTER SETTINGS PANEL
    // =========================================
    $wp_customize->add_panel( 'happiness_is_pets_footer_panel', array(
            'title'       => __( 'Footer Settings', 'happiness-is-pets' ),
            'priority'    => 40,
            'description' => __( 'Configure footer elements including social media, copyright, and styling', 'happiness-is-pets' ),
    ) );

    // --- Footer Style Section ---
    $wp_customize->add_section( 'footer_style', array(
            'title' => __( 'Footer Style', 'happiness-is-pets' ),
            'panel' => 'happiness_is_pets_footer_panel',
            'priority' => 10,
    ) );

    $wp_customize->add_setting( 'footer_background_color', array(
            'default'           => '#00c8baff',
            'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_background_color', array(
            'label'   => __( 'Footer Background Color', 'happiness-is-pets' ),
            'section' => 'footer_style',
    ) ) );

    $wp_customize->add_setting( 'footer_text_color', array(
            'default'           => '#000000',
            'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_text_color', array(
            'label'   => __( 'Footer Text Color', 'happiness-is-pets' ),
            'section' => 'footer_style',
    ) ) );

    // --- Social Media Section ---
    $wp_customize->add_section( 'footer_social', array(
            'title' => __( 'Social Media Links', 'happiness-is-pets' ),
            'panel' => 'happiness_is_pets_footer_panel',
            'priority' => 20,
            'description' => __( 'Social media icons will appear under the logo in footer', 'happiness-is-pets' ),
    ) );

    $wp_customize->add_setting( 'social_facebook', array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'social_facebook', array(
            'label'   => __( 'Facebook URL', 'happiness-is-pets' ),
            'section' => 'footer_social',
            'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'social_instagram', array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'social_instagram', array(
            'label'   => __( 'Instagram URL', 'happiness-is-pets' ),
            'section' => 'footer_social',
            'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'social_twitter', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'social_twitter', array(
            'label'   => __( 'Twitter URL', 'happiness-is-pets' ),
            'section' => 'footer_social',
            'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'social_youtube', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'social_youtube', array(
            'label'   => __( 'YouTube URL', 'happiness-is-pets' ),
            'section' => 'footer_social',
            'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'social_tiktok', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'social_tiktok', array(
            'label'   => __( 'TikTok URL', 'happiness-is-pets' ),
            'section' => 'footer_social',
            'type'    => 'url',
    ) );

    // --- Footer Copyright Section ---
    $wp_customize->add_section( 'footer_copyright', array(
            'title' => __( 'Copyright & Links', 'happiness-is-pets' ),
            'panel' => 'happiness_is_pets_footer_panel',
            'priority' => 30,
    ) );

    $wp_customize->add_setting( 'footer_copyright_text', array(
            'default'           => 'Cosmick Media & Happiness Is Pets Indianapolis',
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'footer_copyright_text', array(
            'label'   => __( 'Copyright Text', 'happiness-is-pets' ),
            'section' => 'footer_copyright',
            'type'    => 'text',
            'description' => __( 'Year will be added automatically', 'happiness-is-pets' ),
    ) );

    $wp_customize->add_setting( 'footer_copyright_url', array(
            'default'           => 'https://www.cosmickmedia.com',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'footer_copyright_url', array(
            'label'   => __( 'Copyright Link URL', 'happiness-is-pets' ),
            'section' => 'footer_copyright',
            'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'footer_privacy_url', array(
            'default'           => '/privacy-policy',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'footer_privacy_url', array(
            'label'   => __( 'Privacy Policy URL', 'happiness-is-pets' ),
            'section' => 'footer_copyright',
            'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'footer_contact_url', array(
            'default'           => '/contact/',
            'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'footer_contact_url', array(
            'label'   => __( 'Contact Page URL', 'happiness-is-pets' ),
            'section' => 'footer_copyright',
            'type'    => 'url',
    ) );

    // =========================================
    // SHOP SETTINGS
    // =========================================
    $wp_customize->add_section( 'happiness_is_pets_shop', array(
            'title'       => __( 'Shop Settings', 'happiness-is-pets' ),
            'priority'    => 50,
            'description' => __( 'Configure WooCommerce shop settings', 'happiness-is-pets' ),
    ) );

    $wp_customize->add_setting( 'enable_catalog_mode', array(
            'default'           => false,
            'sanitize_callback' => function( $value ) {
                return (bool) $value;
            },
    ) );
    $wp_customize->add_control( 'enable_catalog_mode', array(
            'label'       => __( 'Enable Catalog Mode', 'happiness-is-pets' ),
            'section'     => 'happiness_is_pets_shop',
            'type'        => 'checkbox',
            'description' => __( 'Hide prices and add to cart buttons', 'happiness-is-pets' ),
    ) );
}
add_action( 'customize_register', 'happiness_is_pets_customize_register' );

/**
 * Output dynamic CSS based on customizer settings
 */
function happiness_is_pets_customizer_css() {
    ?>
    <style type="text/css">
        /* Header Styles */
        .cssHeader,
        .top-header-bar {
            background-color: <?php echo esc_attr( get_theme_mod( 'header_background_color', '#FFFFFF' ) ); ?>;
        }

        /* Menu Colors */
        .navbar-nav-horizontal a {
            color: <?php echo esc_attr( get_theme_mod( 'menu_text_color', '#333333' ) ); ?> !important;
        }
        .navbar-nav-horizontal a:hover,
        .navbar-nav-horizontal .current-menu-item > a {
            color: <?php echo esc_attr( get_theme_mod( 'menu_hover_color', '#00BCD4' ) ); ?> !important;
        }

        /* Hero Section */
        <?php
        $hero_bg_color         = get_theme_mod( 'hero_background_color' );
        $hero_bg_image         = get_theme_mod( 'hero_background_image' );
        $hero_bg_image_mobile  = get_theme_mod( 'hero_background_image_mobile' );
        if ( $hero_bg_color || $hero_bg_image || $hero_bg_image_mobile ) :
        ?>
        .front-page-hero {
            <?php if ( $hero_bg_color ) : ?>
            background-color: <?php echo esc_attr( $hero_bg_color ); ?>;
            <?php endif; ?>
            <?php if ( $hero_bg_image ) : ?>
            background-image: url('<?php echo esc_url( $hero_bg_image ); ?>');
            <?php endif; ?>
        }
        <?php if ( $hero_bg_image_mobile ) : ?>
        @media (max-width: 767px) {
            .front-page-hero {
                background-image: url('<?php echo esc_url( $hero_bg_image_mobile ); ?>');
            }
        }
        <?php endif; ?>
        <?php endif; ?>

        /* Available Puppies Section */
        #available-puppies {
            background-color: <?php echo esc_attr( get_theme_mod( 'puppies_background_color', '#F7F7F7' ) ); ?>;
        }

        /* Canine Care Section */
        .canine-care-certified {
            background-color: <?php echo esc_attr( get_theme_mod( 'canine_care_background_color', '#FFFFFF' ) ); ?>;
        }

        /* Our Guarantee Section */
        #our-guarantee {
            background-color: <?php echo esc_attr( get_theme_mod( 'guarantee_background_color', '#FFFFFF' ) ); ?>;
        }

        /* Reviews Section */
        #reviews {
            background-color: <?php echo esc_attr( get_theme_mod( 'reviews_background_color', '#FFFFFF' ) ); ?>;
        }

        /* Footer Styles */
        .cssFooter {
            background-color: <?php echo esc_attr( get_theme_mod( 'footer_background_color', '#00c8baff' ) ); ?>;
            color: <?php echo esc_attr( get_theme_mod( 'footer_text_color', '#000000' ) ); ?>;
        }
        .cssFooter a,
        .cssFooter .footer-title,
        .cssFooter .footer-contact-list li,
        .cssFooter .footer-hours-list li,
        .cssFooter .footer-menu li a {
            color: <?php echo esc_attr( get_theme_mod( 'footer_text_color', '#000000' ) ); ?>;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'happiness_is_pets_customizer_css' );

/**
 * Retrieve all configured locations with phone and address.
 *
 * @return array[] Array of locations containing name, phone, and address.
 */
function happiness_is_pets_get_locations() {
    return array(
        array(
            'name'    => get_theme_mod( 'location_1_name', 'Happiness Is Pets Indianapolis' ),
            'phone'   => get_theme_mod( 'location_1_phone', '317-537-2480' ),
            'address' => get_theme_mod( 'location_1_address', "5905 E 82nd St,\nIndianapolis, IN 46250" ),
        ),
        array(
            'name'    => get_theme_mod( 'location_2_name', 'Happiness Is Pets Schererville' ),
            'phone'   => get_theme_mod( 'location_2_phone', '219-865-3078' ),
            'address' => get_theme_mod( 'location_2_address', "1525 US 41,\nSchererville, IN 46375" ),
        ),
    );
}