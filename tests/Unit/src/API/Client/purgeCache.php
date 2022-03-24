<?php

namespace RocketCDN\Tests\Unit\src\API\Client;

use Mockery;
use RocketCDN\API\Client;
use RocketCDN\Options\Options;
use Brain\Monkey\Functions;
/**
 * @covers \RocketCDN\API\Client::purge_cache
 *
 * @group API
 */
class Test_PurgeCache extends \RocketCDN\Tests\Unit\TestCase
{
    /**
     * @dataProvider configTestData
     */
    public function testShouldReturnAsExcepted($config, $expected) {
        $options_api = Mockery::mock( Options::class);
        $options_api->expects()->get('api_key')->andReturn($config['api']);
        if($config['api']) {
            $options_api->expects()->get('cdn_url')->andReturn($config['cdn']);
        } else {
            $options_api->expects()->get('cdn_url')->never();
        }
        $client = new Client($options_api);
        Functions\expect( '__' )->zeroOrMoreTimes()
            ->andReturnFirstArg();
        if($config['api'] && $config['cdn']) {
            Functions\expect( 'wp_remote_request' )
                ->once()->with($config['url'], $config['headers'])->andReturn($config['response']);

            Functions\expect( 'wp_remote_retrieve_response_code' )
                ->once()->with($config['response'])->andReturn($config['code']);

            Functions\expect( 'apply_filters' )
                ->once()->with('rocketcdn_base_api_url', Client::ROCKETCDN_API)->andReturn($config['baseurl']);
        } else {
            Functions\expect( 'wp_remote_get' )
                ->never();

            Functions\expect( 'wp_remote_retrieve_response_code' )
                ->never();

            Functions\expect( 'apply_filters' )
                ->never();
        }

        if($config['requestsucceed']) {
            Functions\expect( 'wp_remote_retrieve_body' )
                ->once()->with($config['response'])->andReturn($config['body']);
        } else {
            Functions\expect( 'wp_remote_retrieve_body' )
                ->never();
        }

        $this->assertEquals($expected, $client->purge_cache());
    }
}
