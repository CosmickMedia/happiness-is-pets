/**
 * Load More Button for WooCommerce Product Archives
 * Happiness Is Pets Theme - Simple & Reliable
 */

(function($) {
    'use strict';

    // Prevent multiple initializations
    if (window.loadMoreInitialized) {
        return;
    }
    window.loadMoreInitialized = true;

    let currentPage = parseInt(infiniteScrollParams.current_page);
    let maxPages = parseInt(infiniteScrollParams.max_pages);
    let loading = false;

    // Find the products container
    const $productsContainer = $('.products.row').first();

    if (!$productsContainer.length) {
        console.error('Products container not found');
        return;
    }

    // Create Load More button
    const loadMoreHTML = `
        <div class="load-more-wrapper text-center py-5">
            <button class="btn btn-primary btn-lg load-more-btn" type="button">
                <span class="btn-text">Load More Puppies</span>
                <span class="spinner-border spinner-border-sm ms-2" role="status" style="display: none;">
                    <span class="visually-hidden">Loading...</span>
                </span>
            </button>
            <p class="text-muted mt-2 mb-0">Showing page ${currentPage} of ${maxPages}</p>
        </div>
    `;

    const noMoreHTML = `
        <div class="load-more-end text-center py-4">
            <p class="text-muted mb-0">That's all the puppies for now!</p>
        </div>
    `;

    // Add button after products container
    if (currentPage < maxPages) {
        $productsContainer.after(loadMoreHTML);
    } else {
        $productsContainer.after(noMoreHTML);
    }

    // Handle button click
    $(document).on('click', '.load-more-btn', function() {
        if (loading) return;

        const $btn = $(this);
        const $btnText = $btn.find('.btn-text');
        const $spinner = $btn.find('.spinner-border');
        const $wrapper = $('.load-more-wrapper');

        loading = true;
        currentPage++;

        // Show loading state
        $btn.prop('disabled', true);
        $btnText.text('Loading...');
        $spinner.show();

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
                    // Parse HTML and separate products from modals
                    const $temp = $('<div>').html(response.data.html);
                    const $allElements = $temp.children();

                    const $newProducts = $allElements.filter(':not(.modal)');
                    const $newModals = $allElements.filter('.modal');

                    // Add products to grid
                    $productsContainer.append($newProducts);

                    // Add modals to body
                    $('body').append($newModals);

                    // Update max pages
                    maxPages = response.data.max_pages || maxPages;

                    // Check if more pages exist
                    if (currentPage >= maxPages) {
                        $wrapper.remove();
                        $productsContainer.after(noMoreHTML);
                    } else {
                        // Update page counter
                        $wrapper.find('p').text(`Showing page ${currentPage} of ${maxPages}`);

                        // Reset button
                        $btn.prop('disabled', false);
                        $btnText.text('Load More Puppies');
                        $spinner.hide();
                    }

                    // Scroll smoothly to first new product
                    $('html, body').animate({
                        scrollTop: $newProducts.first().offset().top - 100
                    }, 400);
                } else {
                    $wrapper.remove();
                    $productsContainer.after(noMoreHTML);
                }

                loading = false;
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $wrapper.html(`
                    <div class="alert alert-warning text-center">
                        Unable to load more products. Please refresh the page.
                    </div>
                `);
                loading = false;
            }
        });
    });

})(jQuery);
