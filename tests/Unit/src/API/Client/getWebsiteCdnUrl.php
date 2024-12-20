<?php

namespace RocketCDN\Tests\Unit\src\API\Client;

use Mockery;
use RocketCDN\API\Client;
use RocketCDN\Tests\Unit\TestCase;
use Brain\Monkey\Functions;
/**
 * @covers \RocketCDN\API\Client::get_website_cdn_url
 * @covers \RocketCDN\API\Client::extract_hostname
 *
 * @group API
 */
class Test_GetWebsiteCdnUrl extends TestCase {

	protected $client;

	protected function setUp(): void {
		parent::setUp();
		$this->client = Mockery::mock( Client::class . '[get_customer_data]' );
	}

	/**
	 * @dataProvider configTestData
	 */
	public function testShouldReturnAsExpected( $config, $expected ) {
		Functions\expect( 'home_url' )
			->with()
			->andReturn( $config['homeurl'] );
		Functions\expect( 'wp_parse_url' )
			->with( $config['homeurl'], PHP_URL_HOST )
			->andReturn( $config['hostname'] );
		$this->client->expects()->get_customer_data()->andReturn( $config['customerdata'] );
		$this->assertEquals( $expected, $this->client->get_website_cdn_url() );
	}
}
