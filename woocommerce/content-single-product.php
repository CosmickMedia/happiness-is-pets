<?php
/**
 * Single Product Page - Happiness Is Pets
 * Completely reimagined puppy showcase experience
 */

defined( 'ABSPATH' ) || exit;

global $product;
if ( ! $product instanceof WC_Product ) { return; }

// Debug: Print $product for admin user 'cosmick'
//$current_user = wp_get_current_user();
//if ( current_user_can('administrator') && $current_user->user_login === 'cosmick' ) {
 //   echo '<div style="background: #000; color: #0f0; padding: 20px; margin: 20px; font-family: monospace; font-size: 12px; overflow: auto; max-height: 500px;">';
  //  echo '<h3 style="color: #0f0; margin-top: 0;">DEBUG: Global $product Object for ' . esc_html($current_user->user_login) . '</h3>';
  //  echo '<pre style="color: #0f0; white-space: pre-wrap; word-wrap: break-word;">';
  //  print_r($product);
  //  echo '</pre>';
  //  echo '<h4 style="color: #0f0; margin-top: 20px;">Available Methods:</h4>';
  //  echo '<pre style="color: #0f0; white-space: pre-wrap; word-wrap: break-word;">';
  //  print_r(get_class_methods($product));
  //  echo '</pre>';
  //  echo '</div>';
//}

// Gather all product data
$product_id        = $product->get_id();
$product_name      = $product->get_name();
$pet_type          = function_exists('wc_ukm_get_pet_type') ? wc_ukm_get_pet_type( $product_id ) : 'Unknown';
$ref_id            = $product->get_meta('reference_number') ?: $product_id;
$status            = $product->get_status();
$video_url         = $product->get_meta('video');
$pet               = (function_exists('wc_ukm_get_pet') && ($p = wc_ukm_get_pet($product_id))) ? $p : new stdClass();
$pet_name          = $product->get_meta( 'pet_name' ) ?: $product_name;
$birth_date        = ! empty( $pet->dob ) && strtotime($pet->dob) ? date( 'm-d-Y', strtotime( $pet->dob ) ) : 'N/A';
$availability_meta = $product->get_meta('availability_date');
$coming_soon       = ( $status === 'coming_soon' );
$breed             = ! empty( $pet->breed ) ? $pet->breed : 'Unknown Breed';
$age               = ! empty( $pet->age ) ? $pet->age : '';
$gender            = ! empty( $pet->gender ) ? $pet->gender : '';
$color             = ! empty( $pet->color ) ? $pet->color : '';
$location          = ! empty( $pet->location ) ? $pet->location : '';
$current_weight    = $product->get_pet_weight() ? $product->get_pet_weight() . ' lbs' : '';
$est_adult_weight  = $product->get_meta('est_adult_weight') ?: '';
$registry_info     = ! empty( $pet->registry_info ) ? $pet->registry_info : null;

$product_categories = wp_get_post_terms($product_id, 'product_cat');
$category_name = !empty($product_categories) ? $product_categories[0]->name : $breed;

// Dynamic phone number based on location
$location_phone = get_theme_mod('header_phone_number', '317-537-2480'); // Default fallback to Indianapolis
if (!empty($location)) {
    $location_lower = strtolower(trim($location));
    if (strpos($location_lower, 'indianapolis') !== false) {
        $location_phone = get_theme_mod('indianapolis_phone_number', '317-537-2480');
    } elseif (strpos($location_lower, 'schererville') !== false) {
        $location_phone = get_theme_mod('schererville_phone_number', '219-865-3078');
    }
}

// Parents info
$sire_id            = get_post_meta( $product_id, 'sire_registration', true );
$dam_id             = get_post_meta( $product_id, 'dam_registration', true );
$sire_ofa           = $sire_id && function_exists('get_field') ? get_field( 'ofa_tested', $sire_id ) : false;
$dam_ofa            = $dam_id && function_exists('get_field') ? get_field( 'ofa_tested', $dam_id ) : false;
$parents_ofa_tested = ( $sire_ofa && $dam_ofa );
$ofa_tested_label   = $parents_ofa_tested ? 'Yes' : 'No';
$pet_sire           = isset( $pet->sire ) && is_object($pet->sire) ? $pet->sire : new stdClass();
$pet_dam            = isset( $pet->dam ) && is_object($pet->dam) ? $pet->dam : new stdClass();
// Use empty string for missing photos - we'll handle with CSS placeholder
$sire_photo         = ! empty( $pet_sire->photo ) ? esc_url( $pet_sire->photo ) : '';
$dam_photo          = ! empty( $pet_dam->photo ) ? esc_url( $pet_dam->photo ) : '';
$sire_breed         = ! empty( $pet_sire->breed ) ? esc_html( $pet_sire->breed ) : 'Unknown Breed';
$dam_breed          = ! empty( $pet_dam->breed ) ? esc_html( $pet_dam->breed ) : 'Unknown Breed';
$sire_registry      = ! empty( $pet_sire->registry ) ? esc_html( $pet_sire->registry ) : 'N/A';
$dam_registry       = ! empty( $pet_dam->registry ) ? esc_html( $pet_dam->registry ) : 'N/A';
$sire_weight        = ! empty( $pet_sire->weight ) ? esc_html( $pet_sire->weight . ' lbs' ) : 'N/A';
$dam_weight         = ! empty( $pet_dam->weight ) ? esc_html( $pet_dam->weight . ' lbs' ) : 'N/A';

// Status
$status_label = '';
switch ( $status ) {
    case 'publish':
        if ( $product->is_in_stock() ) {
            $status_label = $product->is_on_sale() ? 'Special!' : 'Available Now';
        } else {
            $status_label = 'Adopted';
        }
        break;
    case 'coming_soon': $status_label = 'Reserve Now'; break;
    case 'on_hold': $status_label = 'Reserved'; break;
    case 'reserved_puppy': $status_label = 'Reserved'; break;
    default: $status_label = 'Contact Us'; break;
}

// Breeder & Certifications
$breeder_name = 'N/A';
if ( ! empty( $pet->breeders ) ) {
    $breeder_name = is_array($pet->breeders) ? implode(', ', $pet->breeders) : $pet->breeders;
}
elseif ( isset($pet->breeder_obj) && is_object($pet->breeder_obj) && ! empty( $pet->breeder_obj->company ) ) {
    $breeder_name = $pet->breeder_obj->company;
}
$ccc_from_object = ! empty( $pet->breeder_obj->caninecare_certified );
$ccc_from_acf    = false;
if ( ($breeders = get_the_terms( $product_id, 'breeders' )) && is_array($breeders) ) {
    foreach ( $breeders as $breeder ) {
        if ( function_exists( 'get_field' ) && get_field( 'caninecare_certified', 'breeders_' . $breeder->term_id ) ) {
            $ccc_from_acf = true;
            break;
        }
    }
}
$canine_care_certified = ( $ccc_from_object || $ccc_from_acf );

