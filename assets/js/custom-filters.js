/**
 * Custom Product Filters - Gender, Breed, Location
 * Fast AJAX filtering with pagination support
 */

(function($) {
    'use strict';

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
        activeFilters.location = urlParams.get('filter_location') || urlParams.get('location') || '';

        // Normalize location value
        if (activeFilters.location) {
            const locationLower = activeFilters.location.toLowerCase().trim();
            if (locationLower === 'indianapolis') {
                activeFilters.location = 'Indianapolis';
            } else if (locationLower === 'schererville') {
                activeFilters.location = 'Schererville';
            }
        }

        // Update location checkboxes based on URL parameter
        const $locationCheckboxes = $('.location-checkbox');
        if ($locationCheckboxes.length > 0) {
            if (!activeFilters.location) {
                $locationCheckboxes.prop('checked', true);
            } else {
                $locationCheckboxes.each(function() {
                    const checkboxValue = this.value;
                    let checkboxLocation = checkboxValue;
                    if (checkboxValue.includes('Happiness Is Pets ')) {
                        checkboxLocation = checkboxValue.replace('Happiness Is Pets ', '').trim();
                    }
                    const shouldBeChecked = (checkboxLocation.toLowerCase() === activeFilters.location.toLowerCase());
                    this.checked = shouldBeChecked;
                });
            }
        }

        window.activeFilters = activeFilters;
    }

    // Update URL without page reload
    function updateURL() {
        const url = new URL(window.location.href);

        url.searchParams.delete('filter_gender');
        url.searchParams.delete('filter_breed');
        url.searchParams.delete('filter_location');
        url.searchParams.delete('location');
        url.searchParams.delete('paged');

        if (activeFilters.gender) {
            url.searchParams.set('filter_gender', activeFilters.gender);
        }
        if (activeFilters.breed) {
            url.searchParams.set('filter_breed', activeFilters.breed);
        }
        if (activeFilters.location) {
            url.searchParams.set('location', activeFilters.location);
        }

        window.history.pushState({}, '', url);
    }

    // Get current category from URL
    function getCurrentCategory() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('product_cat') || '';
    }

    // Main filter function
    function filterProducts(page) {
        page = page || 1;
        if (isFiltering) return;

        isFiltering = true;
        currentPage = page;

        const $productsContainer = $('.woocommerce .products, ul.products, .row.row-cols-2').first();
        if (!$productsContainer.length) {
            isFiltering = false;
            return;
        }

        showLoadingState($productsContainer);

        // Reset infinite scroll product tracking on filter change
        if (typeof window.resetInfiniteScrollProducts === 'function') {
            window.resetInfiniteScrollProducts();
        }

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
                if (response.success && response.data) {
                    // Reset scroll tracking flag
                    if (window.infiniteScrollUserHasScrolled !== undefined) {
                        window.infiniteScrollUserHasScrolled = false;
                    }

                    $productsContainer.html(response.data.html);

                    maxPages = response.data.max_pages || 1;
                    updatePagination(response.data.count, response.data.max_pages);
                    updateResultsInfo(response.data.count);

                    $('.pets-count-display').text(response.data.count);

                    updateURL();

                    // Programmatic scroll
                    window.isProgrammaticScroll = true;
                    const containerTop = $productsContainer.offset().top;
                    const viewportTop = $(window).scrollTop();
                    const viewportHeight = $(window).height();

                    const scrollTarget = containerTop > viewportTop + viewportHeight / 2
                        ? containerTop - 150
                        : 0;

                    $('html, body').animate({ scrollTop: scrollTarget }, 400, function() {
                        setTimeout(function() {
                            window.isProgrammaticScroll = false;
                        }, 100);
                    });
                } else {
                    showError($productsContainer);
                }

                hideLoadingState($productsContainer);
                isFiltering = false;
            },
            error: function() {
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
        $('.woocommerce-pagination').remove();

        if (totalPages > 1) {
            const $productsContainer = $('.woocommerce .products, ul.products, .row.row-cols-2').first();
            const $pagination = createPaginationHTML(totalPages, currentPage);
            $productsContainer.after($pagination);
        }
    }

    // Create pagination HTML
    function createPaginationHTML(totalPages, current) {
        let html = '<nav class="woocommerce-pagination"><ul class="page-numbers">';

        if (current > 1) {
            html += '<li><a class="prev page-numbers filter-page-link" data-page="' + (current - 1) + '" href="#">Previous</a></li>';
        }

        for (let i = 1; i <= totalPages; i++) {
            if (i === current) {
                html += '<li><span aria-current="page" class="page-numbers current">' + i + '</span></li>';
            } else {
                html += '<li><a class="page-numbers filter-page-link" data-page="' + i + '" href="#">' + i + '</a></li>';
            }
        }

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

        if (filterType === 'location') {
            const $allLocationCheckboxes = $('.location-checkbox');
            const checkedLocations = $allLocationCheckboxes.filter(':checked');

            if (checkedLocations.length === 0 || checkedLocations.length === $allLocationCheckboxes.length) {
                activeFilters.location = '';
                if (checkedLocations.length === 0) {
                    $allLocationCheckboxes.prop('checked', true);
                }
            } else {
                let checkboxValue = checkedLocations.first().val();
                if (checkboxValue && checkboxValue.includes('Happiness Is Pets ')) {
                    checkboxValue = checkboxValue.replace('Happiness Is Pets ', '').trim();
                }
                activeFilters.location = checkboxValue;
            }

            const $dropdown = $('#locationDropdown');
            if ($dropdown.length) {
                $dropdown.val(activeFilters.location || '');
            }
        } else {
            $('.product-filter-checkbox[data-filter-type="' + filterType + '"]').not($checkbox).prop('checked', false);

            if (isChecked) {
                activeFilters[filterType] = value;
            } else {
                activeFilters[filterType] = '';
            }
        }

        window.activeFilters = activeFilters;
        filterProducts(1);
    });

    // Handle clear all filters
    $(document).on('click', '.clear-all-filters', function(e) {
        e.preventDefault();

        $('.product-filter-checkbox').not('.location-checkbox').prop('checked', false);
        $('.location-checkbox').prop('checked', true);

        activeFilters = { gender: '', breed: '', location: '' };
        window.activeFilters = activeFilters;

        filterProducts(1);
        updateURL();
    });

    // Handle pagination clicks
    $(document).on('click', '.filter-page-link', function(e) {
        e.preventDefault();
        const page = parseInt($(this).data('page'));
        filterProducts(page);
    });

    // Load More button functionality
    $(document).on('click', '.load-more-products', function(e) {
        e.preventDefault();
        const $button = $(this);
        const nextPage = currentPage + 1;

        if (nextPage > maxPages || isFiltering) return;

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
                    const $productsContainer = $('.woocommerce .products, ul.products, .row.row-cols-2').first();
                    $productsContainer.append(response.data.html);

                    currentPage = nextPage;
                    maxPages = response.data.max_pages || maxPages;

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
        const urlParams = new URLSearchParams(window.location.search);
        let locationFromURL = urlParams.get('filter_location') || urlParams.get('location') || '';

        if (locationFromURL) {
            const locationLower = locationFromURL.toLowerCase().trim();
            if (locationLower === 'indianapolis') {
                locationFromURL = 'Indianapolis';
            } else if (locationLower === 'schererville') {
                locationFromURL = 'Schererville';
            }
        }

        const $locationCheckboxes = $('.location-checkbox');
        if (!locationFromURL) {
            $locationCheckboxes.prop('checked', true);
        } else {
            $locationCheckboxes.each(function() {
                let checkboxLocation = this.value;
                if (checkboxLocation.includes('Happiness Is Pets ')) {
                    checkboxLocation = checkboxLocation.replace('Happiness Is Pets ', '').trim();
                }
                this.checked = (checkboxLocation.toLowerCase() === locationFromURL.toLowerCase());
            });
        }

        activeFilters.location = locationFromURL;
        window.activeFilters = activeFilters;
    });

    // Initialize on page load
    $(document).ready(function() {
        setTimeout(function() {
            initializeFiltersFromURL();
        }, 200);
    });

})(jQuery);
