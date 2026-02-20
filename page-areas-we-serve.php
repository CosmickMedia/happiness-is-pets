<?php
/**
  * Template Name: Areas We Serve
 */

get_header();
get_template_part('assets/partials/hero');
global $petland_settings;

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
/**
 * Finds the matching location slug for the given location city.
 *
 * This looks through $petland_settings->api_locations and matches the city component
 * (case-insensitively, before the comma in the formatted location string) to the provided $location_city.
 */
$matching_location_slug = '';
if ($location_city && isset($petland_settings->api_locations)) {
    foreach ($petland_settings->api_locations as $slug => $location_data) {
        // $location_data[0] is expected to be a string like "Stuart, FL"
        $location_name = $location_data[0];
        $city_from_location = explode(',', $location_name)[0]; // Extract city part only

        // Check if cities match, case-insensitive and trimmed
        if (strtolower(trim($city_from_location)) === strtolower(trim($location_city))) {
            $matching_location_slug = $slug;
            break; // found, stop searching
        }
    }
}

?>

    <script type="application/ld+json">
        {"@context":"https://schema.org","@type":"LocalBusiness","name":"<?php echo esc_attr(get_bloginfo('name')); ?> - <?php echo esc_attr($full_location); ?>","description":"<?php echo esc_attr(get_the_excerpt()); ?>",<?php if($location_phone): ?>"telephone":"<?php echo esc_attr($location_phone); ?>",<?php endif; ?><?php if($location_email): ?>"email":"<?php echo esc_attr($location_email); ?>",<?php endif; ?>"address":{"@type":"PostalAddress",<?php if($location_city): ?>"addressLocality":"<?php echo esc_attr($location_city); ?>",<?php endif; ?><?php if($location_state): ?>"addressRegion":"<?php echo esc_attr($location_state); ?>",<?php endif; ?><?php if($location_zip): ?>"postalCode":"<?php echo esc_attr($location_zip); ?>",<?php endif; ?>"addressCountry":"US"},<?php if($service_radius): ?>"areaServed":{"@type":"GeoCircle","geoMidpoint":{"@type":"GeoCoordinates","addressCountry":"US"},"geoRadius":"<?php echo esc_attr($service_radius); ?>"},<?php endif; ?><?php if($business_hours): ?>"openingHours":"<?php echo esc_attr($business_hours); ?>",<?php endif; ?>"url":"<?php echo esc_url(get_permalink()); ?>","priceRange":"$$","image":"<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full')); ?>","sameAs":["<?php echo esc_url(home_url()); ?>"]}
    </script>

    <script type="application/ld+json">
        {"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Home","item":"<?php echo esc_url(home_url()); ?>"},{"@type":"ListItem","position":2,"name":"Areas We Serve","item":"<?php echo esc_url(home_url('/areas-we-serve/')); ?>"},{"@type":"ListItem","position":3,"name":"<?php the_title(); ?>","item":"<?php echo esc_url(get_permalink()); ?>"}]}
    </script>

    <script type="application/ld+json">
        {"@context":"https://schema.org","@type":"Service","serviceType":"Puppy Sales and Delivery","provider":{"@type":"LocalBusiness","name":"<?php echo esc_attr(get_bloginfo('name')); ?>"},"areaServed":{"@type":"City","name":"<?php echo esc_attr($location_city); ?>"},"description":"Premium puppies for sale in <?php echo esc_attr($full_location); ?>. Local pickup and delivery available.","offers":{"@type":"Offer","availability":"https://schema.org/InStock"}}
    </script>

<script>
function toggleReadMore() {
    const content = document.querySelector('.read-more-content');
    const button = document.querySelector('.read-more-btn');
    const chevron = button.querySelector('.chevron');
    
    if (content.style.display === 'none' || content.style.display === '') {
        content.style.display = 'block';
        button.innerHTML = 'Read Less <span class="chevron">▲</span>';
        button.classList.add('expanded');
    } else {
        content.style.display = 'none';
        button.innerHTML = 'Read More <span class="chevron">▼</span>';
        button.classList.remove('expanded');
    }
}
</script>

