<?php
if (wp_get_environment_type() !== 'local') { WP_CLI::error('Demo catalog updates are restricted to local development.'); }
$catalog = json_decode(file_get_contents('/setup/catalog.json'), true);
foreach ($catalog as $i=>$data) {
    $id = wc_get_product_id_by_sku('bella-'.$data['id']);
    $variable = count($data['sizes']) > 1;
    $product = $id ? wc_get_product($id) : ($variable ? new WC_Product_Variable() : new WC_Product_Simple());
    $term = term_exists($data['category'],'product_cat');
    if (!$term) { $term = wp_insert_term($data['category'],'product_cat'); }
    if (is_wp_error($term)) { WP_CLI::error($term->get_error_message()); }
    $product->set_name($data['name']);
    $product->set_sku('bella-'.$data['id']);
    $product->set_status('publish');
    $product->set_description($data['description']);
    $product->set_short_description('Ukázkový produkt. Odstín: '.$data['color'].'.');
    $product->set_category_ids([(int)$term['term_id']]);
    $product->set_menu_order($i);
    if (!$id) { $product->set_manage_stock(true); $product->set_stock_quantity(20); }
    if (!$variable) { $product->set_regular_price((string)$data['price']); }
    else {
        $attr = new WC_Product_Attribute();$attr->set_name('Velikost');$attr->set_options($data['sizes']);$attr->set_visible(true);$attr->set_variation(true);$product->set_attributes([$attr]);
    }
    $id = $product->save();
    if ($variable) {
        $variations=[];
        foreach ($product->get_children() as $child) { $v=wc_get_product($child);$values=$v->get_attributes();$variations[$values['velikost']??'']=$v; }
        foreach ($data['sizes'] as $size) {
            $variation=$variations[$size]??new WC_Product_Variation();$variation->set_parent_id($id);$variation->set_attributes(['velikost'=>$size]);$variation->set_regular_price((string)$data['price']);$variation->save();
        }
        WC_Product_Variable::sync($id);
    }
    if ($data['tag']==='Sada') { wp_set_object_terms($id,'Výhodné balíčky','product_cat',true); }
    if ($data['tag']==='Novinka') { wp_set_object_terms($id,'Novinky','product_cat',true); }
}
update_option('bella_catalog_v2', 'yes');
WP_CLI::success('Synchronized 12 local demo products and reference prices; existing orders preserved.');