// Images
$images = [];
if ( has_post_thumbnail( $product_id ) && ($featured_image_url = get_the_post_thumbnail_url( $product_id, 'large' )) ) {
    $images[] = $featured_image_url;
}
if ( function_exists('get_pet_data') && ($pet_images = get_pet_data( $product_id, 'Photos' )) && is_array($pet_images) ) {
    foreach ( $pet_images as $pimg ) {
        if (isset($pimg->BaseUrl, $pimg->Size800)) {
            $image_url = $pimg->BaseUrl . $pimg->Size800;
            if (!in_array($image_url, $images)) {
                $images[] = $image_url;
            }
        }
    }
}
if ( ($gallery_image_ids = $product->get_gallery_image_ids()) ) {
    foreach ( $gallery_image_ids as $gallery_image_id ) {
        if ( ($gimg_url = wp_get_attachment_image_url( $gallery_image_id, 'large' )) && !in_array($gimg_url, $images) ) {
            $images[] = $gimg_url;
        }
    }
}
if ( empty($images) ) {
    // Use a data URI for a placeholder or flag that we need a placeholder
    $images[] = 'placeholder';
}
$img_count = count( $images );

// Pet KB API
$pet_kb_data = null;
if ( function_exists( 'get_pet_kb_api_data' ) ) {
    $pet_kb_data = get_pet_kb_api_data( $product_id );
}

// Color hex map
$color_hex_codes = [
    'black' => '#000000', 'brown' => '#964B00', 'white' => '#FFFFFF',
    'red' => '#A0522D', 'gold' => '#FFD700', 'yellow' => '#FFFF00',
    'gray' => '#808080', 'grey' => '#808080', 'silver' => '#C0C0C0',
    'tan' => '#D2B48C', 'chocolate' => '#D2691E', 'orange' => '#FFA500',
    'purple' => '#800080', 'pink' => '#FFC0CB', 'cream' => '#FFFDD0',
    'apricot' => '#FBCEB1', 'fawn' => '#E5AA70', 'blue' => '#647C9A',
    'wheaten' => '#F5DEB3', 'liver' => '#6C2E1F', 'sienna' => '#A0522D',
];

do_action( 'woocommerce_before_single_product' );
if ( post_password_required() ) { echo get_the_password_form(); return; }
?>

<style>
/* ==========================================================================
   HAPPINESS IS PETS - REIMAGINED PUPPY SHOWCASE
   Fresh, modern, engaging design built from ground up
   ========================================================================== */

:root {
    --brand-turquoise: #00c8ba;
    --brand-turquoise-glow: rgba(0, 200, 186, 0.15);
    --dark-slate: #1a202c;
    --slate: #2d3748;
    --slate-medium: #4a5568;
    --slate-light: #718096;

    --cream-bg: #fffcf7;
    --white: #ffffff;
    --warm-grey: #f7f7f7;

    --radius-sm: 8px;
    --radius-md: 16px;
    --radius-lg: 24px;
    --radius-xl: 32px;

    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --shadow-soft: 0 2px 12px rgba(0, 0, 0, 0.04);
    --shadow-md: 0 4px 24px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 8px 40px rgba(0, 0, 0, 0.08);
    --shadow-turquoise: 0 8px 32px var(--brand-turquoise-glow);
}

* { box-sizing: border-box; }

body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

.pup-wrap {
    max-width: 1400px;
    margin: 0 auto;
    padding: 12px 24px 80px;
    background: var(--cream-bg);
}

/* Hide the default product title that comes from WooCommerce */
.product_title {
    display: none !important;
}

/* Hide the default page title and breadcrumbs from theme */
.main-container .page-title,
.main-container .breadcrumbs,
.woocommerce .page-title,
.woocommerce-products-header {
    display: none !important;
}

/* Breadcrumb styles for WooCommerce breadcrumbs */
.woocommerce-breadcrumb,
.pup-breadcrumb {
    margin-bottom: 16px;
    font-size: 13px;
    color: var(--slate-light);
    padding: 12px 0;
}

.woocommerce-breadcrumb a,
.pup-breadcrumb a {
    color: var(--brand-turquoise);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
}

.woocommerce-breadcrumb a:hover,
.pup-breadcrumb a:hover {
    color: var(--slate);
}

/* ==========================================================================
   HERO GRID - Gallery + Sidebar
   ========================================================================== */

.pup-hero-grid {
    display: grid;
    grid-template-columns: 58% 1fr;
    gap: 40px;
    margin-bottom: 80px;
}

@media (max-width: 1100px) {
    .pup-hero-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }
}

/* Gallery */
.pup-gallery-main {
    position: relative;
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    background: var(--white);
    margin-bottom: 20px;
}

.pup-gallery-main-img {
    width: 100%;
    height: 700px;
    object-fit: cover;
}

@media (max-width: 768px) {
    .pup-gallery-main-img {
        height: 500px;
    }
}

.pup-status-pill {
    position: absolute;
    top: 28px;
    left: 28px;
    background: rgba(0, 0, 0, 0.75);
    backdrop-filter: blur(12px);
    color: white;
    padding: 12px 28px;
    border-radius: 100px;
    font-weight: 800;
    font-size: 13px;
    letter-spacing: 1px;
    text-transform: uppercase;
    z-index: 10;
}

.pup-gallery-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 100%;
    display: flex;
    justify-content: space-between;
    padding: 0 28px;
    z-index: 10;
}

.pup-gallery-arrow {
    background: white;
    border: none;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: var(--shadow-md);
    transition: var(--transition);
}

.pup-gallery-arrow:hover {
    transform: scale(1.1);
    box-shadow: var(--shadow-turquoise);
}

.pup-gallery-arrow i {
    font-size: 20px;
    color: var(--slate);
}

.pup-thumbs-row {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
    gap: 12px;
}

.pup-thumb {
    border-radius: var(--radius-md);
    overflow: hidden;
    cursor: pointer;
    border: 4px solid transparent;
    transition: var(--transition);
    aspect-ratio: 1;
}

.pup-thumb:hover {
    border-color: var(--brand-turquoise);
    transform: translateY(-4px);
    box-shadow: var(--shadow-turquoise);
}

.pup-thumb.active {
    border-color: var(--brand-turquoise);
    box-shadow: var(--shadow-turquoise);
}

.pup-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ==========================================================================
   SIDEBAR
   ========================================================================== */

