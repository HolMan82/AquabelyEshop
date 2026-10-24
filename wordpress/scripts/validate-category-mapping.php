<?php
if (wp_get_environment_type() !== 'local') { WP_CLI::error('Category validation is only allowed in local development.'); }
$mods=(array)get_theme_mods();
$keys=['bella_category_caps','bella_category_label_caps'];
$term_id=0;
try {
    $created=wp_insert_term('Testovací kategorie '.wp_generate_password(6,false),'product_cat');
    if (is_wp_error($created)) { throw new Exception($created->get_error_message()); }
    $term_id=(int)$created['term_id'];
    set_theme_mod('bella_category_caps',$term_id);
    set_theme_mod('bella_category_label_caps','');
    $slot=bella_category_slot('caps');
    if ($slot['term_id']!==$term_id || $slot['url']!==get_term_link($term_id,'product_cat')) { throw new Exception('The tile does not link to its selected WooCommerce category.'); }
    wp_update_term($term_id,'product_cat',['name'=>'Přejmenovaná kategorie','slug'=>'bella-category-test-'. $term_id]);
    $slot=bella_category_slot('caps');
    if ($slot['label']!=='Přejmenovaná kategorie' || $slot['url']!==get_term_link($term_id,'product_cat')) { throw new Exception('Rename broke the category association.'); }
    if (bella_category_link('Plavecké čepice')!==$slot['url']) { throw new Exception('Shared category link does not match the tile.'); }
    set_theme_mod('bella_category_label_caps','Vlastní název');
    if (bella_category_slot('caps')['label']!=='Vlastní název') { throw new Exception('Custom category label is not respected.'); }
    if (bella_sanitize_category_id(999999999)!==0) { throw new Exception('Invalid category ID was accepted.'); }
    WP_CLI::success('Explicit category mapping, shared links, rename-safe IDs, custom labels and invalid IDs verified.');
} finally {
    foreach ($keys as $key) { array_key_exists($key,$mods) ? set_theme_mod($key,$mods[$key]) : remove_theme_mod($key); }
    if ($term_id) { wp_delete_term($term_id,'product_cat'); }
}
