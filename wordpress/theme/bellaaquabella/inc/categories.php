<?php
if (!defined('ABSPATH')) { exit; }

/** Stable slots shared by the navigation, homepage tiles and Customizer. */
function bella_category_slots() {
    return [
        'swimwear'=>['name'=>'Plavky','asset'=>'wave','tile'=>true],
        'caps'=>['name'=>'Plavecké čepice','asset'=>'cap','tile'=>true],
        'nose'=>['name'=>'Klipy na nos','asset'=>'nose','tile'=>true],
        'goggles'=>['name'=>'Plavecké brýle','asset'=>'goggles','tile'=>true],
        'hair'=>['name'=>'Vlasové pomůcky','asset'=>'pins','tile'=>true],
        'training'=>['name'=>'Tréninkové pomůcky','asset'=>'board','tile'=>true],
        'accessories'=>['name'=>'Doplňky','asset'=>'bag','tile'=>false],
        'bundles'=>['name'=>'Výhodné balíčky','asset'=>'bag','tile'=>false],
        'new'=>['name'=>'Novinky','asset'=>'wave','tile'=>false],
    ];
}

function bella_sanitize_category_id($value) {
    $id = absint($value);
    return $id && term_exists($id,'product_cat') ? $id : 0;
}

function bella_category_slot($key) {
    $slots = bella_category_slots();
    if (!isset($slots[$key])) { return null; }
    $slot = $slots[$key];
    $id = absint(get_theme_mod('bella_category_'.$key,0));
    $term = $id ? get_term($id,'product_cat') : get_term_by('name',$slot['name'],'product_cat');
    if (is_wp_error($term)) { $term = false; }
    $url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/');
    if ($term) {
        $term_url = get_term_link($term);
        if (!is_wp_error($term_url)) { $url = $term_url; }
    }
    $image = get_template_directory_uri().'/assets/'.$slot['asset'].'.svg';
    if ($term) {
        $thumbnail_id = absint(get_term_meta($term->term_id,'thumbnail_id',true));
        $thumbnail_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id,'medium') : false;
        if ($thumbnail_url) { $image = $thumbnail_url; }
    }
    $custom_label = trim((string)get_theme_mod('bella_category_label_'.$key,''));
    return array_merge($slot,[
        'label'=>$custom_label !== '' ? $custom_label : ($term ? $term->name : $slot['name']),
        'url'=>$url,
        'image'=>$image,
        'term_id'=>$term ? (int)$term->term_id : 0,
    ]);
}
