<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package happiness-is-pets
 */

get_header();
?>

<main id="primary" class="site-main py-5">
    <div class="container text-center">
        <div class="d-flex flex-column align-items-center">
            <div class="error-404-logo mb-4 text-center">
                <?php
                if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
                    the_custom_logo();
                } else {
                    $logo_url = get_template_directory_uri() . '/assets/images/logo_horizontal.png';
                    echo '<img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '">';
                }
                ?>
            </div>
            <h1 class="display-5 fw-bold"><?php esc_html_e( 'We are sorry we must be dreaming, but this page cannot be found.', 'happiness-is-pets' ); ?></h1>
        </div>
    </div>
</main>

<?php
get_footer();