.pup-sidebar {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.pup-title-card h1 {
    font-size: 48px;
    font-weight: 900;
    color: var(--dark-slate);
    margin: 0 0 8px 0;
    line-height: 1.1;
    letter-spacing: -1px;
}

.pup-title-breed {
    font-size: 22px;
    font-weight: 700;
    color: var(--brand-turquoise);
    margin: 0 0 12px 0;
}

.pup-title-ref {
    font-size: 14px;
    color: var(--slate-medium);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.pup-title-ref strong {
    color: var(--brand-turquoise);
}

/* Action Buttons */
.pup-cta-stack {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.pup-cta-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 20px 28px;
    border-radius: var(--radius-md);
    font-size: 17px;
    font-weight: 800;
    text-decoration: none;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    box-shadow: var(--shadow-md);
}

.pup-cta-primary {
    background: linear-gradient(135deg, var(--brand-turquoise) 0%, #00a89c 100%);
    color: white;
}

.pup-cta-primary:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-turquoise);
}

.pup-cta-secondary {
    background: white;
    color: var(--slate);
    border: 3px solid var(--slate);
}

.pup-cta-secondary:hover {
    background: var(--slate);
    color: white;
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}

/* Quick Facts */
.pup-facts-card {
    background: white;
    padding: 36px;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    border-top: 5px solid var(--brand-turquoise);
}

.pup-facts-title {
    font-size: 18px;
    font-weight: 800;
    color: var(--dark-slate);
    margin: 0 0 28px 0;
    text-transform: uppercase;
    letter-spacing: 1px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.pup-facts-title i {
    color: var(--brand-turquoise);
    font-size: 22px;
}

.pup-fact-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.pup-fact-row:last-child {
    border-bottom: none;
}

.pup-fact-label {
    font-weight: 700;
    color: var(--slate);
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.pup-fact-label i {
    color: var(--brand-turquoise);
    font-size: 18px;
    width: 20px;
}

.pup-fact-value {
    font-weight: 700;
    color: var(--slate-medium);
    font-size: 15px;
}

.pup-fact-value.male { color: #3b82f6; }
.pup-fact-value.female { color: #ec4899; }

/* Video & Certifications */
.pup-video-card {
    background: linear-gradient(135deg, #ff0000 0%, #cc0000 100%);
    padding: 20px;
    border-radius: var(--radius-lg);
    text-align: center;
}

.pup-video-link {
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    font-weight: 800;
    font-size: 16px;
    transition: var(--transition);
}

.pup-video-link:hover {
    transform: scale(1.05);
}

.pup-video-link i {
    font-size: 28px;
}

/* NEW: Modern Certification Grid */
.pup-certs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 16px;
}

.pup-cert-badge {
    background: white;
    padding: 24px 16px;
    border-radius: var(--radius-md);
    text-align: center;
    border: 3px solid transparent;
    transition: var(--transition);
    box-shadow: var(--shadow-soft);
    cursor: pointer;
    text-decoration: none;
    display: block;
}

.pup-cert-badge:hover {
    border-color: var(--brand-turquoise);
    transform: translateY(-6px);
    box-shadow: var(--shadow-turquoise);
}

.pup-cert-icon {
    font-size: 48px;
    margin-bottom: 12px;
    display: block;
}

.pup-cert-badge.certified .pup-cert-icon {
    color: var(--brand-turquoise);
}

.pup-cert-badge.not-certified .pup-cert-icon {
    color: #cbd5e0;
}

.pup-cert-label {
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--slate);
    display: block;
    margin-bottom: 4px;
}

.pup-cert-status {
    font-size: 13px;
    font-weight: 700;
}

.pup-cert-badge.certified .pup-cert-status {
    color: var(--brand-turquoise);
}

.pup-cert-badge.not-certified .pup-cert-status {
    color: var(--slate-light);
}

/* ==========================================================================
   NEW: CARD-BASED CONTENT SECTIONS (No Tabs!)
   ========================================================================== */

.pup-content-flow {
    display: flex;
    flex-direction: column;
    gap: 48px;
}

.pup-section-card {
    background: white;
    padding: 48px;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-lg);
    border-left: 8px solid var(--brand-turquoise);
}

@media (max-width: 768px) {
    .pup-section-card {
        padding: 32px 24px;
    }
}

.pup-section-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 36px;
    padding-bottom: 24px;
    border-bottom: 3px solid var(--brand-turquoise);
}

.pup-section-icon {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, var(--brand-turquoise) 0%, #00a89c 100%);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.pup-section-icon i {
    font-size: 32px;
    color: white;
}

.pup-section-title {
    font-size: 36px;
    font-weight: 900;
    color: var(--dark-slate);
    margin: 0;
    letter-spacing: -0.5px;
}

/* ==========================================================================
   NEW: PARENT SHOWCASE - Split Design
   ========================================================================== */

.pup-parents-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
}

@media (max-width: 900px) {
    .pup-parents-container {
        grid-template-columns: 1fr;
    }
}

.pup-parent-showcase {
    background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
    padding: 40px;
    border-radius: var(--radius-lg);
    position: relative;
    overflow: hidden;
    border: 3px solid transparent;
    transition: var(--transition);
}

.pup-parent-showcase:hover {
    border-color: var(--brand-turquoise);
    transform: translateY(-8px);
    box-shadow: var(--shadow-turquoise);
}

.pup-parent-badge {
    position: absolute;
    top: 24px;
    right: 24px;
    background: var(--brand-turquoise);
    color: white;
    padding: 8px 20px;
    border-radius: 100px;
    font-weight: 800;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.pup-parent-photo-wrap {
    width: 200px;
    height: 200px;
    margin: 0 auto 28px;
    position: relative;
}

.pup-parent-photo {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 6px solid var(--brand-turquoise);
    box-shadow: var(--shadow-lg);
}

/* Placeholder for missing parent photos */
.pup-parent-photo-placeholder {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: linear-gradient(135deg, #f7f7f7 0%, #e9e9e9 100%);
    border: 6px solid var(--brand-turquoise);
    box-shadow: var(--shadow-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 80px;
    color: var(--brand-turquoise);
}

.pup-parent-name {
    text-align: center;
    font-size: 32px;
    font-weight: 900;
    color: var(--dark-slate);
    margin: 0 0 8px 0;
}

.pup-parent-breed {
    text-align: center;
    font-size: 18px;
    color: var(--brand-turquoise);
    font-weight: 700;
    margin: 0 0 24px 0;
}

.pup-parent-details {
    background: white;
    padding: 24px;
    border-radius: var(--radius-md);
}

.pup-parent-detail {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.pup-parent-detail:last-child {
    border-bottom: none;
}

.pup-parent-detail strong {
    color: var(--slate);
    font-weight: 700;
}

.pup-parent-detail span {
    color: var(--slate-medium);
    font-weight: 600;
}

/* ==========================================================================
   BREED INFO SECTION
   ========================================================================== */

.pup-breed-media-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 40px;
}

@media (max-width: 768px) {
    .pup-breed-media-row {
        grid-template-columns: 1fr;
    }
}

.pup-media-frame {
    border-radius: var(--radius-lg);
    overflow: hidden;
    background: var(--warm-grey);
    box-shadow: var(--shadow-md);
}

.pup-media-frame img {
    width: 100%;
    height: 340px;
    object-fit: cover;
}

.pup-media-frame iframe {
    width: 100%;
    height: 340px;
    border: none;
}

.pup-media-empty {
    height: 340px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--slate-light);
    font-weight: 600;
}

/* Stats Showcase */
.pup-stats-showcase {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 20px;
    margin-bottom: 48px;
}

.pup-stat-tile {
    background: linear-gradient(135deg, var(--brand-turquoise) 0%, #00a89c 100%);
    color: white;
    padding: 32px 20px;
    border-radius: var(--radius-lg);
    text-align: center;
    box-shadow: var(--shadow-turquoise);
    transition: var(--transition);
}

.pup-stat-tile:hover {
    transform: translateY(-8px) scale(1.03);
    box-shadow: 0 12px 40px rgba(0, 200, 186, 0.35);
}

.pup-stat-tile i {
    font-size: 42px;
    margin-bottom: 12px;
    opacity: 0.95;
}

.pup-stat-tile strong {
    display: block;
    font-size: 14px;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 1px;
    opacity: 0.9;
}

.pup-stat-tile div {
    font-size: 20px;
    font-weight: 900;
}

/* Traits & Colors */
.pup-info-block {
    margin: 40px 0;
}

.pup-info-block h4 {
    font-size: 22px;
    font-weight: 800;
    color: var(--dark-slate);
    margin: 0 0 20px 0;
}

.pup-traits-flow {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.pup-trait-pill {
    background: linear-gradient(135deg, var(--brand-turquoise) 0%, #00a89c 100%);
    color: white;
    padding: 12px 24px;
    border-radius: 100px;
    font-weight: 800;
    font-size: 14px;
    box-shadow: var(--shadow-soft);
}

.pup-colors-flow {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
}

.pup-color-item {
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--warm-grey);
    padding: 12px 20px;
    border-radius: 100px;
    border: 2px solid transparent;
    font-weight: 700;
    transition: var(--transition);
}

.pup-color-item:hover {
    border-color: var(--brand-turquoise);
    transform: translateY(-2px);
}

.pup-color-dot {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 3px solid rgba(0, 0, 0, 0.1);
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.15);
}

/* Characteristics */
.pup-chars-layout {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 24px;
}

.pup-char-box {
    background: var(--warm-grey);
    padding: 24px;
    border-radius: var(--radius-md);
    border: 2px solid transparent;
    transition: var(--transition);
}

.pup-char-box:hover {
    border-color: var(--brand-turquoise);
    box-shadow: var(--shadow-md);
}

.pup-char-box strong {
    display: block;
    margin-bottom: 12px;
    color: var(--slate);
    font-size: 15px;
    font-weight: 800;
}

.pup-char-meter {
    display: flex;
    gap: 4px;
    height: 12px;
}

.pup-char-dot {
    flex: 1;
    background: rgba(0, 0, 0, 0.1);
    border-radius: 3px;
}

.pup-char-dot.filled {
    background: linear-gradient(135deg, var(--brand-turquoise) 0%, #00a89c 100%);
}

/* Expandable Info */
.pup-expandables {
    margin: 40px 0 0;
}

.pup-expand-item {
    background: var(--warm-grey);
    border-radius: var(--radius-md);
    margin-bottom: 16px;
    overflow: hidden;
    border: 2px solid transparent;
    transition: var(--transition);
}

.pup-expand-item:hover {
    box-shadow: var(--shadow-md);
}

.pup-expand-trigger {
    padding: 24px 28px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--warm-grey);
    transition: var(--transition);
    font-weight: 800;
    font-size: 17px;
    color: var(--slate);
}

.pup-expand-trigger:hover {
    background: #ececec;
}

.pup-expand-trigger.active {
    background: linear-gradient(135deg, var(--brand-turquoise) 0%, #00a89c 100%);
    color: white;
}

.pup-expand-arrow {
    transition: var(--transition);
    font-size: 20px;
}

.pup-expand-trigger.active .pup-expand-arrow {
    transform: rotate(180deg);
}

.pup-expand-content {
    max-height: 0;
    overflow: hidden;
    transition: var(--transition);
}

.pup-expand-content.active {
    max-height: 2000px;
}

.pup-expand-inner {
    padding: 28px;
    background: white;
    line-height: 1.8;
    color: var(--slate-medium);
}

/* ==========================================================================
   CONTACT SECTION
   ========================================================================== */

.pup-contact-notice {
    background: linear-gradient(135deg, #fffcf7 0%, #fff5e6 100%);
    padding: 32px;
    border-radius: var(--radius-lg);
    margin-bottom: 32px;
    border-left: 6px solid var(--brand-turquoise);
}

.pup-contact-notice p {
    margin: 0;
    font-size: 17px;
    color: var(--slate);
    line-height: 1.7;
    font-weight: 500;
}

/* ==========================================================================
   FOOTER
   ========================================================================== */

.pup-related-wrap {
    background: var(--warm-grey);
    padding: 80px 0;
    margin-top: 80px;
}

.pup-related-inner {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 24px;
}

/* NEW: The Difference Section - Redesigned */
.pup-difference-wrap {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    padding: 100px 0;
    margin-top: 80px;
    border-top: 3px solid var(--brand-turquoise);
}

.pup-difference-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
    text-align: center;
}

.pup-difference-content {
    max-width: 800px;
    margin: 0 auto;
}

.pup-difference-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, var(--brand-turquoise) 0%, #00a89c 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 32px;
    box-shadow: var(--shadow-turquoise);
}

.pup-difference-icon i {
    font-size: 48px;
    color: white;
}

.pup-difference-inner h2 {
    font-size: 48px;
    font-weight: 900;
    color: var(--dark-slate);
    margin: 0 0 24px 0;
    letter-spacing: -1px;
}

.pup-difference-inner p {
    font-size: 20px;
    line-height: 1.8;
    color: var(--slate-medium);
    margin: 0 0 40px 0;
    font-weight: 500;
}

.pup-difference-cta {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 24px 48px;
    background: linear-gradient(135deg, var(--brand-turquoise) 0%, #00a89c 100%);
    color: white;
    text-decoration: none;
    border-radius: var(--radius-lg);
    font-size: 18px;
    font-weight: 800;
    box-shadow: var(--shadow-turquoise);
    transition: var(--transition);
}

.pup-difference-cta:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 48px rgba(0, 200, 186, 0.4);
    color: white;
}

.pup-difference-cta i {
    font-size: 22px;
}

@media (max-width: 768px) {
    .pup-difference-inner h2 {
        font-size: 36px;
    }

    .pup-difference-inner p {
        font-size: 18px;
    }
}

/* Modal */
.modal-content {
    border-radius: var(--radius-lg);
    border: none;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
}

.modal-header {
    background: linear-gradient(135deg, var(--brand-turquoise) 0%, #00a89c 100%);
    color: white;
    padding: 28px 32px;
    border: none;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
    opacity: 1;
}

.modal-title {
    font-weight: 900;
    font-size: 24px;
}

.modal-body {
    padding: 36px;
}

/* Responsive */
@media (max-width: 768px) {
    .pup-title-card h1 {
        font-size: 36px;
    }

    .pup-section-title {
        font-size: 28px;
    }

    .pup-section-header {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<div class="pup-wrap" id="product-<?php echo esc_attr($product_id); ?>">

    <!-- Hero Grid -->
    <div class="pup-hero-grid">

        <!-- Gallery -->
        <div class="pup-gallery">
            <div class="pup-gallery-main">
                <?php if ($status_label) : ?>
                    <?php if ($product->get_status() === 'coming_soon') : ?>
                        <a href="#contact-form" class="pup-status-pill text-decoration-none" style="cursor: pointer;">
                            <?php echo esc_html($status_label); ?>
                        </a>
                    <?php elseif ($product->get_status() === 'reserved_puppy') : ?>
                        <div class="pup-status-pill" style="background-color: #fbbf24; color: #78350f;">
                            <?php echo esc_html($status_label); ?>
                        </div>
                    <?php else : ?>
                        <div class="pup-status-pill"><?php echo esc_html($status_label); ?></div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($images[0] === 'placeholder') : ?>
                    <div class="pup-gallery-placeholder d-flex align-items-center justify-content-center bg-light" style="width: 100%; height: 100%; min-height: 500px;">
                        <div class="text-center">
                            <i class="fas fa-paw fa-5x text-muted mb-3"></i>
                            <p class="text-muted mb-0 fs-4"><?php esc_html_e( 'Photo Coming Soon', 'happiness-is-pets' ); ?></p>
                        </div>
                    </div>
                <?php else : ?>
                    <img src="<?php echo esc_url($images[0]); ?>" alt="<?php echo esc_attr($pet_name); ?>" id="mainGalleryImg" class="pup-gallery-main-img" />
                <?php endif; ?>

                <?php if ($img_count > 1) : ?>
                    <div class="pup-gallery-nav">
                        <button class="pup-gallery-arrow" id="galleryPrev"><i class="fas fa-chevron-left"></i></button>
                        <button class="pup-gallery-arrow" id="galleryNext"><i class="fas fa-chevron-right"></i></button>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($img_count > 1) : ?>
                <div class="pup-thumbs-row">
                    <?php foreach ($images as $idx => $img) : ?>
                        <div class="pup-thumb <?php echo $idx === 0 ? 'active' : ''; ?>" data-idx="<?php echo $idx; ?>">
                            <?php if ($img === 'placeholder') : ?>
                                <div class="d-flex align-items-center justify-content-center bg-light" style="width: 100%; height: 100%; min-height: 80px;">
                                    <i class="fas fa-paw text-muted"></i>
                                </div>
                            <?php else : ?>
                                <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($pet_name . ' ' . ($idx + 1)); ?>" />
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="pup-sidebar">

            <!-- Title -->
            <div class="pup-title-card">
                <?php if (function_exists('woocommerce_breadcrumb')) : ?>
                    <div class="pup-breadcrumb-wrap">
                        <?php woocommerce_breadcrumb(); ?>
                    </div>
                <?php endif; ?>
                <h1><?php echo esc_html($pet_name . ' - ' . $ref_id); ?></h1>
                <div class="pup-title-breed"><?php echo esc_html($breed); ?></div>
            </div>

            <!-- CTAs -->
            <div class="pup-cta-stack">
                <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $location_phone)); ?>" class="pup-cta-btn pup-cta-secondary">
                    <i class="fas fa-phone"></i>
                    <span>Call About <?php echo esc_html($pet_name); ?></span>
                </a>
                <a href="#infoModal" data-bs-toggle="modal" class="pup-cta-btn pup-cta-primary">
                    <i class="fas fa-paper-plane"></i>
                    <span>Get More Information</span>
                </a>
            </div>

            <!-- Quick Facts -->
            <div class="pup-facts-card">
                <div class="pup-facts-title">
                    <i class="fas fa-paw"></i>
                    <span>Quick Facts</span>
                </div>

                <?php if (!empty($gender)) : ?>
                <div class="pup-fact-row">
                    <span class="pup-fact-label"><i class="fas fa-venus-mars"></i> Gender</span>
                    <span class="pup-fact-value <?php echo strtolower($gender); ?>"><?php echo esc_html($gender); ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($birth_date) && $birth_date !== 'N/A') : ?>
                <div class="pup-fact-row">
                    <span class="pup-fact-label"><i class="fas fa-calendar"></i> Born</span>
                    <span class="pup-fact-value"><?php echo esc_html($birth_date); ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($color)) : ?>
                <div class="pup-fact-row">
                    <span class="pup-fact-label"><i class="fas fa-palette"></i> Color</span>
                    <span class="pup-fact-value"><?php echo esc_html($color); ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($current_weight)) : ?>
                <div class="pup-fact-row">
                    <span class="pup-fact-label"><i class="fas fa-weight"></i> Current Weight</span>
                    <span class="pup-fact-value"><?php echo esc_html($current_weight); ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($est_adult_weight)) : ?>
                <div class="pup-fact-row">
                    <span class="pup-fact-label"><i class="fas fa-arrows-alt-v"></i> Est. Adult Weight</span>
                    <span class="pup-fact-value"><?php echo esc_html($est_adult_weight); ?> lbs</span>
                </div>
                <?php endif; ?>

                <?php if (!empty($location)) : ?>
                <div class="pup-fact-row">
                    <span class="pup-fact-label"><i class="fas fa-map-marker-alt"></i> Location</span>
                    <span class="pup-fact-value"><?php echo esc_html($location); ?></span>
                </div>
                <?php endif; ?>
            </div>

            <!-- Video -->
            <?php if (!empty($video_url)) : ?>
                <div class="pup-video-card">
                    <a href="#videoModal" data-bs-toggle="modal" data-src="<?php echo esc_url($video_url); ?>" class="pup-video-link">
                        <i class="fab fa-youtube"></i>
                        <span>Watch Video</span>
                    </a>
                </div>
            <?php endif; ?>

            <!-- Certifications Grid -->
            <div class="pup-certs-grid">
                <?php if ($canine_care_certified) : ?>
                <div class="pup-cert-badge certified" data-bs-toggle="modal" href="#canine-care-certified">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/cta-canine-care-certified.png'); ?>" alt="Canine Care Certified" class="pup-cert-logo" style="width: 60px; height: auto; margin-bottom: 8px;">
                    <span class="pup-cert-label">CCC Breeder</span>
                    <span class="pup-cert-status">Certified</span>
                </div>
                <?php endif; ?>

                <?php if ($registry_info && !empty($registry_info['logo']) && !empty($registry_info['website'])) : ?>
                <a href="<?php echo esc_url($registry_info['website']); ?>" target="_blank" rel="noopener noreferrer" class="pup-cert-badge certified">
                    <img src="<?php echo esc_url($registry_info['logo']); ?>" alt="<?php echo esc_attr($registry_info['name'] ?? 'Registry'); ?>" class="pup-cert-logo" style="width: 120px; height: auto; margin-bottom: 8px;">
                    <span class="pup-cert-label"><?php echo esc_html($registry_info['abbr'] ?? 'Registry'); ?></span>
                    <span class="pup-cert-status">Registered</span>
                </a>
                <?php endif; ?>

                <?php if ($parents_ofa_tested) : ?>
                <div class="pup-cert-badge certified" data-bs-toggle="modal" href="#ofa-modal">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/ofa.png'); ?>" alt="OFA Tested" class="pup-cert-logo" style="width: 60px; height: auto; margin-bottom: 8px;">
                    <span class="pup-cert-label">OFA Tested</span>
                    <span class="pup-cert-status">Yes</span>
                </div>
                <?php endif; ?>

                <?php if ($pet_type === 'Dog' && function_exists('get_field') && get_field('puppy_ad_heading', 'option')) : ?>
                <div class="pup-cert-badge certified" data-bs-toggle="modal" href="#warranty">
                    <i class="fas fa-shield-alt pup-cert-icon"></i>
                    <span class="pup-cert-label">Warranty</span>
                    <span class="pup-cert-status">View</span>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <!-- Card-Based Content Flow (No Tabs!) -->
    <div class="pup-content-flow">

        <!-- Contact Section -->
        <div class="pup-section-card" id="contact-form">
            <div class="pup-section-header">
                <div class="pup-section-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h2 class="pup-section-title">Get In Touch</h2>
            </div>

            <div>
                <?php if (shortcode_exists('gravityform')) {
                    echo do_shortcode('[gravityform id="3" title="false" description="false" ajax="true"]');
                } else {
                    echo '<p style="color: #ef4444;">Contact form unavailable.</p>';
                } ?>
            </div>
        </div>




        <!-- Breed Info Section -->
        <?php if (!is_wp_error($pet_kb_data) && !empty($pet_kb_data)) : ?>
        <div class="pup-section-card">
            <div class="pup-section-header">
                <div class="pup-section-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <h2 class="pup-section-title">About The <?php echo esc_html($breed); ?></h2>
            </div>

                <?php
                // Media
                $kb_images = [];
                if (!empty($pet_kb_data['featured_image_url'])) {
                    $kb_images[] = $pet_kb_data['featured_image_url'];
                }
                if (!empty($pet_kb_data['sub_image_urls'])) {
                    $kb_images = array_merge($kb_images, array_slice($pet_kb_data['sub_image_urls'], 0, 1));
                }
                $kb_videos = $pet_kb_data['video_ids'] ?? [];
                ?>

                <?php if (!empty($kb_images) || !empty($kb_videos)) : ?>
                    <div class="pup-breed-media-row">
                        <?php if (!empty($kb_images)) : ?>
                            <div class="pup-media-frame">
                                <img src="<?php echo esc_url($kb_images[0]); ?>" alt="<?php echo esc_attr($pet_kb_data['title']); ?>" />
                            </div>
                        <?php else : ?>
                            <div class="pup-media-frame">
                                <div class="pup-media-empty">No images available</div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($kb_videos)) :
                            $vid = $kb_videos[0];
                            $vid_src = "https://www.youtube.com/embed/" . esc_attr($vid) . "?controls=1&rel=0";
                        ?>
                            <div class="pup-media-frame">
                                <iframe src="<?php echo esc_url($vid_src); ?>" allowfullscreen></iframe>
                            </div>
                        <?php else : ?>
                            <div class="pup-media-frame">
                                <div class="pup-media-empty">No videos available</div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php
                // Stats
                $kb_stats = $pet_kb_data['stats'] ?? [];
                $kb_size = $pet_kb_data['size'] ?? [];
                $has_stats = !empty($kb_stats['weight']['min']) || !empty($kb_stats['height']['min']) ||
                             !empty($kb_stats['lifespan']['min']) || !empty($kb_stats['litter_size']['min']) ||
                             !empty($kb_size);
                ?>

                <?php if ($has_stats) : ?>
                    <div class="pup-stats-showcase">
                        <?php if (!empty($kb_stats['weight']['min']) || !empty($kb_stats['weight']['max'])) : ?>
                            <div class="pup-stat-tile">
                                <i class="fas fa-weight-hanging"></i>
                                <strong>Weight</strong>
                                <div><?php echo esc_html($kb_stats['weight']['min']) . (isset($kb_stats['weight']['max']) && $kb_stats['weight']['max'] ? '-' . esc_html($kb_stats['weight']['max']) : '') . (isset($kb_stats['weight']['unit']) ? ' ' . esc_html($kb_stats['weight']['unit']) : ''); ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($kb_stats['height']['min']) || !empty($kb_stats['height']['max'])) : ?>
                            <div class="pup-stat-tile">
                                <i class="fas fa-ruler-vertical"></i>
                                <strong>Height</strong>
                                <div><?php echo esc_html($kb_stats['height']['min']) . (isset($kb_stats['height']['max']) && $kb_stats['height']['max'] ? '-' . esc_html($kb_stats['height']['max']) : '') . (isset($kb_stats['height']['unit']) ? ' ' . esc_html($kb_stats['height']['unit']) : ''); ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($kb_stats['lifespan']['min']) || !empty($kb_stats['lifespan']['max'])) : ?>
                            <div class="pup-stat-tile">
                                <i class="fas fa-heartbeat"></i>
                                <strong>Lifespan</strong>
                                <div><?php echo esc_html($kb_stats['lifespan']['min']) . (isset($kb_stats['lifespan']['max']) && $kb_stats['lifespan']['max'] ? '-' . esc_html($kb_stats['lifespan']['max']) : '') . (isset($kb_stats['lifespan']['unit']) ? ' ' . esc_html($kb_stats['lifespan']['unit']) : ''); ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($kb_stats['litter_size']['min']) || isset($kb_stats['litter_size']['max'])) : ?>
                            <div class="pup-stat-tile">
                                <i class="fas fa-paw"></i>
                                <strong>Litter Size</strong>
                                <div><?php echo esc_html($kb_stats['litter_size']['min']) . (isset($kb_stats['litter_size']['max']) && $kb_stats['litter_size']['max'] && $kb_stats['litter_size']['max'] != 0 ? '-' . esc_html($kb_stats['litter_size']['max']) : ''); ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($kb_size) && is_array($kb_size)) : ?>
                            <div class="pup-stat-tile">
                                <i class="fas fa-dog"></i>
                                <strong>Size</strong>
                                <div><?php echo esc_html(implode(', ', $kb_size)); ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php
                $kb_traits = $pet_kb_data['traits'] ?? [];
                $kb_colors = $pet_kb_data['colors'] ?? [];
                ?>

                <?php if (!empty($kb_traits)) : ?>
                    <div class="pup-info-block">
                        <h4>Personality Traits</h4>
                        <div class="pup-traits-flow">
                            <?php foreach ($kb_traits as $trait) : ?>
                                <span class="pup-trait-pill"><?php echo esc_html($trait); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($kb_colors)) : ?>
                    <div class="pup-info-block">
                        <h4>Color Varieties</h4>
                        <div class="pup-colors-flow">
                            <?php foreach ($kb_colors as $clr) :
                                $clr_trim = trim($clr);
                                if (empty($clr_trim)) continue;
                                $clr_lower = strtolower($clr_trim);
                                $hex = $color_hex_codes[$clr_lower] ?? '#cccccc';
                            ?>
                                <div class="pup-color-item">
                                    <span class="pup-color-dot" style="background-color: <?php echo esc_attr($hex); ?>;"></span>
                                    <?php echo esc_html($clr_trim); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php $kb_chars = $pet_kb_data['characteristics'] ?? []; ?>

                <?php if (!empty($kb_chars)) : ?>
                    <div class="pup-info-block">
                        <h4>Characteristics</h4>
                        <div class="pup-chars-layout">
                            <?php foreach ($kb_chars as $label => $rating) : ?>
                                <div class="pup-char-box">
                                    <strong><?php echo esc_html($label); ?></strong>
                                    <div class="pup-char-meter">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <div class="pup-char-dot <?php echo ($rating >= $i) ? 'filled' : ''; ?>"></div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php $kb_descs = $pet_kb_data['descriptions'] ?? []; ?>

                <?php if (!empty($kb_descs)) : ?>
                    <div class="pup-expandables">
                        <?php foreach ($kb_descs as $idx => $desc) :
                            $desc_title = trim($desc['title'] ?? '');
                            $desc_title = !empty($desc_title) ? $desc_title : 'Details ' . ($idx + 1);
                            $desc_content = trim($desc['content'] ?? '');
                            if (empty($desc_content)) continue;
                        ?>
                            <div class="pup-expand-item">
                                <div class="pup-expand-trigger" data-target="desc-<?php echo $idx; ?>">
                                    <span><?php echo esc_html($desc_title); ?></span>
                                    <i class="fas fa-chevron-down pup-expand-arrow"></i>
                                </div>
                                <div class="pup-expand-content" id="desc-<?php echo $idx; ?>">
                                    <div class="pup-expand-inner">
                                        <?php echo wp_kses_post(wpautop($desc_content)); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php
                $kb_faqs = $pet_kb_data['faqs'] ?? [];
                $valid_faqs = [];
                ?>

                <?php if (!empty($kb_faqs)) : ?>
                    <div class="pup-expandables">
                        <?php foreach ($kb_faqs as $idx => $faq) :
                            $q = trim($faq['question'] ?? '');
                            $a = trim($faq['answer'] ?? '');
                            if (empty($q) || empty($a)) continue;
                            $valid_faqs[] = ['question' => $q, 'answer' => $a];
                        ?>
                            <div class="pup-expand-item">
                                <div class="pup-expand-trigger" data-target="faq-<?php echo $idx; ?>">
                                    <span><?php echo esc_html($q); ?></span>
                                    <i class="fas fa-chevron-down pup-expand-arrow"></i>
                                </div>
                                <div class="pup-expand-content" id="faq-<?php echo $idx; ?>">
                                    <div class="pup-expand-inner">
                                        <?php echo wp_kses_post(wpautop($a)); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php if (!empty($valid_faqs)) : ?>
                            <script type="application/ld+json">
                            <?php
                            $schema = [
                                '@context' => 'https://schema.org',
                                '@type' => 'FAQPage',
                                'mainEntity' => []
                            ];
                            foreach ($valid_faqs as $faq) {
                                $schema['mainEntity'][] = [
                                    '@type' => 'Question',
                                    'name' => wp_strip_all_tags($faq['question']),
                                    'acceptedAnswer' => [
                                        '@type' => 'Answer',
                                        'text' => wp_strip_all_tags($faq['answer'])
                                    ]
                                ];
                            }
                            echo json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                            ?>
                            </script>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

        </div>
        <?php endif; ?>

    </div>

