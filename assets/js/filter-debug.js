/**
 * Debug script for WooCommerce Filters
 * This logs all filter-related events to help diagnose issues
 */

(function($) {
    'use strict';

    console.log('[Filter Debug] Debug script loaded');
    console.log('[Filter Debug] Current URL:', window.location.href);
    console.log('[Filter Debug] jQuery version:', $.fn.jquery);

    // Monitor ALL network requests to see if blocks are trying to load
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        const url = args[0];
        if (url && (url.includes('wc/store') || url.includes('batch'))) {
            console.log('[Filter Debug] WooCommerce Store API request:', url);
        }
        return originalFetch.apply(this, args)
            .then(response => {
                if (url && (url.includes('wc/store') || url.includes('batch'))) {
                    console.log('[Filter Debug] WooCommerce Store API response:', response.status, response.statusText);
                }
                return response;
            })
            .catch(error => {
                if (url && (url.includes('wc/store') || url.includes('batch'))) {
                    console.error('[Filter Debug] WooCommerce Store API ERROR:', error);
                }
                throw error;
            });
    };

    // Log when DOM is ready
    $(document).ready(function() {
        console.log('[Filter Debug] DOM Ready');

        // Check for WooCommerce BLOCKS (new system)
        const $blockFilters = $('.wp-block-woocommerce-filter-wrapper, [data-block-name="woocommerce/filter-wrapper"]');
        console.log('[Filter Debug] Found WooCommerce BLOCK filters:', $blockFilters.length);

        if ($blockFilters.length > 0) {
            $blockFilters.each(function(index) {
                const $block = $(this);
                console.log('[Filter Debug] Block #' + index + ':', {
                    filterType: $block.data('filter-type'),
                    blockName: $block.data('block-name'),
                    heading: $block.data('heading'),
                    html: $block.html().substring(0, 300)
                });
            });

            // Check for placeholders (blocks not rendered yet)
            const $placeholders = $('.wc-block-placeholder');
            console.log('[Filter Debug] BLOCKS NOT RENDERED - Found placeholders:', $placeholders.length);
        }

        // Check for WooCommerce filter widgets (legacy)
        const $filterWidgets = $('.widget_layered_nav, .woocommerce-widget-layered-nav');
        console.log('[Filter Debug] Found WooCommerce legacy filter widgets:', $filterWidgets.length);

        if ($filterWidgets.length > 0) {
            $filterWidgets.each(function(index) {
                const $widget = $(this);
                console.log('[Filter Debug] Widget #' + index + ':', {
                    id: $widget.attr('id'),
                    class: $widget.attr('class'),
                    html: $widget.html().substring(0, 200)
                });
            });
        }

        // Check for filter lists/checkboxes
        const $filterLists = $('.woocommerce-widget-layered-nav-list');
        console.log('[Filter Debug] Found filter lists:', $filterLists.length);

        // Check for filter links
        const $filterLinks = $('.woocommerce-widget-layered-nav-list a');
        console.log('[Filter Debug] Found filter links:', $filterLinks.length);

        // Check for checkboxes
        const $filterCheckboxes = $('input[type="checkbox"]').filter(function() {
            return $(this).closest('.widget_layered_nav, .woocommerce-widget-layered-nav').length > 0;
        });
        console.log('[Filter Debug] Found filter checkboxes:', $filterCheckboxes.length);

        // Check sidebar
        const $sidebar = $('#pets-sidebar, #secondary, .widget-area');
        console.log('[Filter Debug] Found sidebar:', $sidebar.length, $sidebar);
        if ($sidebar.length > 0) {
            console.log('[Filter Debug] Sidebar HTML (first 500 chars):', $sidebar.html().substring(0, 500));
        }
    });

    // Monitor ALL clicks on the document
    $(document).on('click', function(e) {
        const $target = $(e.target);
        const $closest = $target.closest('.widget_layered_nav, .woocommerce-widget-layered-nav, .widget');

        if ($closest.length > 0) {
            console.log('[Filter Debug] Click detected in filter widget:', {
                target: e.target,
                targetTag: e.target.tagName,
                targetClass: $target.attr('class'),
                targetId: $target.attr('id'),
                targetHtml: $target.html() ? $target.html().substring(0, 100) : '',
                closestWidget: $closest.attr('class'),
                href: $target.attr('href') || $target.closest('a').attr('href')
            });
        }
    });

    // Monitor filter link clicks specifically
    $(document).on('click', '.woocommerce-widget-layered-nav-list a, .widget_layered_nav a', function(e) {
        console.log('[Filter Debug] Filter link clicked:', {
            href: $(this).attr('href'),
            text: $(this).text().trim(),
            defaultPrevented: e.isDefaultPrevented(),
            propagationStopped: e.isPropagationStopped()
        });
    });

    // Monitor checkbox changes
    $(document).on('change', 'input[type="checkbox"]', function(e) {
        const $checkbox = $(this);
        const $widget = $checkbox.closest('.widget_layered_nav, .woocommerce-widget-layered-nav, .widget');

        if ($widget.length > 0) {
            console.log('[Filter Debug] Checkbox changed in filter widget:', {
                name: $checkbox.attr('name'),
                value: $checkbox.val(),
                checked: $checkbox.is(':checked'),
                widgetClass: $widget.attr('class')
            });
        }
    });

    // Monitor AJAX calls
    $(document).ajaxSend(function(event, jqxhr, settings) {
        if (settings.url && (settings.url.indexOf('admin-ajax.php') > -1 || settings.data && settings.data.indexOf('woocommerce') > -1)) {
            console.log('[Filter Debug] AJAX request sent:', {
                url: settings.url,
                type: settings.type,
                data: settings.data
            });
        }
    });

    $(document).ajaxComplete(function(event, jqxhr, settings) {
        if (settings.url && (settings.url.indexOf('admin-ajax.php') > -1 || settings.data && settings.data.indexOf('woocommerce') > -1)) {
            console.log('[Filter Debug] AJAX request completed:', {
                url: settings.url,
                status: jqxhr.status,
                responseText: jqxhr.responseText ? jqxhr.responseText.substring(0, 200) : ''
            });
        }
    });

    // Monitor form submissions
    $(document).on('submit', 'form', function(e) {
        const $form = $(this);
        const $widget = $form.closest('.widget_layered_nav, .woocommerce-widget-layered-nav, .widget');

        if ($widget.length > 0) {
            console.log('[Filter Debug] Form submitted in filter widget:', {
                action: $form.attr('action'),
                method: $form.attr('method'),
                formData: $form.serialize(),
                defaultPrevented: e.isDefaultPrevented()
            });
        }
    });

    // Log when page is about to unload (navigating away)
    $(window).on('beforeunload', function() {
        console.log('[Filter Debug] Page unloading (navigation detected)');
    });

    // Monitor URL changes (for AJAX navigation)
    let lastUrl = window.location.href;
    setInterval(function() {
        if (window.location.href !== lastUrl) {
            console.log('[Filter Debug] URL changed from:', lastUrl);
            console.log('[Filter Debug] URL changed to:', window.location.href);
            lastUrl = window.location.href;
        }
    }, 500);

    // Monitor for WooCommerce Blocks rendering (check periodically)
    let blockCheckCount = 0;
    const blockCheckInterval = setInterval(function() {
        blockCheckCount++;
        const $placeholders = $('.wc-block-placeholder');
        const $renderedFilters = $('.wc-block-attribute-filter__list, .wc-block-components-filter-submit-button');

        if ($renderedFilters.length > 0) {
            console.log('[Filter Debug] WooCommerce Blocks RENDERED successfully!');
            console.log('[Filter Debug] Found rendered filter lists:', $renderedFilters.length);

            // Log filter options
            const $filterItems = $('.wc-block-attribute-filter__list li, .wc-block-checkbox-list__checkbox');
            console.log('[Filter Debug] Found filter options:', $filterItems.length);
            $filterItems.each(function(index) {
                const $item = $(this);
                console.log('[Filter Debug] Filter option #' + index + ':', {
                    text: $item.text().trim(),
                    html: $item.html().substring(0, 100)
                });
            });

            clearInterval(blockCheckInterval);
        } else if ($placeholders.length > 0) {
            console.log('[Filter Debug] Check #' + blockCheckCount + ' - Blocks still loading... (placeholders: ' + $placeholders.length + ')');
        }

        if (blockCheckCount >= 20) {
            console.error('[Filter Debug] Blocks failed to render after 10 seconds!');
            console.log('[Filter Debug] Check for JavaScript errors or API issues');
            clearInterval(blockCheckInterval);
        }
    }, 500);

    // Monitor clicks on WooCommerce Block filter items
    $(document).on('click', '.wc-block-attribute-filter__list a, .wc-block-checkbox-list__checkbox, .wc-block-components-filter-submit-button', function(e) {
        console.log('[Filter Debug] WooCommerce BLOCK filter clicked:', {
            target: this,
            text: $(this).text().trim(),
            href: $(this).attr('href'),
            tagName: this.tagName,
            className: $(this).attr('class')
        });
    });

    // Monitor changes on WooCommerce Block checkboxes
    $(document).on('change', '.wc-block-checkbox-list input[type="checkbox"]', function(e) {
        console.log('[Filter Debug] WooCommerce BLOCK checkbox changed:', {
            value: $(this).val(),
            checked: $(this).is(':checked'),
            name: $(this).attr('name')
        });
    });

    // Monitor offcanvas events
    const offcanvasEl = document.getElementById('petsFilterOffcanvas');
    if (offcanvasEl) {
        console.log('[Filter Debug] Found offcanvas element');

        offcanvasEl.addEventListener('show.bs.offcanvas', function () {
            console.log('[Filter Debug] Offcanvas opening...');
        });

        offcanvasEl.addEventListener('shown.bs.offcanvas', function () {
            console.log('[Filter Debug] Offcanvas opened!');
            console.log('[Filter Debug] Checking if blocks rendered after opening...');

            setTimeout(function() {
                const $placeholders = $('.wc-block-placeholder');
                const $renderedFilters = $('.wc-block-attribute-filter__list, .wc-block-components-filter-submit-button');
                console.log('[Filter Debug] After offcanvas open - Placeholders:', $placeholders.length, 'Rendered filters:', $renderedFilters.length);

                // Try to trigger a window resize event (sometimes helps blocks render)
                console.log('[Filter Debug] Triggering resize event to help blocks render...');
                window.dispatchEvent(new Event('resize'));

                // Check for any React error boundaries or console errors
                if ($placeholders.length > 0 && $renderedFilters.length === 0) {
                    console.error('[Filter Debug] ❌ Blocks STILL not rendering after offcanvas opened!');
                    console.log('[Filter Debug] Possible causes:');
                    console.log('[Filter Debug] 1. WooCommerce Blocks assets not loaded');
                    console.log('[Filter Debug] 2. JavaScript error preventing React initialization');
                    console.log('[Filter Debug] 3. Store API endpoint failing');
                    console.log('[Filter Debug] 4. Theme CSS hiding the blocks');

                    // Check if WooCommerce Blocks global exists
                    console.log('[Filter Debug] window.wc exists?', typeof window.wc !== 'undefined');
                    console.log('[Filter Debug] window.wp exists?', typeof window.wp !== 'undefined');
                }
            }, 1000);
        });
    } else {
        console.warn('[Filter Debug] Offcanvas element #petsFilterOffcanvas not found!');
    }

})(jQuery);
