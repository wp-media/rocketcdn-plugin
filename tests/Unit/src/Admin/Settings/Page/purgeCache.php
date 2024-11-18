<?php
declare(strict_types=1);

namespace RocketCDN\Tests\Unit\src\Admin\Settings\Page;

use Brain\Monkey\Functions;
use Mockery;
use RocketCDN\Admin\Settings\Page;
use RocketCDN\API\Client;
use RocketCDN\Tests\Unit\TestCase;

/**
 * @covers \RocketCDN\Admin\Settings\Page::purge_cache
 *
 * @group Settings
 */
class Test_PurgeCache extends TestCase {
	protected $client;
	protected $page;

	protected function setUp(): void {
		parent::setUp();

		$this->client  = Mockery::mock( Client::class );
		$this->page    = new Page( $this->client,  WP_ROCKET_CDN_PLUGIN_ROOT . '/views/', '/' );

		$this->stubTranslationFunctions();
	}

	/**
	 * @dataProvider configTestData
	 */
	public function testShouldReturnAsExcepted( $config, $expected ) {
		Functions\expect( 'check_ajax_referer' );
		Functions\when( 'current_user_can' )->justReturn( $config['has_right'] );

		$this->client->expects()->purge_cache()
			->atMost()
			->once()
			->andReturn( $config['response'] );

		Functions\when( 'wp_send_json_success' )->alias( function() use ( $expected ) {
			throw new \Exception( $expected );
		} );

		Functions\when( 'wp_send_json_error' )->alias( function() use ( $expected ) {
			throw new \Exception( $expected );
		} );

		$this->expectException( \Exception::class );
		$this->expectExceptionMessage( $expected );

		$this->page->purge_cache();
	}
}