</div>

<!-- Related Products -->
<?php
// Check if there are related products before showing the section
$related_products = wc_get_related_products($product_id, 4);
if (!empty($related_products)) :
?>
<div class="pup-related-wrap">
    <div class="pup-related-inner">
        <?php woocommerce_output_related_products(); ?>
    </div>
</div>
<?php endif; ?>

<!-- Difference Section -->
<div class="pup-difference-wrap">
    <div class="pup-difference-inner">
        <div class="pup-difference-content">
            <div class="pup-difference-icon">
                <i class="fas fa-heart"></i>
            </div>
            <h2>The Happiness Is Pets Difference</h2>
            <p>We're not just a pet store—we're a family dedicated to connecting you with healthy, happy puppies from trusted breeders. Experience the care, quality, and commitment that sets us apart.</p>
            <?php
            $about_page = get_page_by_title('About Us');
            $about_url = $about_page ? get_permalink($about_page) : home_url('/about-us/');
            ?>
            <a href="<?php echo esc_url($about_url); ?>" class="pup-difference-cta">
                <i class="fas fa-arrow-right"></i>
                <span>Discover Our Story</span>
            </a>
        </div>
    </div>
</div>

<!-- Video Modal -->
<?php if ($video_url) : ?>
<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Watch <?php echo esc_html($pet_name); ?> Play!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe src="" allow="autoplay; fullscreen" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Info Modal -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Get More Information About <?php echo esc_html($pet_name); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?php if (shortcode_exists('gravityform')) {
                    echo do_shortcode('[gravityform id="3" title="false" description="false" ajax="true"]');
                } else {
                    echo '<p style="color: #ef4444;">Form unavailable.</p>';
                } ?>
            </div>
        </div>
    </div>
