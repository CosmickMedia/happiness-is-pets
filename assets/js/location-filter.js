/**
 * Location Filter for WooCommerce Products - AJAX Version
 */

(function($) {
    'use strict';

    let isFiltering = false;

    // Handle location dropdown changes
    $(document).on('change', '#locationDropdown', function() {
        if (isFiltering) return;

        const selectedLocation = $(this).val();
        syncLocationCheckboxes(selectedLocation);
        filterProductsByLocation(selectedLocation);
    });

    // Sync location checkboxes with dropdown selection
    function syncLocationCheckboxes(selectedLocation) {
        const $locationCheckboxes = $('.location-checkbox');
        if ($locationCheckboxes.length === 0) return false;

        // Normalize the location value
        let normalizedLocation = '';
        if (selectedLocation) {
            const locationLower = String(selectedLocation).toLowerCase().trim();
            if (locationLower === 'indianapolis') {
                normalizedLocation = 'Indianapolis';
            } else if (locationLower === 'schererville') {
                normalizedLocation = 'Schererville';
            } else {
                normalizedLocation = selectedLocation;
            }
        }

        if (!normalizedLocation) {
            $locationCheckboxes.prop('checked', true);
        } else {
            $locationCheckboxes.each(function() {
                let checkboxLocation = this.value;
                if (checkboxLocation.includes('Happiness Is Pets ')) {
                    checkboxLocation = checkboxLocation.replace('Happiness Is Pets ', '').trim();
                }
                this.checked = (checkboxLocation.toLowerCase() === normalizedLocation.toLowerCase());
            });
        }

        if (typeof window.activeFilters !== 'undefined') {
            window.activeFilters.location = normalizedLocation || '';
        }

        return true;
    }

    // Main filter function using AJAX
    function filterProductsByLocation(selectedLocation) {
        isFiltering = true;

        const $productsContainer = $('.woocommerce .products, ul.products, .row.row-cols-2').first();
        if (!$productsContainer.length) {
            isFiltering = false;
            return;
        }

        $productsContainer.css('opacity', '0.5');

        let $loader = $('.location-filter-loader');
        if (!$loader.length) {
            $loader = $('<div class="location-filter-loader text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-3">Filtering pets...</p></div>');
            $productsContainer.before($loader);
        } else {
            $loader.show();
        }

        const urlParams = new URLSearchParams(window.location.search);
        const category = urlParams.get('product_cat') || '';

        $.ajax({
            url: locationFilterParams.ajaxurl,
            type: 'POST',
            data: {
                action: 'filter_products_by_location',
                location: selectedLocation,
                category: category
            },
            success: function(response) {
                if (response.success && response.data.html) {
                    $productsContainer.html(response.data.html);

                    const newUrl = new URL(window.location.href);
                    if (selectedLocation) {
                        newUrl.searchParams.set('location', selectedLocation);
                    } else {
                        newUrl.searchParams.delete('location');
                    }
                    window.history.pushState({}, '', newUrl);

                    $('html, body').animate({
                        scrollTop: $productsContainer.offset().top - 100
                    }, 400);
                } else {
                    $productsContainer.html('<div class="col-12"><p class="woocommerce-info">No pets found at this location.</p></div>');
                }

                $loader.hide();
                $productsContainer.css('opacity', '1');
                isFiltering = false;
            },
            error: function() {
                alert('An error occurred while filtering. Please refresh the page and try again.');
                $loader.hide();
                $productsContainer.css('opacity', '1');
                isFiltering = false;
            }
        });
    }

    // Handle location checkbox changes (for sidebar widget)
    $(document).on('change', '.location-filter-checkbox', function() {
        const $checkbox = $(this);
        const selectedLocation = $checkbox.data('location');
        const isChecked = $checkbox.is(':checked');

        $('.location-filter-checkbox').not($checkbox).prop('checked', false);

        filterProductsByLocation(isChecked ? selectedLocation : '');
    });

    // Handle clear filter button
    $(document).on('click', '.clear-location-filter', function() {
        $('#locationDropdown').val('').trigger('change');
        $('.location-filter-checkbox').prop('checked', false);
    });

    // Get normalized location from URL
    function getLocationFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        const locationParam = urlParams.get('location') || urlParams.get('filter_location') || '';

        if (!locationParam) return '';

        const locationLower = locationParam.toLowerCase().trim();
        if (locationLower === 'indianapolis') return 'Indianapolis';
        if (locationLower === 'schererville') return 'Schererville';
        return locationParam;
    }

    // Initialize dropdown from URL on page load and sync checkboxes (once)
    $(document).ready(function() {
        const normalizedLocation = getLocationFromURL();

        // Set dropdown value
        const $dropdown = $('#locationDropdown');
        if ($dropdown.length) {
            if (normalizedLocation && $dropdown.val() !== normalizedLocation) {
                $dropdown.val(normalizedLocation);
            } else if (!normalizedLocation && $dropdown.val() !== '') {
                $dropdown.val('');
            }
        }

        // Sync checkboxes - single attempt with one retry
        const $locationCheckboxes = $('.location-checkbox');
        if ($locationCheckboxes.length > 0) {
            syncLocationCheckboxes(normalizedLocation);
        } else {
            // Retry once after sidebar renders
            setTimeout(function() {
                if ($('.location-checkbox').length > 0) {
                    syncLocationCheckboxes(normalizedLocation);
                }
            }, 500);
        }
    });

    // Sync when offcanvas opens
    $(document).on('shown.bs.offcanvas', '#petsFilterOffcanvas', function() {
        syncLocationCheckboxes(getLocationFromURL());
    });

    // Expose sync function globally
    window.syncLocationCheckboxes = syncLocationCheckboxes;

})(jQuery);
