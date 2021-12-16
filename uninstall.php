<?php

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

delete_transient( 'rocketcdn_customer_data' );
delete_option( 'rocketcdn_api_key' );
delete_option( 'rocketcdn_cdn_url' );