</div>

<?php
// Include other modals
$contact_path = get_template_directory() . '/inc/modal-form.php';
if (file_exists($contact_path)) include($contact_path);

if ($canine_care_certified) {
    $ccc_path = get_template_directory() . '/inc/ccc.php';
    if (file_exists($ccc_path)) include($ccc_path);
}

if ($pet_type === 'Dog' && function_exists('get_field') && get_field('puppy_ad_heading', 'option')) {
    $warranty_path = get_template_directory() . '/inc/modal-warranty.php';
    if (file_exists($warranty_path)) include($warranty_path);
}

if ($parents_ofa_tested) {
    $ofa_path = get_template_directory() . '/inc/modal-ofa.php';
    if (file_exists($ofa_path)) include($ofa_path);
}
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Populate Gravity Form fields with product data
    setTimeout(function() {
        console.log('🐾 Populating Gravity Form on single product page');

        const productData = {
            petName: <?php echo json_encode($pet_name); ?>,
            refId: <?php echo json_encode($ref_id); ?>,
            breed: <?php echo json_encode($breed); ?>,
            gender: <?php echo json_encode($gender); ?>,
            birthDate: <?php echo json_encode($birth_date); ?>,
            location: <?php echo json_encode($location); ?>,
            productUrl: <?php echo json_encode(get_permalink()); ?>
        };

        console.log('📦 Product data:', productData);

        const fieldMap = {
            'gf-pet-name': productData.petName,
            'gf-ref-id': productData.refId,
            'gf-breed': productData.breed,
            'gf-gender': productData.gender,
            'gf-birth-date': productData.birthDate,
            'gf-location': productData.location,
            'gf-product-url': productData.productUrl
        };

        // Find and populate fields
        let foundCount = 0;
        Object.keys(fieldMap).forEach(function(className) {
            const value = fieldMap[className];
            let field = null;

            // Method 1: Look for gfield wrapper with the CSS class
            let gfieldWrapper = document.querySelector('.gfield.' + className);
            if (gfieldWrapper) {
                console.log('   Found gfield wrapper for ' + className);
                field = gfieldWrapper.querySelector('input, textarea, select');
            }

            // Method 2: Look for the class on any li element
            if (!field) {
                let liWrapper = document.querySelector('li.' + className);
                if (liWrapper) {
                    console.log('   Found li wrapper for ' + className);
                    field = liWrapper.querySelector('input, textarea, select');
                }
            }

            // Method 3: Look for ginput_container with the class
            if (!field) {
                let fieldContainer = document.querySelector('.ginput_container.' + className);
                if (fieldContainer) {
                    console.log('   Found ginput_container for ' + className);
                    field = fieldContainer.querySelector('input, textarea, select');
                }
            }

            // Method 4: Direct input/textarea/select with the class
            if (!field) {
                field = document.querySelector('input.' + className + ', textarea.' + className + ', select.' + className);
                if (field) {
                    console.log('   Found direct input for ' + className);
                }
            }

            if (field) {
                console.log('🔍 Found field for ' + className + ':', field);
                field.value = value;
                field.setAttribute('value', value);
                foundCount++;

                // Trigger events
                if (window.jQuery && jQuery(field).length) {
                    console.log('   Using jQuery to set value');
                    jQuery(field).val(value).trigger('input').trigger('change').trigger('blur');
                } else {
                    console.log('   Using native JS to trigger events');
                    field.dispatchEvent(new Event('input', { bubbles: true }));
                    field.dispatchEvent(new Event('change', { bubbles: true }));
                    field.dispatchEvent(new Event('blur', { bubbles: true }));
                }

                console.log('✅ Populated field:', className, '=', value);
            } else {
                console.warn('⚠️ Field not found for class:', className);
            }
        });

        console.log('📊 Summary: Populated ' + foundCount + ' out of ' + Object.keys(fieldMap).length + ' fields');
    }, 500);

    // Gallery
    const imgs = <?php echo json_encode($images); ?>;
    let currIdx = 0;
    const mainImg = document.getElementById('mainGalleryImg');
    const thumbs = document.querySelectorAll('.pup-thumb');

    function updateGal(idx) {
        currIdx = idx;
        if (mainImg) mainImg.src = imgs[idx];
        thumbs.forEach((t, i) => {
            t.classList.toggle('active', i === idx);
        });
    }

    document.getElementById('galleryPrev')?.addEventListener('click', () => {
        currIdx = (currIdx - 1 + imgs.length) % imgs.length;
        updateGal(currIdx);
    });

    document.getElementById('galleryNext')?.addEventListener('click', () => {
        currIdx = (currIdx + 1) % imgs.length;
        updateGal(currIdx);
    });

    thumbs.forEach((thumb, idx) => {
        thumb.addEventListener('click', () => updateGal(idx));
    });

    // Expandables
    document.querySelectorAll('.pup-expand-trigger').forEach(trigger => {
        trigger.addEventListener('click', () => {
            const target = trigger.dataset.target;
            const content = document.getElementById(target);
            trigger.classList.toggle('active');
            content?.classList.toggle('active');
        });
    });

    // Video Modal
    const vidModal = document.getElementById('videoModal');
    if (vidModal) {
        const iframe = vidModal.querySelector('iframe');
        vidModal.addEventListener('show.bs.modal', (e) => {
            const btn = e.relatedTarget;
            const src = btn?.getAttribute('data-src');
            if (src && iframe) {
                try {
                    const url = new URL(src);
                    if (url.hostname.includes('youtube') || url.hostname.includes('youtu.be')) {
                        const vid = url.searchParams.get('v') || url.pathname.split('/').pop();
                        if (vid) iframe.src = `https://www.youtube-nocookie.com/embed/${vid}?autoplay=1`;
                    }
                } catch {}
            }
        });
        vidModal.addEventListener('hidden.bs.modal', () => {
            if (iframe) iframe.src = '';
        });
    }
});
</script>

<?php do_action('woocommerce_after_single_product'); ?>
