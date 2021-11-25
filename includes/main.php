<?php
defined( 'ABSPATH' ) || exit;

// Composer autoload.
if ( file_exists( realpath( plugin_dir_path( __DIR__ ) ) . '/vendor/autoload.php' ) ) {
	require realpath( plugin_dir_path( __DIR__ ) ) . '/vendor/autoload.php';
}

add_action( 'plugins_loaded', [ new RocketCDN\Plugin(), 'load' ] );
