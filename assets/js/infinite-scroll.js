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
        console.error('Products container not found');
        return;
    }
    
    // Check if custom filters are active - if so, disable infinite scroll
    // Custom filters handle their own pagination
    function hasActiveFilters() {
        const urlParams = new URLSearchParams(window.location.search);
        const hasBreed = urlParams.get('filter_breed');
        const hasGender = urlParams.get('filter_gender');
        const hasLocation = urlParams.get('filter_location');
        
        // Also check if custom filter script is active
        if (typeof window.activeFilters !== 'undefined') {
            const filters = window.activeFilters;
            if (filters && (filters.breed || filters.gender || filters.location)) {
                return true;
            }
        }
        
        return !!(hasBreed || hasGender || hasLocation);
    }
    
    // Note: Infinite scroll will work WITH filters now
    // Filters will be passed to the AJAX call, so it will respect active filters
    console.log('[Infinite Scroll] Initializing - will respect active filters if any');

    // Initialize displayedProductIds with products already on the page
    $container.find('[data-product-id]').each(function() {
        const $item = $(this);
        const productId = $item.attr('data-product-id');
        const refId = $item.attr('data-ref-id');
        
        if (productId) {
            displayedProductIds.add('id-' + productId);
        }
        if (refId) {
            displayedProductIds.add('ref-' + refId);
        }
    });
    
    // Also check for products by looking at the Ref ID in the card content as fallback
    $container.find('.pet-card').each(function() {
        const $card = $(this).closest('[data-product-id]');
        if (!$card.length) {
            // Try to find Ref ID from card content
            const $refIdElement = $(this).find('.pet-ref-id span');
            if ($refIdElement.length) {
                const refId = $refIdElement.text().trim();
                if (refId) {
                    displayedProductIds.add('ref-' + refId);
                }
            }
        }
    });
    
    console.log('Initialized with', displayedProductIds.size, 'products already on page');

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
        // Filters are now respected in the AJAX call, so we don't need to disable
        // Just log that we're loading with filters
        if (hasActiveFilters()) {
            console.log('[Infinite Scroll] Loading more products with active filters');
        }
        
        if (loading || allLoaded) return;

        loading = true;
        currentPage++;

        // Show loader
        $loader.show();

        // Get current filters from URL and global activeFilters
        const urlParams = new URLSearchParams(window.location.search);
        const location = urlParams.get('location');
        
        // Get active filters (from URL or global variable)
        let breed = urlParams.get('filter_breed') || '';
        let gender = urlParams.get('filter_gender') || '';
        let filterLocation = urlParams.get('filter_location') || location || '';
        
        // Also check global activeFilters if available
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
                    // Create a temporary container to parse the HTML
                    const $tempContainer = $('<div>').html(response.data.html);
                    let hasNewProducts = false;
                    
                    // Filter out duplicate products by checking both product ID and Ref ID
                    $tempContainer.find('[data-product-id]').each(function() {
                        const $item = $(this);
                        const productId = $item.attr('data-product-id');
                        const refId = $item.attr('data-ref-id');
                        
                        // Check if this product is already displayed
                        const isDuplicate = (productId && displayedProductIds.has('id-' + productId)) ||
                                          (refId && displayedProductIds.has('ref-' + refId));
                        
                        if (isDuplicate) {
                            console.log('Duplicate detected and removed:', {
                                productId: productId,
                                refId: refId
                            });
                            $item.remove(); // Remove duplicate
                        } else {
                            // Track new product
                            if (productId) {
                                displayedProductIds.add('id-' + productId);
                            }
                            if (refId) {
                                displayedProductIds.add('ref-' + refId);
                            }
                            hasNewProducts = true;
                        }
                    });
                    
                    // Only append if there are new products
                    if (hasNewProducts) {
                        const newProducts = $tempContainer.html();
                        if (newProducts.trim()) {
                            $container.append(newProducts);
                        }
                    } else {
                        // No new products, might have reached the end
                        console.log('No new products to display (all were duplicates)');
                    }

                    // Update max pages if provided
                    if (response.data.max_pages) {
                        maxPages = response.data.max_pages;
                    }

                    // Check if we've loaded all products
                    if (currentPage >= maxPages) {
                        allLoaded = true;
                        $sentinel.remove();
                        $loader.html('<div class="text-center py-4"><p class="text-muted">That\'s all the puppies for now!</p></div>').show();
                    } else {
                        $loader.hide();
                    }
                } else {
                    // No more products
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

    // Track if user has scrolled (to prevent auto-trigger on page load/filter)
    // Use global variable so custom filters can reset it
    if (typeof window.infiniteScrollUserHasScrolled === 'undefined') {
        window.infiniteScrollUserHasScrolled = false;
    }
    let userHasScrolled = window.infiniteScrollUserHasScrolled;
    let initialScrollTop = $(window).scrollTop();
    
    // Mark that user has scrolled (ignore programmatic scrolls)
    $(window).on('scroll', function() {
        // Ignore programmatic scrolls (from filter animations, etc.)
        if (window.isProgrammaticScroll) {
            return;
        }
        
        const currentScrollTop = $(window).scrollTop();
        // Only mark as scrolled if user actually scrolled down (not just page load positioning)
        if (Math.abs(currentScrollTop - initialScrollTop) > 50) {
            userHasScrolled = true;
            window.infiniteScrollUserHasScrolled = true;
        }
    });
    
    // Use Intersection Observer to detect when sentinel comes into view
    let observer;
    if ('IntersectionObserver' in window) {
        observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                // Only trigger if user has scrolled (prevents auto-trigger after filtering)
                // Update from global in case it was reset
                userHasScrolled = window.infiniteScrollUserHasScrolled || false;
                
                // Don't trigger if it's a programmatic scroll
                if (window.isProgrammaticScroll) {
                    console.log('[Infinite Scroll] Programmatic scroll detected, ignoring sentinel');
                    return;
                }
                
                if (entry.isIntersecting && !loading && !allLoaded && userHasScrolled) {
                    loadMoreProducts();
                } else if (entry.isIntersecting && !userHasScrolled) {
                    console.log('[Infinite Scroll] Sentinel visible but user hasn\'t scrolled yet, waiting...');
                }
            });
        }, {
            rootMargin: '0px 0px 400px 0px' // Start loading 400px before sentinel is visible
        });

        // Delay observing to prevent immediate trigger after page load/filtering
        // Also check that user has scrolled before starting to observe
        setTimeout(function() {
            if ($sentinel.length && !hasActiveFilters()) {
                // Check if sentinel is already in viewport - if so, wait for user scroll
                const sentinelRect = $sentinel[0].getBoundingClientRect();
                const isInViewport = sentinelRect.top < window.innerHeight + 400; // 400px is rootMargin
                
                if (isInViewport && !userHasScrolled) {
                    console.log('[Infinite Scroll] Sentinel already in viewport, waiting for user scroll...');
                    // Wait for user to scroll before observing
                    const checkScroll = setInterval(function() {
                        if (window.infiniteScrollUserHasScrolled || hasActiveFilters()) {
                            clearInterval(checkScroll);
                            if (!hasActiveFilters() && window.infiniteScrollUserHasScrolled) {
                                observer.observe($sentinel[0]);
                                console.log('[Infinite Scroll] User scrolled, now observing sentinel');
                            }
                        }
                    }, 100);
                    
                    // Stop checking after 10 seconds
                    setTimeout(function() {
                        clearInterval(checkScroll);
                        if (!hasActiveFilters() && $sentinel.length) {
                            observer.observe($sentinel[0]);
                        }
                    }, 10000);
                } else {
                    observer.observe($sentinel[0]);
                }
            }
        }, 500);
        
        // Store observer globally so custom filters can disable it
        window.infiniteScrollObserver = observer;
    } else {
        // Fallback for older browsers - use scroll event
        let scrollTimeout;
        const scrollHandler = function() {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(function() {
                // Check if filters became active
                if (hasActiveFilters()) {
                    console.log('[Infinite Scroll] Filters detected, stopping infinite scroll');
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
