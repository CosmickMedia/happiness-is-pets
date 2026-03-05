<?php
/**
 * Template Name: Areas We Serve
 */

get_header();
get_template_part('template-parts/page', 'header');
global $petland_settings;

// Get meta fields
$location_city = get_post_meta(get_the_ID(), 'location_city', true);
$location_state = get_post_meta(get_the_ID(), 'location_state', true);
$location_zip = get_post_meta(get_the_ID(), 'location_zip', true);
$service_radius = get_post_meta(get_the_ID(), 'service_radius', true);
$location_phone = get_post_meta(get_the_ID(), 'location_phone', true);
$location_email = get_post_meta(get_the_ID(), 'location_email', true);
$testimonial_ids = get_post_meta(get_the_ID(), 'testimonial_ids', true);
$delivery_available = get_post_meta(get_the_ID(), 'delivery_available', true);
$pickup_available = get_post_meta(get_the_ID(), 'pickup_available', true);
$business_hours = get_post_meta(get_the_ID(), 'business_hours', true);
$full_location = $location_city . ($location_state ? ', ' . $location_state : '') . ($location_zip ? ' ' . $location_zip : '');

// Find matching location slug based on city name
$matching_location_slug = '';
if ($location_city && isset($petland_settings->api_locations)) {
    foreach ($petland_settings->api_locations as $slug => $location_data) {
        $location_name = $location_data[0];
        $city_from_location = explode(',', $location_name)[0];

        if (strtolower(trim($city_from_location)) === strtolower(trim($location_city))) {
            $matching_location_slug = $slug;
            break;
        }
    }
}

// Check if any meta fields have data worth displaying
$has_meta_data = $location_city || $location_state || $location_phone ||
                 $service_radius || $delivery_available === 'yes' ||
                 $pickup_available === 'yes';
?>

<main id="primary" class="site-main py-4">
    <div class="main-container">

        <!-- Page Content -->
        <div class="aws-content-section mb-5">
            <?php
            // Get and process the content
            $full_content = get_the_content();
            $full_content = apply_filters('the_content', $full_content);

            // Check for <!--more--> tag first, otherwise split after first </p>
            if (strpos($full_content, '<!--more-->') !== false) {
                $parts = explode('<!--more-->', $full_content, 2);
                $preview_content = $parts[0];
                $expanded_content = isset($parts[1]) ? $parts[1] : '';
            } else {
                // Split after first paragraph
                $first_p_end = strpos($full_content, '</p>');
                if ($first_p_end !== false) {
                    $preview_content = substr($full_content, 0, $first_p_end + 4);
                    $expanded_content = substr($full_content, $first_p_end + 4);
                } else {
                    $preview_content = $full_content;
                    $expanded_content = '';
                }
            }

            // Build expanded content with contact details
            $has_expanded_content = trim(strip_tags($expanded_content)) || $business_hours || $location_email;
            ?>

            <!-- Preview paragraph (always visible) -->
            <div class="aws-content-preview">
                <?php echo $preview_content; ?>
            </div>

            <?php if ($has_expanded_content): ?>
            <!-- Collapsible content (hidden by default) -->
            <div class="aws-content-expanded" id="aws-expanded-content" style="display: none;">
                <?php echo $expanded_content; ?>

                <?php if ($business_hours || $location_email): ?>
                <div class="aws-contact-details mt-4">
                    <?php if ($business_hours): ?>
                    <p><strong>Business Hours:</strong> <?php echo esc_html($business_hours); ?></p>
                    <?php endif; ?>

                    <?php if ($location_email): ?>
                    <p><strong>Email:</strong> <a href="mailto:<?php echo esc_attr($location_email); ?>"><?php echo esc_html($location_email); ?></a></p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Toggle button -->
            <button class="aws-read-more-btn" id="aws-read-more-btn" type="button">
                Read More <span class="aws-chevron">&#9660;</span>
            </button>

            <script>
            (function() {
                var btn = document.getElementById('aws-read-more-btn');
                var content = document.getElementById('aws-expanded-content');
                var isExpanded = false;

                btn.addEventListener('click', function() {
                    isExpanded = !isExpanded;
                    content.style.display = isExpanded ? 'block' : 'none';
                    btn.classList.toggle('expanded', isExpanded);
                    btn.innerHTML = isExpanded
                        ? 'Read Less <span class="aws-chevron">&#9660;</span>'
                        : 'Read More <span class="aws-chevron">&#9660;</span>';
                });
            })();
            </script>
            <?php endif; ?>
        </div>

        <?php if ($has_meta_data): ?>
        <!-- Location Info Boxes -->
        <div class="aws-info-section mb-4">
            <div class="row g-3">
                <?php if ($location_phone): ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="aws-info-box">
                        <div class="aws-info-label">Call or Text</div>
                        <div class="aws-info-value"><?php echo esc_html($location_phone); ?></div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($location_city): ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="aws-info-box">
                        <div class="aws-info-label">City</div>
                        <div class="aws-info-value"><?php echo esc_html($location_city); ?></div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($location_state): ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="aws-info-box">
                        <div class="aws-info-label">State</div>
                        <div class="aws-info-value"><?php echo esc_html($location_state); ?></div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($service_radius): ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="aws-info-box">
                        <div class="aws-info-label">Service Radius</div>
                        <div class="aws-info-value"><?php echo esc_html($service_radius); ?> miles</div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($delivery_available === 'yes'): ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="aws-info-box">
                        <div class="aws-info-label">Delivery</div>
                        <div class="aws-info-value">Available</div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($pickup_available === 'yes'): ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="aws-info-box">
                        <div class="aws-info-label">Local Pickup</div>
                        <div class="aws-info-value">Available</div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Available Puppies -->
        <div class="aws-products-section">
            <h2 class="section-title text-center fw-bold mb-4">Available Puppies</h2>
            <div class="woocommerce">
                <?php
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field'    => 'slug',
                            'terms'    => 'puppies-for-sale',
                        ),
                    ),
                    'orderby' => 'date',
                    'order' => 'DESC',
                );

                $products = new WP_Query($args);

                if ($products->have_posts()) {
                    woocommerce_product_loop_start();

                    while ($products->have_posts()) {
                        $products->the_post();
                        wc_get_template_part('content', 'product');
                    }

                    woocommerce_product_loop_end();
                    wp_reset_postdata();
                } else {
                    echo '<div class="alert alert-info text-center"><p>No puppies available at this time.</p></div>';
                }
                ?>
            </div>
        </div>

    </div>
</main>

<?php get_template_part('template-parts/pre-footer'); ?>
<?php get_footer(); ?>
