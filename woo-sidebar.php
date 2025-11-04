<?php
/**
 * Custom WooCommerce Sidebar for Product Filtering
 * Filters products by Gender, Breed, and Location with AJAX
 *
 * @package happiness-is-pets
 */

// Get all unique genders, breeds, and locations from products
function get_filter_options() {
    global $wpdb;

    $options = array(
        'genders' => array(),
        'breeds' => array(),
        'locations' => array()
    );

    // Get all published products (excluding Accessories)
    $products = get_posts(array(
        'post_type' => 'product',
        'post_status' => happiness_is_pets_get_visible_product_statuses(),
        'posts_per_page' => -1,
        'fields' => 'ids',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => 'accessories',
                'operator' => 'NOT IN',
            ),
        ),
    ));

    foreach ($products as $product_id) {
        // Get gender from ACF
        $gender = get_field('gender', $product_id);
        if ($gender && !in_array($gender, $options['genders'])) {
            $options['genders'][] = $gender;
        }

        // Get breed and location from WC Unified KM
        if (function_exists('wc_ukm_get_pet')) {
            $pet = wc_ukm_get_pet($product_id);

            if (!empty($pet->breed) && !in_array($pet->breed, $options['breeds'])) {
                $options['breeds'][] = $pet->breed;
            }

            if (!empty($pet->location)) {
                $location = trim($pet->location);
                if (!in_array($location, $options['locations'])) {
                    $options['locations'][] = $location;
                }
            }
        }
    }

    // Sort options alphabetically
    sort($options['genders']);
    sort($options['breeds']);
    sort($options['locations']);

    return $options;
}

// Get currently selected filters from URL
$current_gender = isset($_GET['filter_gender']) ? sanitize_text_field($_GET['filter_gender']) : '';
$current_breed = isset($_GET['filter_breed']) ? sanitize_text_field($_GET['filter_breed']) : '';
$current_location = isset($_GET['filter_location']) ? sanitize_text_field($_GET['filter_location']) : '';

// By default, both locations are selected (empty means both)
$location_is_default = empty($current_location);

$filter_options = get_filter_options();
?>

