<?php
if (!defined('ABSPATH')) { exit; }
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/categories.php';
require_once get_template_directory() . '/inc/brands.php';
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-lightbox');
    register_nav_menus(['primary' => 'Hlavní menu']);
});
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('bella-storefront', get_template_directory_uri() . '/assets/storefront.css', [], '0.5.0');
    wp_enqueue_style('bella-woo', get_template_directory_uri() . '/assets/woo.css', ['bella-storefront'], '0.6.0');
    wp_enqueue_script('bella-theme', get_template_directory_uri() . '/assets/theme.js', ['jquery','wc-add-to-cart-variation'], '0.6.0', true);
});
function bella_logo() {
    if (file_exists(get_template_directory() . '/assets/reference-logo.png')) {
        return '<img src="' . esc_url(get_template_directory_uri() . '/assets/reference-logo.png') . '" alt="BellaAquaBella">';
    }
    return '<span class="brand-wordmark">Bella<span>Aqua</span>Bella</span>';
}
function bella_icon($name) {
    $paths = ['bag' => '<path d="M5 8h14l1 13H4L5 8Z M8 9V6a4 4 0 0 1 8 0v3"/>', 'search' => '<circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 5 5"/>', 'user' => '<circle cx="12" cy="7" r="4"/><path d="M4 22v-3a8 8 0 0 1 16 0v3"/>'];
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">' . ($paths[$name] ?? '') . '</svg>';
}
add_filter('woocommerce_product_get_image', function ($html, $product) {
    $sku = $product->get_sku();
    if (!$product->get_image_id() && preg_match('/^bella-(wave|bloom|cap|goggles|nose|towel|bag|gelatin|pins|textile-cap|child-cap|board)$/', $sku)) {
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

function bella_category_link($name) {
    foreach (bella_category_slots() as $key=>$slot) {
        if ($slot['name'] === $name) { return bella_category_slot($key)['url']; }
    }
    $term = get_term_by('name',$name,'product_cat');
    if ($term && !is_wp_error($term)) {
        $url = get_term_link($term);
        if (!is_wp_error($url)) { return $url; }
    }
    return function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/');
}
function bella_reference_icon($name) {
    $paths = [
        'suit'=>'<path d="M7 3h3l1 6h2l1-6h3v8l2 7-4 4-3-4-3 4-4-4 2-7Z"/>',
        'goggles'=>'<path d="M2 10q-1-7 4-6h12q5 0 4 7M2 10h7l-1 6q-5 3-6-2Zm13 0h7v4q-1 5-7 2Zm-6 2q3-3 6 0"/>',
        'nose'=>'<path d="m7 20-2-10a7 7 0 0 1 14 0l-2 10M5 19l3-1m8 0 3 1"/>',
        'towel'=>'<path d="m6 2 14 2-3 18-14-2Zm-2 15 14 2M6 5l11 1"/>',
        'bag'=>'<path d="M5 8h14l1 13H4L5 8Z M8 9V6a4 4 0 0 1 8 0v3"/>',
        'star'=>'<path d="m12 2 3 7 8 1-6 5 2 8-7-4-7 4 2-8-6-5 8-1Z"/>',
        'diamond'=>'<path d="m2 7 5-5h10l5 5-10 15Zm0 0h20M7 2l5 20 5-20M7 7l5-5 5 5"/>',
        'heart'=>'<path d="M20 5c-3-3-7 0-8 2-1-2-5-5-8-2-5 5 8 15 8 15S25 10 20 5Z"/>',
        'wave'=>'<path d="M2 9c4-6 6 6 10 0s6 6 10 0M2 16c4-6 6 6 10 0s6 6 10 0"/>',
        'lotus'=>'<path d="M12 21C4 15 9 4 12 2c3 2 8 13 0 19Zm0 0C3 21 1 13 2 8c6 1 10 6 10 13Zm0 0c9 0 11-8 10-13-6 1-10 6-10 13Z"/>',
        'leaf'=>'<path d="M3 21 20 3M6 18C-2 8 11 3 21 2c-1 10-6 23-15 16Z"/>'
    ];
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($paths[$name] ?? '') . '</svg>';
}

add_action('woocommerce_after_shop_loop_item', function () {
    global $product;
    if (!$product) { return; }
    echo '<button class="wp-favorite" type="button" data-wp-favorite="' . esc_attr($product->get_id()) . '" data-wp-name="' . esc_attr($product->get_name()) . '" data-wp-url="' . esc_url(get_permalink($product->get_id())) . '" aria-label="Přidat do oblíbených: ' . esc_attr($product->get_name()) . '" aria-pressed="false">' . bella_reference_icon('heart') . '</button>';
});
