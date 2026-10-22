<?php
/** Local development only: prevent messages reaching real recipients. */
if (defined('WP_ENVIRONMENT_TYPE') && WP_ENVIRONMENT_TYPE === 'local') {
    add_filter('pre_wp_mail', '__return_true');
}
