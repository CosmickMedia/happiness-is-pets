/**
 * Location Filter for WooCommerce Products - AJAX Version
 */

(function($) {
    'use strict';

    console.log('[Location Filter] Script loaded');

    let isFiltering = false;

    // Handle location dropdown changes
    $(document).on('change', '#locationDropdown', function() {
        console.log('[Location Filter] Dropdown changed, isFiltering:', isFiltering);
        if (isFiltering) return;

        const selectedLocation = $(this).val();
        console.log('[Location Filter] Selected location:', selectedLocation);
        filterProductsByLocation(selectedLocation);
    });

    // Main filter function using AJAX
    function filterProductsByLocation(selectedLocation) {
        console.log('[Location Filter] filterProductsByLocation called with:', selectedLocation);
        isFiltering = true;

        // Find the products container
        const $productsContainer = $('.woocommerce .products, ul.products, .row.row-cols-2').first();
        console.log('[Location Filter] Products container found:', $productsContainer.length > 0, $productsContainer);

        if (!$productsContainer.length) {
            console.error('[Location Filter] Products container not found');
            isFiltering = false;
            return;
        }

        // Show loading state
        $productsContainer.css('opacity', '0.5');

        // Add loading spinner if not exists
        let $loader = $('.location-filter-loader');
        if (!$loader.length) {
            $loader = $('<div class="location-filter-loader text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-3">Filtering pets...</p></div>');
            $productsContainer.before($loader);
        } else {
            $loader.show();
        }

        // Get current category from URL
        const urlParams = new URLSearchParams(window.location.search);
        const category = urlParams.get('product_cat') || '';

        // Make AJAX request
        console.log('[Location Filter] Making AJAX request with data:', {
            action: 'filter_products_by_location',
            location: selectedLocation,
            category: category
        });

        $.ajax({
            url: locationFilterParams.ajaxurl,
            type: 'POST',
            data: {
                action: 'filter_products_by_location',
                location: selectedLocation,
                category: category
            },
            success: function(response) {
                console.log('[Location Filter] AJAX response:', response);
                if (response.success && response.data.html) {
                    // Replace products
                    $productsContainer.html(response.data.html);

                    // Update URL without reload
                    const newUrl = new URL(window.location.href);
                    if (selectedLocation) {
                        newUrl.searchParams.set('location', selectedLocation);
                    } else {
                        newUrl.searchParams.delete('location');
                    }
                    window.history.pushState({}, '', newUrl);

                    // Scroll to top of products
                    $('html, body').animate({
                        scrollTop: $productsContainer.offset().top - 100
                    }, 400);
                } else {
                    // Show no results message
                    $productsContainer.html('<div class="col-12"><p class="woocommerce-info">No pets found at this location.</p></div>');
                }

                $loader.hide();
                $productsContainer.css('opacity', '1');
                isFiltering = false;
            },
            error: function(xhr, status, error) {
                console.error('Filter error:', error);
                alert('An error occurred while filtering. Please refresh the page and try again.');
                $loader.hide();
                $productsContainer.css('opacity', '1');
                isFiltering = false;
            }
        });
    }

    // Handle location checkbox changes (for sidebar widget if used)
    $(document).on('change', '.location-filter-checkbox', function() {
        const $checkbox = $(this);
        const selectedLocation = $checkbox.data('location');
        const isChecked = $checkbox.is(':checked');

        // Uncheck other location checkboxes (only one location at a time)
        $('.location-filter-checkbox').not($checkbox).prop('checked', false);

        if (isChecked) {
            filterProductsByLocation(selectedLocation);
        } else {
            filterProductsByLocation('');
        }
    });

    // Handle clear filter button
    $(document).on('click', '.clear-location-filter', function() {
        $('#locationDropdown').val('').trigger('change');
        $('.location-filter-checkbox').prop('checked', false);
    });

})(jQuery);
