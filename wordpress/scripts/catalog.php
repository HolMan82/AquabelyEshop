<?php
if (!class_exists('WooCommerce')) { WP_CLI::error('WooCommerce is required.'); }
if (!get_option('bella_price_format_initialized')) {
    update_option('woocommerce_price_num_decimals', 0);
    update_option('woocommerce_currency_pos', 'right_space');
    update_option('woocommerce_price_thousand_sep', ' ');
    update_option('woocommerce_price_decimal_sep', ',');
    update_option('bella_price_format_initialized', true);
}
if (get_option('bella_catalog_initialized')) {
    WP_CLI::success('Existing catalog and store settings preserved.');
    return;
}
update_option('blog_public', 0);
update_option('woocommerce_coming_soon', 'no');
update_option('woocommerce_stripe_settings', ['enabled'=>'no','testmode'=>'yes','title'=>'Platba kartou','description'=>'Bezpečná platba kartou přes Stripe.']);
update_option('woocommerce_currency', 'CZK');
update_option('woocommerce_default_country', 'CZ');
update_option('woocommerce_allowed_countries', 'specific');
update_option('woocommerce_specific_allowed_countries', ['CZ']);
update_option('woocommerce_calc_taxes', 'no');
update_option('woocommerce_enable_guest_checkout', 'yes');
update_option('woocommerce_enable_myaccount_registration', 'no');
update_option('woocommerce_cod_settings', ['enabled'=>'yes', 'title'=>'Testovací objednávka — bez platby', 'description'=>'Pouze vývojová zkouška. Nic neplaťte; objednávka nebude odeslána.', 'instructions'=>'Toto je pouze testovací objednávka.', 'enable_for_virtual'=>'yes']);
update_option('woocommerce_store_address', 'Testovací ulice 1');
update_option('woocommerce_store_city', 'Praha');
update_option('woocommerce_store_postcode', '11000');
update_option('woocommerce_onboarding_profile', ['completed'=>true]);
WC_Install::create_pages();
foreach (['cart'=>'[woocommerce_cart]', 'checkout'=>'[woocommerce_checkout]', 'myaccount'=>'[woocommerce_my_account]'] as $key => $shortcode) {
    wp_update_post(['ID'=>wc_get_page_id($key), 'post_content'=>$shortcode]);
}
$home = wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_title'=>'Úvod','post_content'=>'']);
update_option('show_on_front','page');
update_option('page_on_front',$home);
$zone = new WC_Shipping_Zone();
$zone->set_zone_name('Testovací doprava v ČR');
$zone->add_location('CZ','country');
$zone->save();
$instance = $zone->add_shipping_method('free_shipping');
update_option('woocommerce_free_shipping_'.$instance.'_settings',['enabled'=>'yes','title'=>'Testovací doprava — bez odeslání','requires'=>'']);
$catalog = json_decode(file_get_contents('/setup/catalog.json'), true);
foreach ($catalog as $i => $data) {
    if (wc_get_product_id_by_sku('bella-'.$data['id'])) { continue; }
    $term = term_exists($data['category'], 'product_cat');
    if (!$term) { $term = wp_insert_term($data['category'], 'product_cat'); }
    if (is_wp_error($term)) { WP_CLI::error($term->get_error_message()); }
    $variable = count($data['sizes']) > 1;
    $product = $variable ? new WC_Product_Variable() : new WC_Product_Simple();
    $product->set_name($data['name']);
    $product->set_sku('bella-'.$data['id']);
    $product->set_status('publish');
    $product->set_description($data['description'].' Ukázkový produkt, prodej není spuštěný.');
    $product->set_short_description('Odstín: '.$data['color'].'. Ilustrační zboží pro testování obchodu.');
    $product->set_menu_order($i);
    $product->set_category_ids([(int)$term['term_id']]);
    $product->set_manage_stock(true);
    $product->set_stock_quantity(20);
    if (!$variable) { $product->set_regular_price((string)$data['price']); }
    if ($variable) {
        $attr = new WC_Product_Attribute();
        $attr->set_name('Velikost');
        $attr->set_options($data['sizes']);
        $attr->set_visible(true);
        $attr->set_variation(true);
        $product->set_attributes([$attr]);
    }
    $id = $product->save();
    if ($variable) {
        foreach ($data['sizes'] as $size) {
            $variation = new WC_Product_Variation();
            $variation->set_parent_id($id);
            $variation->set_attributes(['velikost'=>$size]);
            $variation->set_regular_price((string)$data['price']);
            $variation->save();
        }
        WC_Product_Variable::sync($id);
    }
}
update_option('bella_catalog_initialized', true);
WP_CLI::success('Initialized seven demo products, size variations, account, cart, checkout and test-only payment.');
