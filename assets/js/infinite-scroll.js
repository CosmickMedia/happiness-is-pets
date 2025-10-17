/**
 * Infinite Scroll for WooCommerce Product Archives
 * Happiness Is Pets Theme
 */

(function($) {
    'use strict';

    console.log('🚀 Infinite Scroll Script Loaded');
    console.log('📊 Params:', infiniteScrollParams);

    let currentPage = parseInt(infiniteScrollParams.current_page);
    let maxPages = parseInt(infiniteScrollParams.max_pages);
    let loading = false;
    let allLoaded = false;

    console.log('📄 Current Page:', currentPage);
    console.log('📚 Max Pages:', maxPages);

    // Create and append loading indicator
    const loadingHTML = `
        <div class="infinite-scroll-loading text-center py-5" style="display: none;">
            <div class="spinner-border text-primary" role="status" style="color: var(--color-primary-dark-teal) !important;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 fw-bold">${infiniteScrollParams.loading_text}</p>
        </div>
    `;

    const noMoreHTML = `
        <div class="infinite-scroll-end text-center py-4">
            <p class="text-muted">${infiniteScrollParams.no_more_text}</p>
        </div>
    `;

    // Find the products container - try multiple selectors
    let $productsContainer = $('.products');

    // If not found, try alternative selectors
    if ($productsContainer.length === 0) {
        console.log('⚠️ .products not found, trying alternatives...');
        $productsContainer = $('ul.products');
    }

    if ($productsContainer.length === 0) {
        $productsContainer = $('.woocommerce ul.products');
    }

    if ($productsContainer.length === 0) {
        $productsContainer = $('.row.product-grid');
    }

    if ($productsContainer.length === 0) {
        // Find the FIRST row that contains product elements
        $productsContainer = $('.row').has('.product, .col.type-product').first();
        console.log('🎯 Using first .row with products');
    }

    console.log('🔍 Products container found:', $productsContainer.length);
    console.log('📦 Container class:', $productsContainer.attr('class'));
    console.log('🔢 Products in container:', $productsContainer.find('.product, .col.type-product').length);

    if ($productsContainer.length) {
        // Only append loading indicator once
        if ($('.infinite-scroll-loading').length === 0) {
            $productsContainer.after(loadingHTML);
            console.log('✅ Loading indicator added');
        } else {
            console.log('ℹ️ Loading indicator already exists');
        }
    } else {
        console.error('❌ No products container found!');
        console.log('🔍 Available containers:', $('[class*="product"]').length);
        return; // Exit if no container found
    }

    const $loadingIndicator = $('.infinite-scroll-loading');

    /**
     * Load more products via AJAX
     */
    function loadMoreProducts() {
        console.log('🎬 loadMoreProducts called - Page:', currentPage, 'Max:', maxPages, 'Loading:', loading, 'AllLoaded:', allLoaded);

        if (loading || allLoaded || currentPage >= maxPages) {
            console.log('⛔ Skipping load - already loading or all loaded');
            if (currentPage >= maxPages && !allLoaded) {
                $loadingIndicator.hide();
                if ($('.infinite-scroll-end').length === 0) {
                    $productsContainer.after(noMoreHTML);
                }
                allLoaded = true;
            }
            return;
        }

        loading = true;
        currentPage++;
        console.log('📤 Making AJAX request for page:', currentPage);
        $loadingIndicator.show();

        $.ajax({
            url: infiniteScrollParams.ajaxurl,
            type: 'POST',
            data: {
                action: 'load_more_products',
                page: currentPage,
                query_vars: infiniteScrollParams.query_vars
            },
            success: function(response) {
                console.log('📥 AJAX Response received:', response);

                if (response.success && response.data.html) {
                    console.log('✅ Success! HTML length:', response.data.html.length);

                    // Try to parse the HTML and find products
                    const $responseHTML = $(response.data.html);
                    console.log('🔍 Response elements:', $responseHTML.length);

                    let $newProducts;

                    // Check if response is product elements or wrapped
                    if ($responseHTML.hasClass('product') || $responseHTML.hasClass('col')) {
                        $newProducts = $responseHTML;
                        console.log('✓ Products found directly');
                    } else {
                        $newProducts = $responseHTML.find('.product, .col');
                        console.log('✓ Products found nested:', $newProducts.length);
                    }

                    if ($newProducts.length === 0) {
                        // Fallback: Just append the HTML as-is
                        console.log('⚠️ No product elements found, appending raw HTML');
                        $productsContainer.append(response.data.html);
                    } else {
                        console.log('➕ Appending', $newProducts.length, 'products');
                        $productsContainer.append($newProducts);
                    }

                    // Update max pages
                    maxPages = parseInt(response.data.max_pages);
                    console.log('📚 Updated max pages:', maxPages);

                    // Trigger event for other scripts
                    $(document).trigger('happiness_infinite_scroll_loaded', [$newProducts]);

                    // Check if we've loaded all products
                    if (currentPage >= maxPages) {
                        console.log('🏁 All pages loaded!');
                        $loadingIndicator.hide();
                        if ($('.infinite-scroll-end').length === 0) {
                            $productsContainer.after(noMoreHTML);
                        }
                        allLoaded = true;
                    } else {
                        console.log('📄 More pages available');
                        $loadingIndicator.hide();
                    }
                } else {
                    console.log('❌ No more products available');
                    // No more products
                    $loadingIndicator.hide();
                    if ($('.infinite-scroll-end').length === 0) {
                        $productsContainer.after(noMoreHTML);
                    }
                    allLoaded = true;
                }

                loading = false;
                console.log('✓ Loading complete, ready for next page');
            },
            error: function(xhr, status, error) {
                console.error('❌ AJAX Error:', status, error);
                console.error('Response:', xhr.responseText);
                $loadingIndicator.hide();
                loading = false;
            }
        });
    }

    /**
     * Check if user has scrolled near bottom of page
     */
    function checkScroll() {
        if (allLoaded || loading) {
            return;
        }

        const scrollPosition = $(window).scrollTop() + $(window).height();
        const documentHeight = $(document).height();
        const triggerPoint = documentHeight - 800; // Trigger 800px before bottom

        console.log('📍 Scroll check - Position:', scrollPosition, 'Trigger:', triggerPoint);

        if (scrollPosition >= triggerPoint) {
            console.log('✨ Trigger point reached! Loading more...');
            loadMoreProducts();
        }
    }

    // Bind scroll event with throttling
    let scrollTimeout;
    $(window).on('scroll', function() {
        if (scrollTimeout) {
            clearTimeout(scrollTimeout);
        }

        scrollTimeout = setTimeout(function() {
            checkScroll();
        }, 100);
    });

    // Initial check in case content doesn't fill the page
    $(document).ready(function() {
        // Remove any duplicate loading indicators
        $('.infinite-scroll-loading').each(function(index) {
            if (index > 0) {
                console.log('🗑️ Removing duplicate loading indicator #' + index);
                $(this).remove();
            }
        });

        // Remove any duplicate end messages
        $('.infinite-scroll-end').each(function(index) {
            if (index > 0) {
                console.log('🗑️ Removing duplicate end message #' + index);
                $(this).remove();
            }
        });

        setTimeout(function() {
            checkScroll();
        }, 500);
    });

})(jQuery);
