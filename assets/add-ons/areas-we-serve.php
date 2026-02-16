<?php
/**
 * Custom Meta Box for Areas We Serve Template
 */

// Register meta boxes
add_action('add_meta_boxes', 'aws_register_meta_boxes', 10, 2);

function aws_register_meta_boxes($post_type, $post) {
    // Only add to page post type
    if ($post_type !== 'page') {
        return;
    }

    // Always add the meta box - we'll control visibility with JS/CSS
    add_meta_box(
            'aws_location_details',
            'Location Details (Areas We Serve)',
            'aws_meta_box_callback',
            'page',
            'normal',
            'high'
    );
}

function aws_meta_box_callback($post) {
    wp_nonce_field('aws_save_meta_box_data', 'aws_meta_box_nonce');

    // Get existing values
    $location_city = get_post_meta($post->ID, 'location_city', true);
    $location_state = get_post_meta($post->ID, 'location_state', true);
    $location_zip = get_post_meta($post->ID, 'location_zip', true);
    $service_radius = get_post_meta($post->ID, 'service_radius', true);
    $location_phone = get_post_meta($post->ID, 'location_phone', true);
    $location_email = get_post_meta($post->ID, 'location_email', true);
    $delivery_available = get_post_meta($post->ID, 'delivery_available', true);
    $pickup_available = get_post_meta($post->ID, 'pickup_available', true);
    $business_hours = get_post_meta($post->ID, 'business_hours', true);
    ?>

    <div class="aws-meta-fields" style="display: none;">
        <p class="aws-template-notice" style="padding: 20px; background: #f0f0f0; border-left: 4px solid #ffb900; display: none;">
            This meta box is only available when using the <strong>"Areas We Serve"</strong> page template.
            Please select it from the Page Attributes section on the right.
        </p>

        <div class="aws-fields-wrapper">
            <style>
                .aws-meta-field { margin-bottom: 15px; }
                .aws-meta-field label { display: block; font-weight: bold; margin-bottom: 5px; color: #23282d; }
                .aws-meta-field input[type="text"],
                .aws-meta-field input[type="email"],
                .aws-meta-field textarea,
                .aws-meta-field select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
                .aws-meta-field textarea { height: 80px; resize: vertical; }
                .aws-meta-row { display: flex; gap: 20px; }
                .aws-meta-row .aws-meta-field { flex: 1; }
                .aws-required { color: #d63638; }
            </style>

            <div class="aws-meta-row">
                <div class="aws-meta-field">
                    <label for="location_city">City <span class="aws-required">*</span></label>
                    <input type="text" id="location_city" name="location_city" value="<?php echo esc_attr($location_city); ?>" />
                    <p class="description">Primary city for this service area page</p>
                </div>

                <div class="aws-meta-field">
                    <label for="location_state">State</label>
                    <select id="location_state" name="location_state">
                        <option value="">Select State</option>
                        <option value="AL" <?php selected($location_state, 'AL'); ?>>Alabama</option>
                        <option value="AK" <?php selected($location_state, 'AK'); ?>>Alaska</option>
                        <option value="AZ" <?php selected($location_state, 'AZ'); ?>>Arizona</option>
                        <option value="AR" <?php selected($location_state, 'AR'); ?>>Arkansas</option>
                        <option value="CA" <?php selected($location_state, 'CA'); ?>>California</option>
                        <option value="CO" <?php selected($location_state, 'CO'); ?>>Colorado</option>
                        <option value="CT" <?php selected($location_state, 'CT'); ?>>Connecticut</option>
                        <option value="DE" <?php selected($location_state, 'DE'); ?>>Delaware</option>
                        <option value="FL" <?php selected($location_state, 'FL'); ?>>Florida</option>
                        <option value="GA" <?php selected($location_state, 'GA'); ?>>Georgia</option>
                        <option value="HI" <?php selected($location_state, 'HI'); ?>>Hawaii</option>
                        <option value="ID" <?php selected($location_state, 'ID'); ?>>Idaho</option>
                        <option value="IL" <?php selected($location_state, 'IL'); ?>>Illinois</option>
                        <option value="IN" <?php selected($location_state, 'IN'); ?>>Indiana</option>
                        <option value="IA" <?php selected($location_state, 'IA'); ?>>Iowa</option>
                        <option value="KS" <?php selected($location_state, 'KS'); ?>>Kansas</option>
                        <option value="KY" <?php selected($location_state, 'KY'); ?>>Kentucky</option>
                        <option value="LA" <?php selected($location_state, 'LA'); ?>>Louisiana</option>
                        <option value="ME" <?php selected($location_state, 'ME'); ?>>Maine</option>
                        <option value="MD" <?php selected($location_state, 'MD'); ?>>Maryland</option>
                        <option value="MA" <?php selected($location_state, 'MA'); ?>>Massachusetts</option>
                        <option value="MI" <?php selected($location_state, 'MI'); ?>>Michigan</option>
                        <option value="MN" <?php selected($location_state, 'MN'); ?>>Minnesota</option>
                        <option value="MS" <?php selected($location_state, 'MS'); ?>>Mississippi</option>
                        <option value="MO" <?php selected($location_state, 'MO'); ?>>Missouri</option>
                        <option value="MT" <?php selected($location_state, 'MT'); ?>>Montana</option>
                        <option value="NE" <?php selected($location_state, 'NE'); ?>>Nebraska</option>
                        <option value="NV" <?php selected($location_state, 'NV'); ?>>Nevada</option>
                        <option value="NH" <?php selected($location_state, 'NH'); ?>>New Hampshire</option>
                        <option value="NJ" <?php selected($location_state, 'NJ'); ?>>New Jersey</option>
                        <option value="NM" <?php selected($location_state, 'NM'); ?>>New Mexico</option>
                        <option value="NY" <?php selected($location_state, 'NY'); ?>>New York</option>
                        <option value="NC" <?php selected($location_state, 'NC'); ?>>North Carolina</option>
                        <option value="ND" <?php selected($location_state, 'ND'); ?>>North Dakota</option>
                        <option value="OH" <?php selected($location_state, 'OH'); ?>>Ohio</option>
                        <option value="OK" <?php selected($location_state, 'OK'); ?>>Oklahoma</option>
                        <option value="OR" <?php selected($location_state, 'OR'); ?>>Oregon</option>
                        <option value="PA" <?php selected($location_state, 'PA'); ?>>Pennsylvania</option>
                        <option value="RI" <?php selected($location_state, 'RI'); ?>>Rhode Island</option>
                        <option value="SC" <?php selected($location_state, 'SC'); ?>>South Carolina</option>
                        <option value="SD" <?php selected($location_state, 'SD'); ?>>South Dakota</option>
                        <option value="TN" <?php selected($location_state, 'TN'); ?>>Tennessee</option>
                        <option value="TX" <?php selected($location_state, 'TX'); ?>>Texas</option>
                        <option value="UT" <?php selected($location_state, 'UT'); ?>>Utah</option>
                        <option value="VT" <?php selected($location_state, 'VT'); ?>>Vermont</option>
                        <option value="VA" <?php selected($location_state, 'VA'); ?>>Virginia</option>
                        <option value="WA" <?php selected($location_state, 'WA'); ?>>Washington</option>
                        <option value="WV" <?php selected($location_state, 'WV'); ?>>West Virginia</option>
                        <option value="WI" <?php selected($location_state, 'WI'); ?>>Wisconsin</option>
                        <option value="WY" <?php selected($location_state, 'WY'); ?>>Wyoming</option>
                    </select>
                </div>

                <div class="aws-meta-field">
                    <label for="location_zip">ZIP Code</label>
                    <input type="text" id="location_zip" name="location_zip" value="<?php echo esc_attr($location_zip); ?>" pattern="[0-9]{5}" />
                    <p class="description">5-digit ZIP code</p>
                </div>
            </div>

            <div class="aws-meta-row">
                <div class="aws-meta-field">
                    <label for="service_radius">Service Radius (miles)</label>
                    <input type="text" id="service_radius" name="service_radius" value="<?php echo esc_attr($service_radius); ?>" />
                    <p class="description">How far you deliver/serve</p>
                </div>

                <div class="aws-meta-field">
                    <label for="location_phone">Contact Phone</label>
                    <input type="text" id="location_phone" name="location_phone" value="<?php echo esc_attr($location_phone); ?>" />
                    <p class="description">Local phone number if available</p>
                </div>

                <div class="aws-meta-field">
                    <label for="location_email">Contact Email</label>
                    <input type="email" id="location_email" name="location_email" value="<?php echo esc_attr($location_email); ?>" />
                    <p class="description">Location-specific email</p>
                </div>
            </div>

            <div class="aws-meta-row">
                <div class="aws-meta-field">
                    <label for="delivery_available">Delivery Available</label>
                    <select id="delivery_available" name="delivery_available">
                        <option value="no" <?php selected($delivery_available, 'no'); ?>>No</option>
                        <option value="yes" <?php selected($delivery_available, 'yes'); ?>>Yes</option>
                    </select>
                </div>

                <div class="aws-meta-field">
                    <label for="pickup_available">Local Pickup Available</label>
                    <select id="pickup_available" name="pickup_available">
                        <option value="no" <?php selected($pickup_available, 'no'); ?>>No</option>
                        <option value="yes" <?php selected($pickup_available, 'yes'); ?>>Yes</option>
                    </select>
                </div>
            </div>

            <div class="aws-meta-field">
                <label for="business_hours">Business Hours (for Schema)</label>
                <textarea id="business_hours" name="business_hours" placeholder="Mo-Fr 09:00-17:00, Sa 10:00-15:00"><?php echo esc_textarea($business_hours); ?></textarea>
                <p class="description">Format: Mo-Fr 09:00-17:00, Sa 10:00-15:00</p>
            </div>
        </div>
    </div>

    <script>
        jQuery(document).ready(function($) {
            var savedTemplate = '<?php echo esc_js(get_post_meta($post->ID, '_wp_page_template', true)); ?>';

            function checkTemplate() {
                var currentTemplate = $('#page_template').val() || savedTemplate || 'default';
                var $metaFields = $('.aws-meta-fields');
                var $notice = $('.aws-template-notice');
                var $wrapper = $('.aws-fields-wrapper');

                if (currentTemplate === 'page-areas-we-serve.php') {
                    $metaFields.show();
                    $notice.hide();
                    $wrapper.show();
                } else {
                    $metaFields.show();
                    $notice.show();
                    $wrapper.hide();
                }
            }

            // Check on load
            checkTemplate();

            // Check when template changes
            $('#page_template').on('change', checkTemplate);
        });
    </script>
    <?php
}

function aws_save_meta_box_data($post_id) {
    if (!isset($_POST['aws_meta_box_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['aws_meta_box_nonce'], 'aws_save_meta_box_data')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save fields
    $fields = array(
            'location_city', 'location_state', 'location_zip',
            'service_radius', 'location_phone', 'location_email',
            'delivery_available', 'pickup_available', 'business_hours'
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'aws_save_meta_box_data');






