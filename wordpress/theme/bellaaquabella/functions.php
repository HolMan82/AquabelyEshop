<?php
if (!defined('ABSPATH')) { exit; }
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-lightbox');
    register_nav_menus(['primary' => 'Hlavní menu']);
});
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('bella-store', get_template_directory_uri() . '/assets/store.css', [], '0.2.0');
});
function bella_logo() {
    return '<img src="' . esc_url(get_template_directory_uri() . '/assets/beaqua-logo.png') . '" alt="BeAqua — BellaAquaBella">';
}
function bella_icon($name) {
    $paths = ['bag' => '<path d="M5 8h14l1 13H4L5 8Z M8 9V6a4 4 0 0 1 8 0v3"/>', 'user' => '<circle cx="12" cy="7" r="4"/><path d="M4 22v-3a8 8 0 0 1 16 0v3"/>'];
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">' . ($paths[$name] ?? '') . '</svg>';
}
add_filter('woocommerce_product_get_image', function ($html, $product) {
    $sku = $product->get_sku();
    if (!$product->get_image_id() && preg_match('/^bella-(wave|bloom|cap|goggles|nose|towel|bag)$/', $sku)) {
        return '<img width="400" height="400" class="attachment-woocommerce_thumbnail" src="' . esc_url(get_template_directory_uri() . '/assets/' . substr($sku,6) . '.svg') . '" alt="' . esc_attr($product->get_name()) . '" loading="lazy">';
    }
    return $html;
}, 10, 2);
add_filter('woocommerce_single_product_image_thumbnail_html', function ($html, $id) {
    global $product;
    if (!$id && $product) { return '<div class="bella-product-illustration">' . $product->get_image() . '</div>'; }
    return $html;
}, 10, 2);
add_filter('woocommerce_account_menu_items', function ($items) {
    if (current_user_can('manage_woocommerce')) {
        $items['bella-orders'] = 'Správa objednávek';
    }
    return $items;
});
add_filter('woocommerce_get_endpoint_url', function ($url, $endpoint) {
    if ($endpoint === 'bella-orders' && current_user_can('manage_woocommerce')) {
        return admin_url('admin.php?page=wc-orders');
    }
    return $url;
}, 10, 2);
