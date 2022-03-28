<?php

namespace RocketCDN\Tests\Unit\src\Admin\AdminBar\AdminBar;

use Mockery;
use RocketCDN\Admin\AdminBar\AdminBar;
use RocketCDN\API\Client;
use RocketCDN\Options\Options;
use RocketCDN\Tests\Unit\TestCase;
use Brain\Monkey\Functions;

/**
 * @covers \RocketCDN\Admin\AdminBar\AdminBar::enqueue_style
 *
 * @group Admin
 */
class Test_EnqueueStyle extends TestCase
{
    /**
     * @dataProvider configTestData
     */
    public function testShouldReturnExpected($config, $expected) {
        $admin_bar = new AdminBar(Mockery::mock(Options::class), Mockery::mock(Client::class), $config['base']);
        Functions\expect( 'wp_enqueue_style' )
            ->with($expected['id'], $expected['path'], $expected['options'], $expected['version']);
        $admin_bar->enqueue_style();
    }
}
