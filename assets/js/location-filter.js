/**
 * Location Filter for WooCommerce Products
 */

(function($) {
    'use strict';

    // Handle location dropdown changes
    $(document).on('change', '#locationDropdown', function() {
        const location = $(this).val();
        const url = new URL(window.location.href);

        if (location) {
            url.searchParams.set('location', location);
        } else {
            url.searchParams.delete('location');
        }

        // Reset to page 1 when filtering
        url.searchParams.delete('paged');

        // Reload page with new filter
        window.location.href = url.toString();
    });

    // Handle location checkbox changes (for sidebar widget if used)
    $(document).on('change', '.location-filter-checkbox', function() {
        const $checkbox = $(this);
        const location = $checkbox.data('location');
        const isChecked = $checkbox.is(':checked');

        // Uncheck other location checkboxes (only one location at a time)
        $('.location-filter-checkbox').not($checkbox).prop('checked', false);

        // Update URL and reload page
        const url = new URL(window.location.href);

        if (isChecked) {
            url.searchParams.set('location', location);
        } else {
            url.searchParams.delete('location');
        }

        // Reset to page 1 when filtering
        url.searchParams.delete('paged');

        // Reload page with new filter
        window.location.href = url.toString();
    });

    // Handle clear filter button
    $(document).on('click', '.clear-location-filter', function() {
        const url = new URL(window.location.href);
        url.searchParams.delete('location');
        url.searchParams.delete('paged');
        window.location.href = url.toString();
    });

})(jQuery);
