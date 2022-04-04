<?php
namespace RocketCDN\Tests\Unit\src\Admin\Settings\Page;

use Mockery;
use RocketCDN\Admin\Settings\Page;
use RocketCDN\API\Client;
use RocketCDN\Options\Options;
use WPMedia\PHPUnit\Unit\TestCase;
use Brain\Monkey\Functions;

/**
 * @covers \RocketCDN\Admin\Settings\Page::configure_settings
 *
 * @group Settings
 */
class Test_ConfigureSettings extends TestCase {

    protected $client;
    protected $page;

    protected function setUp(): void
    {
        parent::setUp();
        $options_api = Mockery::mock( Options::class);
        $this->client = Mockery::mock( Client::class);
        $this->page = new Page($options_api, $this->client, '/', '/');
    }

    public function testShouldRegister() {
        Functions\expect( 'register_setting' )
            ->with('rocketcdn',
                'rocketcdn_api_key',
                [
                    'sanitize_callback' => 'sanitize_key',
                ]);
        $this->page->configure_settings();
    }
}
