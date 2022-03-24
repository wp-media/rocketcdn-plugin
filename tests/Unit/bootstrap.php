<?php

namespace WP_Rocket\Tests\Unit;

define( 'WP_ROCKET_PLUGIN_ROOT', dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR );
define( 'WP_ROCKET_TESTS_FIXTURES_DIR', dirname( __DIR__ ) . '/Fixtures' );
define( 'WP_ROCKET_TESTS_DIR', __DIR__ );
define( 'WP_ROCKET_IS_TESTING', true );

// Set the path and URL to our virtual filesystem.
define( 'WP_ROCKET_CACHE_ROOT_PATH', 'vfs://public/wp-content/cache/' );
define( 'WP_ROCKET_CACHE_ROOT_URL', 'vfs://public/wp-content/cache/' );
