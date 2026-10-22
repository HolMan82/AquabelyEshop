<?php
if (wp_get_environment_type() !== 'local') { WP_CLI::error('Validation is only allowed in the local development environment.'); }
$product_id = wc_get_product_id_by_sku('bella-nose');
$product = wc_get_product($product_id);
if (!$product || (float)$product->get_price() !== 190.0) { WP_CLI::error('Demo product is missing or has the wrong price.'); }
$owner = get_user_by('login','shopowner');
if (!$owner || !user_can($owner,'manage_woocommerce')) { WP_CLI::error('Owner cannot manage orders.'); }
$stripe = get_option('woocommerce_stripe_settings');
if (($stripe['enabled'] ?? '') !== 'no' || ($stripe['testmode'] ?? '') !== 'yes') { WP_CLI::error('Stripe must remain disabled in test mode until connected.'); }
$order = wc_create_order();
try {
    $order->add_product($product,2);
    $order->set_billing_email('smoke-test@example.test');
    $order->set_payment_method('cod');
    $order->calculate_totals();
    $order->set_status('on-hold');
    $order->save();
    $read = wc_get_order($order->get_id());
    if (!$read || (float)$read->get_total() !== 380.0 || $read->get_status() !== 'on-hold') { throw new Exception('Order persistence or total is wrong.'); }
    WP_CLI::success('Order stored and retrieved with correct total; owner permission verified; Stripe disabled in test mode.');
} finally {
    $order->delete(true);
}
