<?php

namespace RocketCDN\Tests\Unit\src\API\Client;

use Mockery;
use RocketCDN\API\Client;
use RocketCDN\Options\Options;
use RocketCDN\Tests\Unit\TestCase;
use Brain\Monkey\Functions;
/**
 * @covers \RocketCDN\API\Client::is_website_sync
 *
 * @group API
 */
class Test_IsWebsiteSync extends TestCase
{
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();
        $options_api = Mockery::mock( Options::class);
        $this->client = Mockery::mock(Client::class .  '[get_customer_data]', [$options_api]);
    }

    /**
     * @dataProvider configTestData
     */
    public function testShouldReturnAsExcepted($config, $expected) {
        Functions\expect( 'home_url' )
                ->with()
                ->andReturn( $config['homeurl'] );
        Functions\expect( 'wp_parse_url' )
            ->with($config['homeurl'], PHP_URL_HOST)
            ->andReturn( $config['hostname'] );
        $this->client->expects()->get_customer_data($config['api'])->andReturn($config['customerdata']);
        $this->assertEquals($expected, $this->client->is_website_sync($config['api']));
    }
}
