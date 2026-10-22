<?php
if (wp_get_environment_type() !== 'local') { WP_CLI::error('Local environment only.'); }
$orders = wc_get_orders(['billing_email'=>'smoke-checkout@example.test','limit'=>10]);
if (!$orders) { WP_CLI::error('Customer checkout did not create an order.'); }
$order = $orders[0];
if ((float)$order->get_total() !== 190.0 || $order->get_payment_method() !== 'cod') { WP_CLI::error('Unexpected checkout total or payment method.'); }
$order->set_created_via('bella-smoke');
$order->save();
WP_CLI::success('Customer checkout order is visible in WooCommerce: #'.$order->get_id().', status '.$order->get_status().', total 190 CZK. No payment was taken.');
