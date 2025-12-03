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
        
        // Sync location checkboxes in sidebar to match dropdown selection
        syncLocationCheckboxes(selectedLocation);
        
        filterProductsByLocation(selectedLocation);
    });
    
    // Function to sync location checkboxes with dropdown selection
    function syncLocationCheckboxes(selectedLocation) {
        const $locationCheckboxes = $('.location-checkbox');
        
        if ($locationCheckboxes.length === 0) {
            console.warn('[Location Filter] No location checkboxes found to sync');
            return false;
        }
        
        // Normalize the location value
        let normalizedLocation = '';
        if (selectedLocation) {
            const locationLower = String(selectedLocation).toLowerCase().trim();
            if (locationLower === 'indianapolis') {
                normalizedLocation = 'Indianapolis';
            } else if (locationLower === 'schererville') {
                normalizedLocation = 'Schererville';
            } else {
                normalizedLocation = selectedLocation; // Use as-is if already normalized
            }
        }
        
        console.log('[Location Filter] Syncing checkboxes with location:', normalizedLocation || 'All Locations');
        console.log('[Location Filter] Found', $locationCheckboxes.length, 'checkboxes');
        
        // Log current state before sync
        $locationCheckboxes.each(function() {
            const $cb = $(this);
            console.log('[Location Filter] BEFORE: Checkbox', $cb.val(), 'is', $cb.is(':checked') ? 'CHECKED' : 'UNCHECKED');
        });
        
        if (!normalizedLocation || normalizedLocation === '') {
            // No location selected - check all location checkboxes (default state)
            $locationCheckboxes.each(function() {
                const $checkbox = $(this);
                $checkbox.prop('checked', true);
                $checkbox.attr('checked', 'checked');
            });
            console.log('[Location Filter] No location selected - all', $locationCheckboxes.length, 'checkboxes checked');
        } else {
            // Location selected - check only the matching checkbox, uncheck others
            let checkedCount = 0;
            let uncheckedCount = 0;
            $locationCheckboxes.each(function() {
                const $checkbox = $(this);
                const checkboxValue = $checkbox.val();
                
                // Checkbox value might be "Happiness Is Pets Indianapolis" or just "Indianapolis"
                // Extract location name from checkbox value (remove "Happiness Is Pets " prefix if present)
                let checkboxLocation = checkboxValue;
                if (checkboxValue.includes('Happiness Is Pets ')) {
                    checkboxLocation = checkboxValue.replace('Happiness Is Pets ', '').trim();
                }
                
                // Compare normalized location with checkbox location (case-insensitive)
                const shouldBeChecked = (checkboxLocation.toLowerCase() === normalizedLocation.toLowerCase());
                
                console.log('[Location Filter] Comparing checkbox:', checkboxValue, '->', checkboxLocation, 'with normalized:', normalizedLocation, '-> match:', shouldBeChecked);
                
                // Force set using multiple methods to ensure it sticks
                if (shouldBeChecked) {
                    // Use multiple methods to ensure checkbox is checked
                    $checkbox.prop('checked', true);
                    $checkbox.attr('checked', 'checked');
                    if ($checkbox[0]) {
                        $checkbox[0].checked = true; // Direct DOM manipulation
                    }
                    checkedCount++;
                    console.log('[Location Filter] ✓ FORCE CHECKED:', checkboxValue, 'using prop, attr, and direct DOM');
                } else {
                    // Use multiple methods to ensure checkbox is unchecked
                    $checkbox.prop('checked', false);
                    $checkbox.removeAttr('checked');
                    if ($checkbox[0]) {
                        $checkbox[0].checked = false; // Direct DOM manipulation
                    }
                    uncheckedCount++;
                    console.log('[Location Filter] ✗ FORCE UNCHECKED:', checkboxValue, 'using prop, attr, and direct DOM');
                }
            });
            console.log('[Location Filter] Location selected:', normalizedLocation, '-', checkedCount, 'checked,', uncheckedCount, 'unchecked');
        }
        
        // Log state after sync
        $locationCheckboxes.each(function() {
            const $cb = $(this);
            console.log('[Location Filter] AFTER: Checkbox', $cb.val(), 'is', $cb.is(':checked') ? 'CHECKED' : 'UNCHECKED');
        });
        
        // Also update activeFilters if it exists (for custom-filters.js compatibility)
        if (typeof window.activeFilters !== 'undefined') {
            window.activeFilters.location = normalizedLocation || '';
        }
        
        return true;
    }

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

    // Get normalized location from URL
    function getLocationFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        const locationParam = urlParams.get('location') || urlParams.get('filter_location') || '';
        
        let normalizedLocation = '';
        if (locationParam) {
            // Normalize location value (case-insensitive)
            const locationLower = locationParam.toLowerCase().trim();
            
            if (locationLower === 'indianapolis') {
                normalizedLocation = 'Indianapolis';
            } else if (locationLower === 'schererville') {
                normalizedLocation = 'Schererville';
            } else {
                normalizedLocation = locationParam; // Use as-is if already normalized
            }
        }
        
        return normalizedLocation;
    }

    // Initialize dropdown from URL on page load and sync checkboxes
    $(document).ready(function() {
        // Function to initialize from URL
        function initializeFromURL() {
            const normalizedLocation = getLocationFromURL();
            
            // Set dropdown value if we found a match
            const $dropdown = $('#locationDropdown');
            if ($dropdown.length) {
                if (normalizedLocation && $dropdown.val() !== normalizedLocation) {
                    $dropdown.val(normalizedLocation);
                    console.log('[Location Filter] Set dropdown to:', normalizedLocation, 'from URL parameter');
                } else if (!normalizedLocation && $dropdown.val() !== '') {
                    $dropdown.val('');
                    console.log('[Location Filter] Set dropdown to: All Locations');
                }
            }
            
            // Sync checkboxes with dropdown/URL on page load
            // Try multiple times to ensure sidebar is rendered
            const $locationCheckboxes = $('.location-checkbox');
            console.log('[Location Filter] Looking for checkboxes, found:', $locationCheckboxes.length);
            
            if ($locationCheckboxes.length > 0) {
                const synced = syncLocationCheckboxes(normalizedLocation);
                if (synced) {
                    console.log('[Location Filter] ✓ Successfully initialized checkboxes from URL');
                } else {
                    console.warn('[Location Filter] ✗ Failed to sync checkboxes');
                }
            } else {
                console.warn('[Location Filter] No checkboxes found, will retry');
                // Retry after a delay if checkboxes not found
                setTimeout(function() {
                    const $retryCheckboxes = $('.location-checkbox');
                    console.log('[Location Filter] Retry: Looking for checkboxes, found:', $retryCheckboxes.length);
                    if ($retryCheckboxes.length > 0) {
                        const synced = syncLocationCheckboxes(normalizedLocation);
                        if (synced) {
                            console.log('[Location Filter] ✓ Successfully initialized checkboxes from URL (retry)');
                        }
                    } else {
                        console.warn('[Location Filter] ✗ Location checkboxes not found after retry');
                    }
                }, 500);
            }
        }
        
        // Run immediately
        initializeFromURL();
        
        // Also run after a delay to catch late-rendered elements (only once)
        setTimeout(initializeFromURL, 300);
    });
    
    // Also sync when offcanvas opens (checkboxes become visible)
    $(document).on('shown.bs.offcanvas', '#petsFilterOffcanvas', function() {
        console.log('[Location Filter] Offcanvas opened, syncing checkboxes from URL');
        const normalizedLocation = getLocationFromURL();
        
        // Small delay to ensure offcanvas content is fully rendered
        setTimeout(function() {
            syncLocationCheckboxes(normalizedLocation);
        }, 50);
    });
    
    // Also sync when offcanvas is about to show (before animation)
    $(document).on('show.bs.offcanvas', '#petsFilterOffcanvas', function() {
        console.log('[Location Filter] Offcanvas showing, preparing to sync checkboxes');
        const normalizedLocation = getLocationFromURL();
        
        // Sync immediately when offcanvas starts showing
        setTimeout(function() {
            syncLocationCheckboxes(normalizedLocation);
        }, 10);
    });
    
    // Force sync on window load (after everything is loaded)
    $(window).on('load', function() {
        console.log('[Location Filter] Window loaded, forcing checkbox sync');
        const normalizedLocation = getLocationFromURL();
        setTimeout(function() {
            syncLocationCheckboxes(normalizedLocation);
        }, 100);
    });
    
    // Also expose sync function globally for manual triggering if needed
    window.syncLocationCheckboxes = syncLocationCheckboxes;

})(jQuery);
