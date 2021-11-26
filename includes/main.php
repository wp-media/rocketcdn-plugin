<?php
defined( 'ABSPATH' ) || exit;

use RocketCDN\Dependencies\League\Container\Container;

// Composer autoload.
if ( file_exists( realpath( plugin_dir_path( __DIR__ ) ) . '/vendor/autoload.php' ) ) {
	require realpath( plugin_dir_path( __DIR__ ) ) . '/vendor/autoload.php';
}

$rocketcdn_plugin = new RocketCDN\Plugin(
	new Container()
);

add_action( 'plugins_loaded', [ $rocketcdn_plugin, 'load' ] );
