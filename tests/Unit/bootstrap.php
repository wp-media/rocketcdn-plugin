<?php

namespace WP_Rocket\Tests\Unit;

define( 'WP_ROCKET_CDN_PLUGIN_ROOT', dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR );
define( 'WP_ROCKET_CDN_TESTS_FIXTURES_DIR', dirname( __DIR__ ) . '/Fixtures' );
define( 'WP_ROCKET_CDN_TESTS_DIR', __DIR__ );
define( 'WP_ROCKET_CDN_IS_TESTING', true );

// Set the path and URL to our virtual filesystem.
define( 'WP_ROCKET_CDN_CACHE_ROOT_PATH', 'vfs://public/wp-content/cache/' );
define( 'WP_ROCKET_CDN_CACHE_ROOT_URL', 'vfs://public/wp-content/cache/' );
define( 'ROCKETCDN_VERSION', '1.0.3' );
require_once WP_ROCKET_CDN_TESTS_FIXTURES_DIR . '/src/Admin/AdminBar/AdminBar/WPAdminBar.php';
