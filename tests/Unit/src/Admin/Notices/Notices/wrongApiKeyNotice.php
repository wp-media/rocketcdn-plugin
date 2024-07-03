<?php

namespace RocketCDN\Tests\Unit\src\Admin\Notices\Notices;

use Mockery;
use RocketCDN\Admin\Notices\Notices;
use RocketCDN\Dependencies\LaunchpadOptions\Options;
use RocketCDN\Tests\Unit\TestCase;
use Brain\Monkey\Functions;

/**
 * @covers \RocketCDN\Admin\Notices\Notices::wrong_api_key_notice
 *
 * @group Admin
 */
class Test_WrongApiKeyNotice extends TestCase {

	protected $options;
	protected $client;
	protected $notices;

	protected function setUp(): void {
		$this->options = Mockery::mock( Options::class );
		$this->client  = Mockery::mock( \RocketCDN\API\Client::class );
		$this->notices = new Notices( $this->client );
        $this->notices->set_options($this->options);
		parent::setUp();
	}

	/**
	 * @dataProvider configTestData
	 */
	public function testShouldReturnExpected( $config, $expected ) {
		Functions\expect( 'current_user_can' )
			->with( 'manage_options' )
			->andReturn( $config['has_rights'] );

		Functions\expect( '__' )->zeroOrMoreTimes()
			->andReturnFirstArg();

		if ( $config['has_rights'] ) {
			$this->options->expects()->get( 'api_key', '' )->andReturn( $config['api'] );
		}

		if ( $config['api'] ) {
			$this->client->expects()->get_customer_data( $config['api'] )->andReturn( $config['data'] );
		}

		ob_start();
		$this->notices->wrong_api_key_notice();
		$this->assertContains( ob_get_contents(), $expected );
		ob_end_clean();
	}
}
