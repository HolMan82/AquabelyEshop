<?php
if (!defined('ABSPATH')) { exit; }

function bella_brands_url() {
    $page=get_page_by_path('znacky');
    if ($page && $page->post_status==='publish' && has_shortcode($page->post_content,'bella_brands')) {
        return get_permalink($page);
    }
    // Query-based fallback works immediately, without saving rewrite rules.
    return add_query_arg('bella_brands','1',home_url('/'));
}

function bella_render_brands() {
    ob_start();
    include get_template_directory().'/template-parts/brands-list.php';
    return ob_get_clean();
}
add_shortcode('bella_brands','bella_render_brands');
add_filter('query_vars',function ($vars) { $vars[]='bella_brands'; return $vars; });
add_filter('template_include',function ($template) {
    return get_query_var('bella_brands')==='1' ? get_template_directory().'/brands.php' : $template;
});
add_filter('redirect_canonical',function ($redirect) {
    return get_query_var('bella_brands')==='1' ? false : $redirect;
});
add_filter('document_title_parts',function ($parts) {
    if (get_query_var('bella_brands')==='1') { $parts=['title'=>'Značky','site'=>get_bloginfo('name')]; }
    return $parts;
});

add_filter('get_canonical_url',function ($url) {
    return get_query_var('bella_brands')==='1' ? bella_brands_url() : $url;
});
