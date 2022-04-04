<?php

namespace RocketCDN\Tests\Unit\src\Admin\AdminBar\AdminBar;

use Mockery;
use RocketCDN\Admin\AdminBar\AdminBar;
use RocketCDN\API\Client;
use RocketCDN\Options\Options;
use RocketCDN\Tests\Unit\TestCase;
use Brain\Monkey\Functions;

/**
 * @covers \RocketCDN\Admin\AdminBar\AdminBar::purge_cache
 *
 * @group Admin
 */
class Test_PurgeCache  extends TestCase {

	protected $options;
	protected $client;
	protected $admin_bar_menu;

	protected function setUp(): void {
		$this->options        = Mockery::mock( Options::class );
		$this->client         = Mockery::mock( Client::class );
		$this->admin_bar_menu = Mockery::mock( AdminBar::class . '[exit]', [ $this->options, $this->client, '/' ] )
			->shouldAllowMockingProtectedMethods();
		parent::setUp();
	}
	/**
	 * @dataProvider configTestData
	 */
	public function testShouldReturnExpected( $config, $expected ) {
		$_GET['_wpnonce'] = $config['nonce'];

		Functions\expect( 'wp_verify_nonce' )
			->with()
			->andReturn( $config['is_nonce_valid'] );

		Functions\expect( 'sanitize_key' )->zeroOrMoreTimes()
			->andReturnFirstArg();

		Functions\expect( 'esc_url_raw' )->zeroOrMoreTimes()
			->andReturnFirstArg();

		if ( ! $config['nonce'] || ! $config['is_nonce_valid'] ) {
			Functions\expect( 'wp_nonce_ays' )
				->with( '' );
		} else {
			Functions\expect( 'wp_nonce_ays' )->never();
		}

		if ( $config['nonce'] && $config['is_nonce_valid'] ) {
			Functions\expect( 'current_user_can' )
				->with( 'manage_options' )->andReturn( $config['has_right'] );
		} else {
			Functions\expect( 'current_user_can' )->never();
		}

		if ( $config['has_right'] ) {
			$this->client->expects()->purge_cache();
			Functions\expect( 'wp_get_referer' )->andReturn( $config['referer'] );
			Functions\expect( 'wp_safe_redirect' )->with( $config['referer'] );
			$this->admin_bar_menu->expects()->exit();
		}

		$this->admin_bar_menu->purge_cache();
	}
}
