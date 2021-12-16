<?php
/**
 * Plugin Name: RocketCDN
 * Plugin URI: https://rocketcdn.me
 * Description: RocketCDN automatically rewrites all URLs to be served by our content delivery network (CDN). Easy CDN deployment to WordPress.
 * Version: 1.0
 * Requires at least: 5.4
 * Requires PHP: 7.0
 * Author: WP Media
 * Author URI: https://wp-media.me
 * Licence: GPLv3 or later
 *
 * Copyright 2021 RocketCDN
 */

defined( 'ABSPATH' ) || exit;

define( 'ROCKETCDN_VERSION', '1.0' );

require realpath( plugin_dir_path( __FILE__ ) ) . '/includes/RocketCDNRequirementsCheck.php';

$rocketcdn_rq_check = new RocketCDNRequirementsCheck(
	[
		'plugin_name'    => 'RocketCDN',
		'plugin_version' => ROCKETCDN_VERSION,
		'wp_version'     => '5.4',
		'php_version'    => '7.0',
	]
);

if ( $rocketcdn_rq_check->check() ) {
	require realpath( plugin_dir_path( __FILE__ ) ) . '/includes/main.php';
}

unset( $rocketcdn_rq_check );