<div class="woo-custom-sidebar">
    <div class="sidebar-header mb-4">
        <?php if ($current_gender || $current_breed || !$location_is_default) : ?>
            <button type="button" class="btn btn-sm btn-outline-secondary w-100 clear-all-filters">
                <i class="fas fa-times me-1"></i>Clear All Filters
            </button>
        <?php endif; ?>
    </div>

    <!-- Location Filter - NOW FIRST -->
    <?php if (!empty($filter_options['locations'])) : ?>
    <div class="filter-widget location-filter mb-4">
        <div class="filter-options">
            <?php foreach ($filter_options['locations'] as $location) :
                $location_slug = sanitize_title($location);
                // Check if this specific location is selected, or if no filter is set (default = both checked)
                $is_selected = ($location_is_default || $current_location === $location);
            ?>
            <div class="form-check filter-option">
                <input
                    class="form-check-input product-filter-checkbox location-checkbox"
                    type="checkbox"
                    value="<?php echo esc_attr($location); ?>"
                    id="location-<?php echo esc_attr($location_slug); ?>"
                    data-filter-type="location"
                    <?php checked($is_selected); ?>
                >
                <label class="form-check-label" for="location-<?php echo esc_attr($location_slug); ?>">
                    <i class="fas fa-map-marker-alt me-2"></i><?php echo esc_html($location); ?>
                </label>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Gender Filter -->
    <?php if (!empty($filter_options['genders'])) : ?>
    <div class="filter-widget gender-filter mb-4">
        <div class="filter-options">
            <?php foreach ($filter_options['genders'] as $gender) :
                $gender_lower = strtolower($gender);
                $is_selected = ($current_gender === $gender);
            ?>
            <div class="form-check filter-option">
                <input
                    class="form-check-input product-filter-checkbox"
                    type="checkbox"
                    value="<?php echo esc_attr($gender); ?>"
                    id="gender-<?php echo esc_attr($gender_lower); ?>"
                    data-filter-type="gender"
                    <?php checked($is_selected); ?>
                >
                <label class="form-check-label" for="gender-<?php echo esc_attr($gender_lower); ?>">
                    <?php echo esc_html(ucfirst($gender)); ?>
                </label>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Breed Filter -->
    <?php if (!empty($filter_options['breeds'])) : ?>
    <div class="filter-widget breed-filter mb-4">
        <div class="filter-options">
            <?php
            // Show first 10 breeds, rest in a scrollable container
            $visible_breeds = array_slice($filter_options['breeds'], 0, 10);
            $hidden_breeds = array_slice($filter_options['breeds'], 10);

            foreach ($visible_breeds as $breed) :
                $breed_slug = sanitize_title($breed);
                $is_selected = ($current_breed === $breed);
            ?>
            <div class="form-check filter-option">
                <input
                    class="form-check-input product-filter-checkbox"
                    type="checkbox"
                    value="<?php echo esc_attr($breed); ?>"
                    id="breed-<?php echo esc_attr($breed_slug); ?>"
                    data-filter-type="breed"
                    <?php checked($is_selected); ?>
                >
                <label class="form-check-label" for="breed-<?php echo esc_attr($breed_slug); ?>">
                    <?php echo esc_html($breed); ?>
                </label>
            </div>
            <?php endforeach; ?>

            <?php if (!empty($hidden_breeds)) : ?>
            <div class="more-breeds-container" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease;">
                <?php foreach ($hidden_breeds as $breed) :
                    $breed_slug = sanitize_title($breed);
                    $is_selected = ($current_breed === $breed);
                ?>
                <div class="form-check filter-option">
                    <input
                        class="form-check-input product-filter-checkbox"
                        type="checkbox"
                        value="<?php echo esc_attr($breed); ?>"
                        id="breed-<?php echo esc_attr($breed_slug); ?>"
                        data-filter-type="breed"
                        <?php checked($is_selected); ?>
                    >
                    <label class="form-check-label" for="breed-<?php echo esc_attr($breed_slug); ?>">
                        <?php echo esc_html($breed); ?>
                    </label>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn btn-sm btn-link p-0 show-more-breeds mt-2">
                <i class="fas fa-chevron-down me-1"></i>Show More
            </button>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="filter-results-info mt-4 p-3 bg-light rounded" style="display: none;">
        <div class="d-flex align-items-center justify-content-between">
            <span class="text-muted">
                <i class="fas fa-paw me-2"></i>
                <span class="results-count">0</span> pets found
            </span>
        </div>
    </div>
</div>

<style>
/* Custom Sidebar Styles - Using existing theme styles */
.woo-custom-sidebar {
    padding: 1.5rem;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
}

.woo-custom-sidebar .widget-title {
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--color-primary-dark-grey, #3D5155);
    margin-bottom: 1rem;
}

.woo-custom-sidebar .sidebar-header .widget-title {
    font-size: 1.3rem;
    margin-bottom: 0.5rem;
}

.filter-widget {
    padding-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.filter-widget:last-child {
    border-bottom: none;
}

.filter-options {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-option {
    padding: 0.5rem;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.filter-option:hover {
    background-color: #f8f9fa;
}

.filter-option .form-check-input {
    cursor: pointer;
}

.filter-option .form-check-input:checked {
    background-color: var(--color-primary-dark-teal, #3D5155);
    border-color: var(--color-primary-dark-teal, #3D5155);
}

.filter-option .form-check-label {
    cursor: pointer;
    font-weight: 500;
    color: #495057;
}

.show-more-breeds {
    color: var(--color-primary-dark-teal, #3D5155);
    text-decoration: none;
    font-weight: 600;
}

.show-more-breeds:hover {
    text-decoration: underline;
}

.clear-all-filters {
    transition: all 0.2s ease;
}

.clear-all-filters:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: #fff !important;
}

/* Mobile responsive */
@media (max-width: 991px) {
    .woo-custom-sidebar {
        padding: 1rem;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Show more breeds toggle
    $('.show-more-breeds').on('click', function() {
        const $btn = $(this);
        const $container = $('.more-breeds-container');
        const isExpanded = $container.css('max-height') !== '0px';

        if (isExpanded) {
            $container.css('max-height', '0');
            $btn.html('<i class="fas fa-chevron-down me-1"></i>Show More');
        } else {
            $container.css('max-height', $container[0].scrollHeight + 'px');
            $btn.html('<i class="fas fa-chevron-up me-1"></i>Show Less');
        }
    });
});
</script>