<div class="page__content">
    <div class="l-constrain">
        <div class="petkey">
            
            <!-- Information Boxes Section -->
            <div class="info-boxes">
                <?php if ($location_phone): ?>
                <div class="info-box">
                    <div class="info-box-label">Call or Text</div>
                    <div class="info-box-value"><?php echo esc_html($location_phone); ?></div>
                </div>
                <?php endif; ?>
                
                <?php if ($location_city): ?>
                <div class="info-box">
                    <div class="info-box-label">City</div>
                    <div class="info-box-value"><?php echo esc_html($location_city); ?></div>
                </div>
                <?php endif; ?>
                
                <?php if ($location_state): ?>
                <div class="info-box">
                    <div class="info-box-label">State</div>
                    <div class="info-box-value"><?php echo esc_html($location_state); ?></div>
                </div>
                <?php endif; ?>
                
                <?php if ($service_radius): ?>
                <div class="info-box">
                    <div class="info-box-label">Service Radius</div>
                    <div class="info-box-value"><?php echo esc_html($service_radius); ?> miles</div>
                </div>
                <?php endif; ?>
                
                <div class="info-box">
                    <div class="info-box-label">Delivery</div>
                    <div class="info-box-value"><?php echo ($delivery_available === 'yes') ? 'Available' : 'Not Available'; ?></div>
                </div>
                
                <div class="info-box">
                    <div class="info-box-label">Local Pickup</div>
                    <div class="info-box-value"><?php echo ($pickup_available === 'yes') ? 'Available' : 'Not Available'; ?></div>
                </div>
            </div>

            <!-- Content Section with Read More -->
            <div class="content-section">
                <div class="content-text">
                    <?php
                    // Get the page content
                    $page_content = get_the_content();
                    
                    // Split content at a specific point (you can adjust this logic)
                    $content_parts = explode('<!--more-->', $page_content);
                    $main_content = $content_parts[0];
                    $read_more_content = isset($content_parts[1]) ? $content_parts[1] : '';
                    
                    // If no <!--more--> tag, split at first paragraph break
                    if (empty($read_more_content)) {
                        $paragraphs = explode('</p>', $main_content);
                        if (count($paragraphs) > 1) {
                            $main_content = $paragraphs[0] . '</p>';
                            $read_more_content = implode('</p>', array_slice($paragraphs, 1));
                        }
                    }
                    
                    // Display main content
                    if (!empty($main_content)) {
                        echo wp_kses_post($main_content);
                    } else {
                        // Fallback content if no page content
                        echo '<p>At ' . esc_html(get_bloginfo('name')) . ', we help families in ' . esc_html($location_city) . ' and surrounding areas find healthy, well-bred puppies for sale in ' . esc_html($location_city) . ' from breeders who share our commitment to wellness. We believe every puppy should come from breeders who prioritize animal welfare, and we\'ve built a network that upholds those high standards.</p>';
                    }
                    ?>
                    
                    <?php if (!empty($read_more_content)): ?>
                    <div class="read-more-content">
                        <?php echo wp_kses_post($read_more_content); ?>
                        
                        <?php if ($business_hours): ?>
                        <p><strong>Business Hours:</strong> <?php echo esc_html($business_hours); ?></p>
                        <?php endif; ?>
                        
                        <?php if ($location_email): ?>
                        <p><strong>Email:</strong> <a href="mailto:<?php echo esc_attr($location_email); ?>"><?php echo esc_html($location_email); ?></a></p>
                        <?php endif; ?>
                    </div>
                    
                    <button class="read-more-btn" onclick="toggleReadMore()">
                        Read More <span class="chevron">▼</span>
                    </button>
                    <?php endif; ?>
                </div>
            </div>


                <div class="aws-products-section">
                    <div class="container">
                        <h2 class="section-title text-center fw-bold mb-5">Available Puppies</h2>
                        <div class="woocommerce">
                            <?php
                            // Query all products (puppies) using the same method as page-all-pets.php
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
                            
                            // Run the query
                            $products = new WP_Query($args);

                            // Check if there are any products to show
                            if ($products->have_posts()) {
                                woocommerce_product_loop_start();
                                
                                while ($products->have_posts()) {
                                    $products->the_post();
                                    wc_get_template_part('content', 'product');
                                }
                                
                                woocommerce_product_loop_end();
                                
                                // Reset the global post object so that the rest of the page works correctly
                                wp_reset_postdata();
                            } else {
                                // No products found
                                echo '<div class="alert alert-info text-center"><p>No puppies available at this time.</p></div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>



        </div>
    </div>
</div>

<?php get_template_part('template-parts/pre-footer'); ?>

<?php

get_footer();


