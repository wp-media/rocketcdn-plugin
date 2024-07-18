<?php

namespace RocketCDN\Tests\Unit\src\API\Client;

use Mockery;
use RocketCDN\API\Client;
use RocketCDN\Options\Options;
use RocketCDN\Tests\Unit\TestCase;
use Brain\Monkey\Functions;

/**
 * @covers \RocketCDN\API\Client::get_customer_data
 * @covers \RocketCDN\API\Client::get_raw_customer_data
 * @covers \RocketCDN\API\Client::get_base_api_url
 *
 * @group API
 */
class Test_GetCustomerData extends TestCase {

	protected $client;

	protected function setUp(): void {
		parent::setUp();
		$options_api  = Mockery::mock( Options::class );
		$this->client = new Client( $options_api );
	}

	/**
	 * @dataProvider configTestData
	 */
	public function testShouldReturnAsExcepted( $config, $expected ) {
		Functions\expect( 'get_transient' )
			->once()
			->with( 'rocketcdn_customer_data' )
			->andReturn( $config['cache'] );

		if ( ! $config['cache'] && $config['api'] ) {
			Functions\expect( 'wp_remote_get' )
				->once()->with( $config['url'], $config['headers'] )->andReturn( $config['response'] );

			Functions\expect( 'wp_remote_retrieve_response_code' )
				->once()->with( $config['response'] )->andReturn( $config['code'] );

			Functions\expect( 'apply_filters' )
				->once()->with( 'rocketcdn_base_api_url', Client::ROCKETCDN_API )->andReturn( $config['baseurl'] );
		} else {
			Functions\expect( 'wp_remote_get' )
				->never();

			Functions\expect( 'wp_remote_retrieve_response_code' )
				->never();

			Functions\expect( 'apply_filters' )
				->never();
		}

		if ( $config['requestsucceed'] ) {
			Functions\expect( 'wp_remote_retrieve_body' )
				->once()->with( $config['response'] )->andReturn( $config['body'] );
		} else {
			Functions\expect( 'wp_remote_retrieve_body' )
				->never();
		}

		if ( $config['requestsucceed'] && count( $expected ) > 0 ) {
			Functions\expect( 'set_transient' )
				->once()->with( 'rocketcdn_customer_data', $expected, 2 * DAY_IN_SECONDS );
		} else {
			Functions\expect( 'set_transient' )
				->never();
		}

		$this->assertEquals( $expected, $this->client->get_customer_data( $config['api'] ) );
	}


}
