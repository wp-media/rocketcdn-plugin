<?php
namespace RocketCDN\Tests\Unit\src\Admin\Settings\Page;

use Mockery;
use RocketCDN\Admin\Settings\Page;
use RocketCDN\API\Client;
use RocketCDN\Options\Options;
use RocketCDN\Tests\Unit\TestCase;
use Brain\Monkey\Functions;

/**
 * @covers \RocketCDN\Admin\Settings\Page::purge_cache
 *
 * @group Settings
 */
class Test_PurgeCache extends TestCase {
	protected $options;
	protected $client;
	protected $page;

	protected function setUp(): void {
		parent::setUp();
		$this->options = Mockery::mock( Options::class );
		$this->client  = Mockery::mock( Client::class );
		$this->page    = new Page( $this->options, $this->client,  WP_ROCKET_CDN_PLUGIN_ROOT . '/views/', '/' );
	}

	/**
	 * @dataProvider configTestData
	 */
	public function testShouldReturnAsExcepted( $config, $expected ) {
		Functions\expect( '__' )->zeroOrMoreTimes()->andReturnFirstArg();
		Functions\expect( 'current_user_can' )->with( 'manage_options' )->andReturn( $config['has_right'] );
		Functions\expect( 'check_ajax_referer' );
		if ( $config['has_right'] ) {
			$this->client->expects()->purge_cache()->andReturn( $config['response'] );
		}
		if ( $expected['success'] ) {
			Functions\expect( 'wp_send_json_success' )->with( $expected['message'] );
		} else {
			Functions\expect( 'wp_send_json_error' )->with( $expected['message'] );
		}
		$this->page->purge_cache();
	}
}
