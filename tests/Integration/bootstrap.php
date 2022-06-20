<?php

define( 'WP_ROCKET_CDN_PLUGIN_ROOT', dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR );
define( 'WP_ROCKET_CDN_TESTS_FIXTURES_DIR', dirname( __DIR__ ) . '/Fixtures' );
define( 'WP_ROCKET_CDN_TESTS_DIR', __DIR__ );
define( 'WP_ROCKET_CDN_IS_TESTING', true );

tests_add_filter(
	'muplugins_loaded',
	function() {
		// Load the plugin.
		require WP_ROCKET_CDN_PLUGIN_ROOT . '/rocketcdn.php';
	}
	);
