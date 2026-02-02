/**
 * Infinite Scroll for WooCommerce Products
 * Uses Intersection Observer API for reliable scroll detection
 */

(function($) {
    'use strict';

    // Configuration
    let currentPage = parseInt(infiniteScrollParams.current_page);
    let maxPages = parseInt(infiniteScrollParams.max_pages);
    let loading = false;
    let allLoaded = false;

    // Use global variable to persist across page loads and AJAX calls
    if (typeof window.displayedProductIds === 'undefined') {
        window.displayedProductIds = new Set();
    }
    const displayedProductIds = window.displayedProductIds;

    // Find the products container
    const $container = $('.row.row-cols-2').first();

    if (!$container.length) {
        return;
    }

    // Cache filter check result - only recompute when URL changes
    let _cachedFilterResult = null;
    let _cachedFilterURL = null;
    function hasActiveFilters() {
        const currentURL = window.location.search;
        if (_cachedFilterURL === currentURL && _cachedFilterResult !== null) {
            return _cachedFilterResult;
        }
        _cachedFilterURL = currentURL;
        const urlParams = new URLSearchParams(currentURL);
        const hasBreed = urlParams.get('filter_breed');
        const hasGender = urlParams.get('filter_gender');
        const hasLocation = urlParams.get('filter_location');

        if (typeof window.activeFilters !== 'undefined') {
            const filters = window.activeFilters;
            if (filters && (filters.breed || filters.gender || filters.location)) {
                _cachedFilterResult = true;
                return true;
            }
        }

        _cachedFilterResult = !!(hasBreed || hasGender || hasLocation);
        return _cachedFilterResult;
    }

    // Invalidate filter cache when URL changes (called by filter scripts)
    window.invalidateFilterCache = function() {
        _cachedFilterResult = null;
        _cachedFilterURL = null;
    };

    // Initialize displayedProductIds with products already on the page
    $container.find('[data-product-id]').each(function() {
        const productId = this.getAttribute('data-product-id');
        const refId = this.getAttribute('data-ref-id');
        if (productId) displayedProductIds.add('id-' + productId);
        if (refId) displayedProductIds.add('ref-' + refId);
    });

    // Only proceed if there are more pages to load
    if (currentPage >= maxPages) {
        return;
    }

    // Create loading sentinel (invisible element that triggers load when visible)
    const $sentinel = $('<div class="infinite-scroll-sentinel" style="height: 1px; margin-top: 200px;"></div>');
    const $loader = $('<div class="infinite-scroll-loader text-center py-5" style="display: none;"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-3 text-muted">Loading more puppies...</p></div>');

    // Insert sentinel and loader after the products container
    $container.after($sentinel);
    $sentinel.after($loader);

    // Load more products function
    function loadMoreProducts() {
        if (hasActiveFilters()) {
            // Filters handle their own pagination
        }

        if (loading || allLoaded) return;

        loading = true;
        currentPage++;

        // Show loader
        $loader.show();

        // Get current filters from URL and global activeFilters
        const urlParams = new URLSearchParams(window.location.search);
        const location = urlParams.get('location');

        let breed = urlParams.get('filter_breed') || '';
        let gender = urlParams.get('filter_gender') || '';
        let filterLocation = urlParams.get('filter_location') || location || '';

        if (typeof window.activeFilters !== 'undefined' && window.activeFilters) {
            if (window.activeFilters.breed) breed = window.activeFilters.breed;
            if (window.activeFilters.gender) gender = window.activeFilters.gender;
            if (window.activeFilters.location) filterLocation = window.activeFilters.location;
        }

        $.ajax({
            url: infiniteScrollParams.ajaxurl,
            type: 'POST',
            data: {
                action: 'load_more_products',
                page: currentPage,
                query_vars: infiniteScrollParams.query_vars,
                location: filterLocation,
                breed: breed,
                gender: gender
            },
            success: function(response) {
                if (response.success && response.data.html) {
                    const $tempContainer = $('<div>').html(response.data.html);
                    let hasNewProducts = false;

                    $tempContainer.children('[data-product-id]').each(function() {
                        const $item = $(this);
                        const productId = $item.attr('data-product-id');
                        const refId = $item.attr('data-ref-id');

                        const isDuplicate = (productId && displayedProductIds.has('id-' + productId)) ||
                                          (refId && displayedProductIds.has('ref-' + refId));

                        if (isDuplicate) {
                            $item.remove();
                        } else {
                            if (productId) displayedProductIds.add('id-' + productId);
                            if (refId) displayedProductIds.add('ref-' + refId);
                            hasNewProducts = true;
                        }
                    });

                    if (hasNewProducts) {
                        const $newProducts = $tempContainer.children();
                        if ($newProducts.length > 0) {
                            $container.append($newProducts);
                        }
                    }

                    if (response.data.max_pages) {
                        maxPages = response.data.max_pages;
                    }

                    if (currentPage >= maxPages) {
                        allLoaded = true;
                        $sentinel.remove();
                        $loader.html('<div class="text-center py-4"><p class="text-muted">That\'s all the puppies for now!</p></div>').show();
                    } else {
                        $loader.hide();
                    }
                } else {
                    allLoaded = true;
                    $sentinel.remove();
                    $loader.html('<div class="text-center py-4"><p class="text-muted">No more puppies available</p></div>').show();
                }

                loading = false;
            },
            error: function() {
                allLoaded = true;
                $sentinel.remove();
                $loader.html('<div class="alert alert-warning text-center">Unable to load more products. Please refresh the page.</div>').show();
                loading = false;
            }
        });
    }

    // Track if user has scrolled
    if (typeof window.infiniteScrollUserHasScrolled === 'undefined') {
        window.infiniteScrollUserHasScrolled = false;
    }
    let userHasScrolled = window.infiniteScrollUserHasScrolled;
    let initialScrollTop = $(window).scrollTop();

    // Debounced scroll handler
    let scrollTicking = false;
    $(window).on('scroll', function() {
        if (window.isProgrammaticScroll) return;
        if (scrollTicking) return;

        scrollTicking = true;
        requestAnimationFrame(function() {
            const currentScrollTop = $(window).scrollTop();
            if (Math.abs(currentScrollTop - initialScrollTop) > 50) {
                userHasScrolled = true;
                window.infiniteScrollUserHasScrolled = true;
            }
            scrollTicking = false;
        });
    });

    // Clear displayed product IDs when filters change (prevents memory leak)
    window.resetInfiniteScrollProducts = function() {
        displayedProductIds.clear();
        // Re-populate with currently visible products
        $container.find('[data-product-id]').each(function() {
            const productId = this.getAttribute('data-product-id');
            const refId = this.getAttribute('data-ref-id');
            if (productId) displayedProductIds.add('id-' + productId);
            if (refId) displayedProductIds.add('ref-' + refId);
        });
        _cachedFilterResult = null;
        _cachedFilterURL = null;
    };

    // Use Intersection Observer to detect when sentinel comes into view
    let observer;
    if ('IntersectionObserver' in window) {
        observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                userHasScrolled = window.infiniteScrollUserHasScrolled || false;

                if (window.isProgrammaticScroll) return;

                if (entry.isIntersecting && !loading && !allLoaded && userHasScrolled) {
                    loadMoreProducts();
                }
            });
        }, {
            rootMargin: '0px 0px 400px 0px'
        });

        // Delay observing to prevent immediate trigger after page load/filtering
        setTimeout(function() {
            if ($sentinel.length && !hasActiveFilters()) {
                const sentinelRect = $sentinel[0].getBoundingClientRect();
                const isInViewport = sentinelRect.top < window.innerHeight + 400;

                if (isInViewport && !userHasScrolled) {
                    // Wait for user scroll before observing - use passive listener instead of polling
                    function onFirstScroll() {
                        if (window.infiniteScrollUserHasScrolled) {
                            window.removeEventListener('scroll', onFirstScroll);
                            if (!hasActiveFilters() && $sentinel.length) {
                                observer.observe($sentinel[0]);
                            }
                        }
                    }
                    window.addEventListener('scroll', onFirstScroll, { passive: true });

                    // Safety timeout: start observing after 10 seconds regardless
                    setTimeout(function() {
                        window.removeEventListener('scroll', onFirstScroll);
                        if (!hasActiveFilters() && $sentinel.length) {
                            observer.observe($sentinel[0]);
                        }
                    }, 10000);
                } else {
                    observer.observe($sentinel[0]);
                }
            }
        }, 500);

        window.infiniteScrollObserver = observer;
    } else {
        // Fallback for older browsers - debounced scroll event
        let scrollTimeout;
        const scrollHandler = function() {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(function() {
                if (hasActiveFilters()) {
                    $(window).off('scroll', scrollHandler);
                    $sentinel.remove();
                    $loader.remove();
                    return;
                }

                if (loading || allLoaded) return;

                const sentinelOffset = $sentinel.offset().top;
                const scrollPosition = $(window).scrollTop() + $(window).height();

                if (scrollPosition >= sentinelOffset - 400) {
                    loadMoreProducts();
                }
            }, 100);
        };
        $(window).on('scroll', scrollHandler);
    }

})(jQuery);
