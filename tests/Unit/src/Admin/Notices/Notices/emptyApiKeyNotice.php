<?php

namespace RocketCDN\Tests\Unit\src\Admin\Notices\Notices;

use Mockery;
use RocketCDN\Admin\Notices\Notices;
use RocketCDN\Tests\Unit\TestCase;
use Brain\Monkey\Functions;

/**
 * @covers \RocketCDN\Admin\Notices\Notices::empty_api_key_notice
 *
 * @group Admin
 */
class Test_EmptyApiKeyNotice extends TestCase {

	protected $options;
	protected $client;
	protected $notices;

	protected function setUp(): void {
		$this->options = Mockery::mock( \RocketCDN\Options\Options::class );
		$this->client  = Mockery::mock( \RocketCDN\API\Client::class );
		$this->notices = new Notices( $this->options, $this->client );
		parent::setUp();
	}

	/**
	 * @dataProvider configTestData
	 */
	public function testShouldReturnExpected( $config, $expected ) {
		if ( $config['has_rights'] ) {
			$this->options->expects()->get( 'api_key' )->andReturn( $config['api'] );
		}
		Functions\expect( '__' )->zeroOrMoreTimes()
			->andReturnFirstArg();
		Functions\expect( 'admin_url' )
			->with( 'options-general.php?page=rocketcdn' )
			->andReturn( $config['admin_url'] );
		Functions\expect( 'current_user_can' )
			->with( 'manage_options' )
			->andReturn( $config['has_rights'] );
		ob_start();
		$this->notices->empty_api_key_notice();
		$this->assertContains( ob_get_contents(), $expected );
		ob_end_clean();
	}
}
