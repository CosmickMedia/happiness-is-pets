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

    // Find the products container
    const $container = $('.row.row-cols-2').first();

    if (!$container.length) {
        console.error('Products container not found');
        return;
    }

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
        if (loading || allLoaded) return;

        loading = true;
        currentPage++;

        // Show loader
        $loader.show();

        $.ajax({
            url: infiniteScrollParams.ajaxurl,
            type: 'POST',
            data: {
                action: 'load_more_products',
                page: currentPage,
                query_vars: infiniteScrollParams.query_vars
            },
            success: function(response) {
                if (response.success && response.data.html) {
                    // Append new products
                    $container.append(response.data.html);

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

    // Use Intersection Observer to detect when sentinel comes into view
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting && !loading && !allLoaded) {
                    loadMoreProducts();
                }
            });
        }, {
            rootMargin: '0px 0px 400px 0px' // Start loading 400px before sentinel is visible
        });

        observer.observe($sentinel[0]);
    } else {
        // Fallback for older browsers - use scroll event
        let scrollTimeout;
        $(window).on('scroll', function() {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(function() {
                if (loading || allLoaded) return;

                const sentinelOffset = $sentinel.offset().top;
                const scrollPosition = $(window).scrollTop() + $(window).height();

                if (scrollPosition >= sentinelOffset - 400) {
                    loadMoreProducts();
                }
            }, 100);
        });
    }

})(jQuery);
