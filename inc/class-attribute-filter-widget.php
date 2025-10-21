<?php
/**
 * Custom Attribute Filter Widget
 *
 * A simple widget to filter WooCommerce products by attributes.
 * Works with traditional WooCommerce templates (not blocks).
 *
 * @package happiness-is-pets
 */

class Happiness_Attribute_Filter_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'happiness_attribute_filter',
            __( 'Filter Products by Attribute', 'happiness-is-pets' ),
            array( 'description' => __( 'Display a list of attribute terms to filter products.', 'happiness-is-pets' ) )
        );
    }

    /**
     * Front-end display of widget.
     */
    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $attribute = ! empty( $instance['attribute'] ) ? $instance['attribute'] : '';
        $display_type = ! empty( $instance['display_type'] ) ? $instance['display_type'] : 'list';

        if ( empty( $attribute ) ) {
            return;
        }

        // Get the attribute taxonomy
        $taxonomy = wc_attribute_taxonomy_name( $attribute );

        if ( ! taxonomy_exists( $taxonomy ) ) {
            return;
        }

        // Get all terms for this attribute
        $terms = get_terms( array(
            'taxonomy'   => $taxonomy,
            'hide_empty' => true,
        ) );

        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            return;
        }

        // Get currently selected filter from URL
        $current_filter = isset( $_GET[ 'filter_' . $attribute ] ) ? sanitize_text_field( $_GET[ 'filter_' . $attribute ] ) : '';

        echo $args['before_widget'];

        if ( ! empty( $title ) ) {
            echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
        }

        if ( $display_type === 'list' ) {
            echo '<ul class="woocommerce-widget-layered-nav-list">';

            foreach ( $terms as $term ) {
                $current_url = remove_query_arg( 'paged' ); // Remove pagination

                // Check if this term is currently selected
                $is_selected = ( $current_filter === $term->slug );

                if ( $is_selected ) {
                    // If selected, clicking removes the filter
                    $link = remove_query_arg( 'filter_' . $attribute );
                } else {
                    // If not selected, clicking adds the filter
                    $link = add_query_arg( 'filter_' . $attribute, $term->slug );
                }

                $class = $is_selected ? 'class="chosen"' : '';

                echo '<li ' . $class . '>';
                echo '<a href="' . esc_url( $link ) . '">';
                echo esc_html( $term->name );
                echo ' <span class="count">(' . $term->count . ')</span>';
                echo '</a>';
                echo '</li>';
            }

            echo '</ul>';
        } else {
            // Checkbox display
            echo '<form method="get" class="woocommerce-widget-layered-nav">';

            // Preserve existing query parameters
            foreach ( $_GET as $key => $value ) {
                if ( $key !== 'filter_' . $attribute && $key !== 'paged' ) {
                    echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" />';
                }
            }

            echo '<ul class="woocommerce-widget-layered-nav-list">';

            foreach ( $terms as $term ) {
                $is_selected = ( $current_filter === $term->slug );
                $checked = $is_selected ? 'checked' : '';

                echo '<li>';
                echo '<label>';
                echo '<input type="checkbox" name="filter_' . esc_attr( $attribute ) . '" value="' . esc_attr( $term->slug ) . '" ' . $checked . ' onchange="this.form.submit()" />';
                echo ' ' . esc_html( $term->name );
                echo ' <span class="count">(' . $term->count . ')</span>';
                echo '</label>';
                echo '</li>';
            }

            echo '</ul>';
            echo '</form>';
        }

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $attribute = ! empty( $instance['attribute'] ) ? $instance['attribute'] : '';
        $display_type = ! empty( $instance['display_type'] ) ? $instance['display_type'] : 'list';

        // Get all product attributes
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php esc_html_e( 'Title:', 'happiness-is-pets' ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                   type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'attribute' ) ); ?>">
                <?php esc_html_e( 'Attribute:', 'happiness-is-pets' ); ?>
            </label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'attribute' ) ); ?>"
                    name="<?php echo esc_attr( $this->get_field_name( 'attribute' ) ); ?>">
                <option value=""><?php esc_html_e( 'Select an attribute', 'happiness-is-pets' ); ?></option>
                <?php foreach ( $attribute_taxonomies as $tax ) : ?>
                    <option value="<?php echo esc_attr( $tax->attribute_name ); ?>" <?php selected( $attribute, $tax->attribute_name ); ?>>
                        <?php echo esc_html( $tax->attribute_label ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'display_type' ) ); ?>">
                <?php esc_html_e( 'Display Type:', 'happiness-is-pets' ); ?>
            </label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'display_type' ) ); ?>"
                    name="<?php echo esc_attr( $this->get_field_name( 'display_type' ) ); ?>">
                <option value="list" <?php selected( $display_type, 'list' ); ?>>List</option>
                <option value="checkbox" <?php selected( $display_type, 'checkbox' ); ?>>Checkboxes</option>
            </select>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['attribute'] = ! empty( $new_instance['attribute'] ) ? sanitize_text_field( $new_instance['attribute'] ) : '';
        $instance['display_type'] = ! empty( $new_instance['display_type'] ) ? sanitize_text_field( $new_instance['display_type'] ) : 'list';
        return $instance;
    }
}
