/**
 * Custom Product Filters - Gender, Breed, Location
 * Fast AJAX filtering with pagination support
 */

(function($) {
    'use strict';

    console.log('[Custom Filters] Script loaded');

    // Fix for lazy loading images in blog posts
    function fixLazyImages() {
        const lazyImages = document.querySelectorAll('img[loading="lazy"]');
        lazyImages.forEach(img => {
            if (img.complete) {
                img.classList.add('loaded');
            } else {
                img.addEventListener('load', function() {
                    this.classList.add('loaded');
                });
            }
        });
    }

    // Run on page load
    $(document).ready(function() {
        fixLazyImages();
    });

    // Run when images are loaded
    $(window).on('load', function() {
        fixLazyImages();
    });

    // State management
    let isFiltering = false;
    let currentPage = 1;
    let maxPages = customFilterParams.max_pages || 1;
    let activeFilters = {
        gender: '',
        breed: '',
        location: ''
    };
    
    // Expose activeFilters globally so infinite scroll can check it
    window.activeFilters = activeFilters;

        // Initialize filters from URL on page load
    function initializeFiltersFromURL() {
        const urlParams = new URLSearchParams(window.location.search);

        activeFilters.gender = urlParams.get('filter_gender') || '';
        activeFilters.breed = urlParams.get('filter_breed') || '';
        // Check both 'filter_location' and 'location' parameters for backward compatibility
        activeFilters.location = urlParams.get('filter_location') || urlParams.get('location') || '';

        // Normalize location value (case-insensitive)
        if (activeFilters.location) {
            const locationLower = activeFilters.location.toLowerCase().trim();
            if (locationLower === 'indianapolis') {
                activeFilters.location = 'Indianapolis';
            } else if (locationLower === 'schererville') {
                activeFilters.location = 'Schererville';
            }
        }

        // Update location checkboxes based on URL parameter
        // IMPORTANT: Only update if checkboxes exist (sidebar might not be loaded yet)
        const $locationCheckboxes = $('.location-checkbox');
        if ($locationCheckboxes.length > 0) {
            console.log('[Custom Filters] Found', $locationCheckboxes.length, 'location checkboxes to sync');
            if (!activeFilters.location) {
                // No location filter - check all location checkboxes (default state)
                $locationCheckboxes.each(function() {
                    const $checkbox = $(this);
                    $checkbox.prop('checked', true);
                    $checkbox.attr('checked', 'checked');
                });
                console.log('[Custom Filters] No location filter - all checkboxes checked');
            } else {
                // Location filter active - check only the matching checkbox, uncheck others
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
                    const shouldBeChecked = (checkboxLocation.toLowerCase() === activeFilters.location.toLowerCase());
                    
                    console.log('[Custom Filters] Comparing checkbox:', checkboxValue, '->', checkboxLocation, 'with filter:', activeFilters.location, '-> match:', shouldBeChecked);
                    
                    if (shouldBeChecked) {
                        // Use multiple methods to ensure checkbox is checked
                        $checkbox.prop('checked', true);
                        $checkbox.attr('checked', 'checked');
                        if ($checkbox[0]) {
                            $checkbox[0].checked = true; // Direct DOM manipulation
                        }
                        console.log('[Custom Filters] ✓ Checked:', checkboxValue);
                    } else {
                        // Use multiple methods to ensure checkbox is unchecked
                        $checkbox.prop('checked', false);
                        $checkbox.removeAttr('checked');
                        if ($checkbox[0]) {
                            $checkbox[0].checked = false; // Direct DOM manipulation
                        }
                        console.log('[Custom Filters] ✗ Unchecked:', checkboxValue);
                    }
                });
                console.log('[Custom Filters] Location filter active:', activeFilters.location, '- only matching checkbox checked');
            }
        } else {
            console.log('[Custom Filters] No location checkboxes found yet');
        }
        
        // Update global reference
        window.activeFilters = activeFilters;

        console.log('[Custom Filters] Initialized from URL:', activeFilters);
    }

    // Update URL without page reload
    function updateURL() {
        const url = new URL(window.location.href);

        // Remove old filter params
        url.searchParams.delete('filter_gender');
        url.searchParams.delete('filter_breed');
        url.searchParams.delete('filter_location');
        url.searchParams.delete('location'); // Also remove 'location' param
        url.searchParams.delete('paged');

        // Add active filters
        if (activeFilters.gender) {
            url.searchParams.set('filter_gender', activeFilters.gender);
        }
        if (activeFilters.breed) {
            url.searchParams.set('filter_breed', activeFilters.breed);
        }
        if (activeFilters.location) {
            // Use 'location' parameter to match dropdown behavior
            url.searchParams.set('location', activeFilters.location);
        }

        window.history.pushState({}, '', url);
        console.log('[Custom Filters] URL updated to:', url.toString());
    }

    // Get current category from URL
    function getCurrentCategory() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('product_cat') || '';
    }

    // Main filter function
    function filterProducts(page = 1) {
        if (isFiltering) {
            console.log('[Custom Filters] Already filtering, skipping');
            return;
        }

        console.log('[Custom Filters] Starting filter with:', activeFilters, 'Page:', page);
        isFiltering = true;
        currentPage = page;

        // Find products container
        const $productsContainer = $('.woocommerce .products, ul.products, .row.row-cols-2').first();

        if (!$productsContainer.length) {
            console.error('[Custom Filters] Products container not found');
            isFiltering = false;
            return;
        }

        // Show loading state
        showLoadingState($productsContainer);

        // Make AJAX request
        const ajaxData = {
            action: 'custom_filter_products',
            nonce: customFilterParams.nonce,
            gender: activeFilters.gender,
            breed: activeFilters.breed,
            location: activeFilters.location,
            category: getCurrentCategory(),
            page: page
        };
        
        console.log('[Custom Filters] Sending AJAX request with data:', ajaxData);
        console.log('[Custom Filters] Breed filter value:', activeFilters.breed);
        console.log('[Custom Filters] Breed filter type:', typeof activeFilters.breed);
        
        $.ajax({
            url: customFilterParams.ajaxurl,
            type: 'POST',
            data: ajaxData,
            success: function(response) {
                console.log('[Custom Filters] Response received:', response);

                if (response.success && response.data) {
                    // Reset scroll tracking flag so infinite scroll won't auto-trigger immediately
                    // But allow infinite scroll to work with filters
                    if (window.infiniteScrollUserHasScrolled !== undefined) {
                        window.infiniteScrollUserHasScrolled = false;
                    }
                    
                    // Note: Infinite scroll will now work WITH filters
                    // It will pass filter parameters to the AJAX call
                    
                    // Replace products
                    $productsContainer.html(response.data.html);

                    // Update pagination
                    maxPages = response.data.max_pages || 1;
                    updatePagination(response.data.count, response.data.max_pages);

                    // Update results count
                    updateResultsInfo(response.data.count);

                    // Update count in offcanvas header
                    $('.pets-count-display').text(response.data.count);

                    // Update URL
                    updateURL();

                    // Mark that we're doing a programmatic scroll (not user scroll)
                    window.isProgrammaticScroll = true;
                    
                    // Scroll to top of products smoothly, but only if container is below viewport
                    const containerTop = $productsContainer.offset().top;
                    const viewportTop = $(window).scrollTop();
                    const viewportHeight = $(window).height();
                    
                    // Only scroll if container is significantly below current viewport
                    if (containerTop > viewportTop + viewportHeight / 2) {
                        $('html, body').animate({
                            scrollTop: containerTop - 150
                        }, 400, function() {
                            // Clear flag after animation completes
                            setTimeout(function() {
                                window.isProgrammaticScroll = false;
                            }, 100);
                        });
                    } else {
                        // Just scroll to top if we're already near the container
                        $('html, body').animate({
                            scrollTop: 0
                        }, 400, function() {
                            setTimeout(function() {
                                window.isProgrammaticScroll = false;
                            }, 100);
                        });
                    }

                    console.log('[Custom Filters] Products updated successfully');
                } else {
                    showError($productsContainer);
                }

                hideLoadingState($productsContainer);
                isFiltering = false;
            },
            error: function(xhr, status, error) {
                console.error('[Custom Filters] AJAX error:', error);
                showError($productsContainer);
                hideLoadingState($productsContainer);
                isFiltering = false;
            }
        });
    }

    // Show loading state
    function showLoadingState($container) {
        $container.css('opacity', '0.5');

        let $loader = $('.custom-filter-loader');
        if (!$loader.length) {
            $loader = $('<div class="custom-filter-loader text-center py-5">' +
                '<div class="spinner-border text-primary" role="status">' +
                '<span class="visually-hidden">Loading...</span>' +
                '</div>' +
                '<p class="mt-3 text-muted"><i class="fas fa-paw me-2"></i>Finding your perfect pet...</p>' +
                '</div>');
            $container.before($loader);
        } else {
            $loader.show();
        }
    }

    // Hide loading state
    function hideLoadingState($container) {
        $container.css('opacity', '1');
        $('.custom-filter-loader').hide();
    }

    // Show error
    function showError($container) {
        $container.html('<div class="col-12"><div class="alert alert-danger text-center">' +
            '<i class="fas fa-exclamation-triangle me-2"></i>' +
            'An error occurred while filtering. Please refresh the page and try again.' +
            '</div></div>');
    }

    // Update results info
    function updateResultsInfo(count) {
        const $resultsInfo = $('.filter-results-info');
        if ($resultsInfo.length) {
            $resultsInfo.find('.results-count').text(count);
            $resultsInfo.show();
        }
    }

    // Update pagination
    function updatePagination(totalResults, totalPages) {
        // Remove existing pagination
        $('.woocommerce-pagination').remove();

        if (totalPages > 1) {
            // Create new pagination
            const $productsContainer = $('.woocommerce .products, ul.products, .row.row-cols-2').first();
            const $pagination = createPaginationHTML(totalPages, currentPage);
            $productsContainer.after($pagination);
        }
    }

    // Create pagination HTML
    function createPaginationHTML(totalPages, current) {
        let html = '<nav class="woocommerce-pagination"><ul class="page-numbers">';

        // Previous button
        if (current > 1) {
            html += '<li><a class="prev page-numbers filter-page-link" data-page="' + (current - 1) + '" href="#">Previous</a></li>';
        }

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            if (i === current) {
                html += '<li><span aria-current="page" class="page-numbers current">' + i + '</span></li>';
            } else {
                html += '<li><a class="page-numbers filter-page-link" data-page="' + i + '" href="#">' + i + '</a></li>';
            }
        }

        // Next button
        if (current < totalPages) {
            html += '<li><a class="next page-numbers filter-page-link" data-page="' + (current + 1) + '" href="#">Next</a></li>';
        }

        html += '</ul></nav>';
        return $(html);
    }

    // Handle checkbox changes
    $(document).on('change', '.product-filter-checkbox', function() {
        const $checkbox = $(this);
        const filterType = $checkbox.data('filter-type');
        const value = $checkbox.val();
        const isChecked = $checkbox.is(':checked');

        console.log('[Custom Filters] Checkbox changed:', filterType, value, isChecked);

        // Special handling for location - allow multiple checkboxes
        if (filterType === 'location') {
            const $allLocationCheckboxes = $('.location-checkbox');
            const checkedLocations = $allLocationCheckboxes.filter(':checked');

            // If both are checked or none are checked, treat as "all locations" (no filter)
            if (checkedLocations.length === 0 || checkedLocations.length === $allLocationCheckboxes.length) {
                activeFilters.location = '';
                // Check all if none are checked
                if (checkedLocations.length === 0) {
                    $allLocationCheckboxes.prop('checked', true);
                }
            } else {
                // Only one location is checked - extract location name from checkbox value
                let checkboxValue = checkedLocations.first().val();
                // Extract location name (remove "Happiness Is Pets " prefix if present)
                if (checkboxValue && checkboxValue.includes('Happiness Is Pets ')) {
                    checkboxValue = checkboxValue.replace('Happiness Is Pets ', '').trim();
                }
                activeFilters.location = checkboxValue;
            }
            
            // Sync location dropdown with checkbox selection
            const $dropdown = $('#locationDropdown');
            if ($dropdown.length) {
                // Dropdown values are "Indianapolis" or "Schererville", not the full text
                $dropdown.val(activeFilters.location || '');
                console.log('[Custom Filters] Synced dropdown to:', activeFilters.location || 'All Locations');
            }
        } else {
            // For gender and breed, only allow one selection per filter type
            $('.product-filter-checkbox[data-filter-type="' + filterType + '"]').not($checkbox).prop('checked', false);

            // Update active filters
            if (isChecked) {
                activeFilters[filterType] = value;
            } else {
                activeFilters[filterType] = '';
            }
        }
        
        // Update global reference
        window.activeFilters = activeFilters;

        console.log('[Custom Filters] Active filters updated:', activeFilters);

        // Trigger filter
        filterProducts(1);
    });

    // Handle clear all filters
    $(document).on('click', '.clear-all-filters', function(e) {
        e.preventDefault();
        console.log('[Custom Filters] Clearing all filters');

        // Uncheck all non-location checkboxes
        $('.product-filter-checkbox').not('.location-checkbox').prop('checked', false);

        // Check all location checkboxes (default state)
        $('.location-checkbox').prop('checked', true);

        // Reset active filters
        activeFilters = {
            gender: '',
            breed: '',
            location: ''
        };
        
        // Update global reference
        window.activeFilters = activeFilters;

        // Reload without filters
        window.location.href = window.location.pathname + (getCurrentCategory() ? '?product_cat=' + getCurrentCategory() : '');
    });

    // Handle pagination clicks
    $(document).on('click', '.filter-page-link', function(e) {
        e.preventDefault();
        const page = parseInt($(this).data('page'));
        console.log('[Custom Filters] Pagination clicked:', page);
        filterProducts(page);
    });

    // Optional: Load More button functionality
    $(document).on('click', '.load-more-products', function(e) {
        e.preventDefault();
        const $button = $(this);
        const nextPage = currentPage + 1;

        if (nextPage > maxPages || isFiltering) {
            return;
        }

        console.log('[Custom Filters] Load More clicked for page:', nextPage);

        $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');

        isFiltering = true;

        $.ajax({
            url: customFilterParams.ajaxurl,
            type: 'POST',
            data: {
                action: 'custom_filter_products',
                nonce: customFilterParams.nonce,
                gender: activeFilters.gender,
                breed: activeFilters.breed,
                location: activeFilters.location,
                category: getCurrentCategory(),
                page: nextPage
            },
            success: function(response) {
                if (response.success && response.data && response.data.html) {
                    // Append new products
                    const $productsContainer = $('.woocommerce .products, ul.products, .row.row-cols-2').first();
                    $productsContainer.append(response.data.html);

                    currentPage = nextPage;
                    maxPages = response.data.max_pages || maxPages;

                    // Hide button if no more pages
                    if (currentPage >= maxPages) {
                        $button.replaceWith('<p class="text-center text-muted"><i class="fas fa-check me-2"></i>All pets loaded!</p>');
                    } else {
                        $button.prop('disabled', false).html('<i class="fas fa-plus me-2"></i>Load More');
                    }
                }
                isFiltering = false;
            },
            error: function() {
                $button.prop('disabled', false).html('<i class="fas fa-plus me-2"></i>Load More');
                isFiltering = false;
            }
        });
    });

    // Auto-close offcanvas after filter selection (mobile)
    $(document).on('change', '.product-filter-checkbox', function() {
        // Close offcanvas on mobile after a short delay to show selection
        if (window.innerWidth < 992) {
            setTimeout(function() {
                const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('petsFilterOffcanvas'));
                if (offcanvas) {
                    offcanvas.hide();
                }
            }, 500);
        }
    });

    // Sync location checkboxes when offcanvas opens
    $(document).on('shown.bs.offcanvas', '#petsFilterOffcanvas', function() {
        console.log('[Custom Filters] Offcanvas opened, syncing location checkboxes from URL');
        
        // Get location from URL (check both 'location' and 'filter_location')
        const urlParams = new URLSearchParams(window.location.search);
        let locationFromURL = urlParams.get('filter_location') || urlParams.get('location') || '';
        
        // Normalize location value (case-insensitive)
        if (locationFromURL) {
            const locationLower = locationFromURL.toLowerCase().trim();
            if (locationLower === 'indianapolis') {
                locationFromURL = 'Indianapolis';
            } else if (locationLower === 'schererville') {
                locationFromURL = 'Schererville';
            }
        }
        
        // Update location checkboxes - IMPORTANT: Only check the matching one, uncheck others
        const $locationCheckboxes = $('.location-checkbox');
        
        if (!locationFromURL || locationFromURL === '') {
            // No location filter - check all location checkboxes (default state)
            $locationCheckboxes.prop('checked', true);
            console.log('[Custom Filters] No location filter - all locations checked');
        } else {
            // Location filter active - check ONLY the matching checkbox, UNCHECK others
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
                const shouldBeChecked = (checkboxLocation.toLowerCase() === locationFromURL.toLowerCase());
                
                console.log('[Custom Filters] Comparing checkbox:', checkboxValue, '->', checkboxLocation, 'with location:', locationFromURL, '-> match:', shouldBeChecked);
                
                if (shouldBeChecked) {
                    $checkbox.prop('checked', true);
                    $checkbox.attr('checked', 'checked');
                    if ($checkbox[0]) {
                        $checkbox[0].checked = true;
                    }
                    console.log('[Custom Filters] ✓ Checked checkbox for:', checkboxValue);
                } else {
                    $checkbox.prop('checked', false);
                    $checkbox.removeAttr('checked');
                    if ($checkbox[0]) {
                        $checkbox[0].checked = false;
                    }
                    console.log('[Custom Filters] ✗ Unchecked checkbox for:', checkboxValue);
                }
            });
            console.log('[Custom Filters] Location filter active:', locationFromURL, '- only matching checkbox should be checked');
        }
        
        // Update activeFilters to match
        activeFilters.location = locationFromURL;
        window.activeFilters = activeFilters;
    });

    // Initialize on page load - use delay to ensure sidebar is rendered
    $(document).ready(function() {
        // Don't override PHP-set checkboxes immediately
        // Only sync if URL parameter doesn't match current checkbox state
        setTimeout(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const locationParam = urlParams.get('filter_location') || urlParams.get('location') || '';
            
            // Only initialize if we have a location parameter and checkboxes exist
            if (locationParam) {
                const $locationCheckboxes = $('.location-checkbox');
                if ($locationCheckboxes.length > 0) {
                    // Check if checkboxes are already correctly set by PHP
                    let needsSync = false;
                    const locationLower = locationParam.toLowerCase().trim();
                    let normalizedLocation = '';
                    if (locationLower === 'indianapolis') {
                        normalizedLocation = 'Indianapolis';
                    } else if (locationLower === 'schererville') {
                        normalizedLocation = 'Schererville';
                    }
                    
                    // Check if any checkbox is checked that shouldn't be, or if the right one isn't checked
                    $locationCheckboxes.each(function() {
                        const $checkbox = $(this);
                        const checkboxValue = $checkbox.val();
                        const isChecked = $checkbox.is(':checked');
                        
                        if (checkboxValue === normalizedLocation && !isChecked) {
                            needsSync = true;
                        } else if (checkboxValue !== normalizedLocation && isChecked) {
                            needsSync = true;
                        }
                    });
                    
                    if (needsSync) {
                        console.log('[Custom Filters] Checkboxes need sync, initializing from URL');
                        initializeFiltersFromURL();
                    } else {
                        console.log('[Custom Filters] Checkboxes already correctly set by PHP');
                        // Still update activeFilters
                        activeFilters.location = normalizedLocation;
                        window.activeFilters = activeFilters;
                    }
                } else {
                    // No checkboxes found yet, try to initialize anyway
                    initializeFiltersFromURL();
                }
            } else {
                // No location parameter, just initialize normally
                initializeFiltersFromURL();
            }
        }, 200);
    });

    console.log('[Custom Filters] Ready and initialized');

})(jQuery);
