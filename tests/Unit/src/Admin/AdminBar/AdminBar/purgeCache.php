<?php
declare(strict_types=1);

namespace RocketCDN\Tests\Unit\src\Admin\AdminBar\AdminBar;

use Brain\Monkey\Functions;
use Mockery;
use RocketCDN\Admin\AdminBar\AdminBar;
use RocketCDN\API\Client;
use RocketCDN\Tests\Unit\TestCase;

/**
 * @covers \RocketCDN\Admin\AdminBar\AdminBar::purge_cache
 *
 * @group AdminBar
 */
class Test_PurgeCache extends TestCase {
	protected $client;
	protected $admin_bar;

	protected function setUp(): void {
		parent::setUp();

		$this->client    = Mockery::mock( Client::class );
		$this->admin_bar = new AdminBar( $this->client, 'http://example.org/wp-content/plugins/rocketcdn' );

		$this->stubEscapeFunctions();
	}

	protected function tearDown(): void {
		unset( $_GET['_wpnonce'] );

		parent::tearDown();
	}

	/**
	 * @dataProvider configTestData
	 */
	public function testShouldReturnExpected( $config, $expected ) {
		$_GET['_wpnonce'] = $config['nonce'];

		Functions\expect( 'wp_verify_nonce' )
			->with()
			->andReturn( $config['is_nonce_valid'] );

		Functions\expect( 'sanitize_key' )
			->zeroOrMoreTimes()
			->andReturnFirstArg();

		Functions\when( 'wp_nonce_ays' )
			->alias( function() {
				throw new \Exception( 'wp_nonce_ays' );
			} );


		Functions\when( 'current_user_can' )->justReturn( $config['has_right'] );

		Functions\when( 'wp_die' )
			->alias( function() {
				throw new \Exception( 'wp_die' );
			} );

		$this->client->expects()->purge_cache()
			->atMost()
			->once();
		Functions\when( 'wp_get_referer' )->justReturn( $config['referer'] );
		Functions\when( 'wp_safe_redirect' )
			->alias( function() {
				throw new \Exception( 'wp_safe_redirect' );
			} );

		$this->expectException( \Exception::class );
		$this->expectExceptionMessage( $expected );

		$this->admin_bar->purge_cache();
	}
}
