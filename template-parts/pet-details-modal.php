<?php
/**
 * Shared Pet Details Modal Template
 * Single modal reused for all product cards instead of per-product modals.
 *
 * @package happiness-is-pets
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="modal fade" id="petDetailsModal" tabindex="-1" aria-labelledby="petDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="petDetailsModalLabel">Get Details About <span id="modal-pet-name"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if ( shortcode_exists( 'gravityform' ) ) {
                    echo do_shortcode( '[gravityform id="3" title="false" description="false" ajax="true"]' );
                } else {
                    echo '<p style="color: #ef4444;">Contact form unavailable.</p>';
                } ?>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';

    if (window.sharedPetModalHandlerInitialized) return;
    window.sharedPetModalHandlerInitialized = true;

    function populatePetDetailsForm(modal, button) {
        if (!button || !button.classList.contains('pet-details-trigger')) return;

        const productData = {
            petName: button.getAttribute('data-pet-name'),
            refId: button.getAttribute('data-ref-id'),
            breed: button.getAttribute('data-breed'),
            gender: button.getAttribute('data-gender'),
            birthDate: button.getAttribute('data-birth-date'),
            location: button.getAttribute('data-location'),
            productUrl: button.getAttribute('data-product-url')
        };

        // Update modal title
        const petNameSpan = document.getElementById('modal-pet-name');
        if (petNameSpan) {
            petNameSpan.textContent = productData.petName || '';
        }

        setTimeout(function() {
            const modalBody = modal.querySelector('.modal-body');
            if (!modalBody) return;

            const fieldMap = {
                'gf-pet-name': productData.petName,
                'gf-ref-id': productData.refId,
                'gf-breed': productData.breed,
                'gf-gender': productData.gender,
                'gf-birth-date': productData.birthDate,
                'gf-location': productData.location,
                'gf-product-url': productData.productUrl
            };

            Object.keys(fieldMap).forEach(function(className) {
                const value = fieldMap[className];
                let field = null;

                // Try common Gravity Forms selectors
                const selectors = [
                    '.gfield.' + className + ' input, .gfield.' + className + ' textarea, .gfield.' + className + ' select',
                    'li.' + className + ' input, li.' + className + ' textarea, li.' + className + ' select',
                    '.ginput_container.' + className + ' input, .ginput_container.' + className + ' textarea, .ginput_container.' + className + ' select',
                    'input.' + className + ', textarea.' + className + ', select.' + className
                ];

                for (let i = 0; i < selectors.length && !field; i++) {
                    field = modalBody.querySelector(selectors[i]);
                }

                if (field) {
                    field.value = value;
                    if (window.jQuery) {
                        jQuery(field).val(value).trigger('input').trigger('change');
                    } else {
                        field.dispatchEvent(new Event('input', { bubbles: true }));
                        field.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                }
            });
        }, 500);
    }

    document.addEventListener('show.bs.modal', function(event) {
        const modal = event.target;
        if (modal.id !== 'petDetailsModal') return;

        populatePetDetailsForm(modal, event.relatedTarget);
    });
})();
</script>
