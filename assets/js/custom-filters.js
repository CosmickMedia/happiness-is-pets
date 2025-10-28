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

    // Initialize filters from URL on page load
    function initializeFiltersFromURL() {
        const urlParams = new URLSearchParams(window.location.search);

        activeFilters.gender = urlParams.get('filter_gender') || '';
        activeFilters.breed = urlParams.get('filter_breed') || '';
        activeFilters.location = urlParams.get('filter_location') || '';

        // Ensure both location checkboxes are checked if no location filter is active
        if (!activeFilters.location) {
            $('.location-checkbox').prop('checked', true);
        }

        console.log('[Custom Filters] Initialized from URL:', activeFilters);
    }

    // Update URL without page reload
    function updateURL() {
        const url = new URL(window.location.href);

        // Remove old filter params
        url.searchParams.delete('filter_gender');
        url.searchParams.delete('filter_breed');
        url.searchParams.delete('filter_location');
        url.searchParams.delete('paged');

        // Add active filters
        if (activeFilters.gender) {
            url.searchParams.set('filter_gender', activeFilters.gender);
        }
        if (activeFilters.breed) {
            url.searchParams.set('filter_breed', activeFilters.breed);
        }
        if (activeFilters.location) {
            url.searchParams.set('filter_location', activeFilters.location);
        }

        window.history.pushState({}, '', url);
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
                page: page
            },
            success: function(response) {
                console.log('[Custom Filters] Response received:', response);

                if (response.success && response.data) {
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

                    // Scroll to top of products smoothly
                    $('html, body').animate({
                        scrollTop: $productsContainer.offset().top - 150
                    }, 400);

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
                // Only one location is checked
                activeFilters.location = checkedLocations.first().val();
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

    // Initialize on page load
    initializeFiltersFromURL();

    console.log('[Custom Filters] Ready and initialized');

})(jQuery);
