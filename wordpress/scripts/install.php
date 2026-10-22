<?php
if (!is_blog_installed()) {
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $password = getenv('WP_ADMIN_PASSWORD');
    if (!$password) { WP_CLI::error('Missing local administrator password.'); }
    wp_install('BellaAquaBella', 'shopowner', 'owner@example.test', false, '', $password, 'en_US');
    update_option('home', 'http://127.0.0.1:8080');
    update_option('siteurl', 'http://127.0.0.1:8080');
    update_option('blogdescription', 'Vše pro akvabely');
    update_option('blog_public', 0);
    WP_CLI::success('Created local development WordPress.');
} else {
    WP_CLI::success('Existing WordPress preserved.');
}
