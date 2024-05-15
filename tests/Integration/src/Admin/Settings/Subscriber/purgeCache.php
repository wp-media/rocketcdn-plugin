<?php
namespace RocketCDN\Tests\Integration\src\Admin\Settings\Subscriber;

use RocketCDN\API\Client;
use RocketCDN\Tests\Integration\AjaxTestCase;
use Brain\Monkey\Functions;
use RocketCDN\Tests\Integration\CapTrait;
use WP_Error;

/**
 * @covers \RocketCDN\Admin\Settings\Subscriber::purge_cache
 * @uses \RocketCDN\Admin\Settings\Page::purge_cache
 *
 * @group Admin
 */
class Test_PurgeCache extends AjaxTestCase {
	private static $admin_user_id = 0;
	protected $api;
	protected $cdn;
	public static function setUpBeforeClass() : void {
		parent::setUpBeforeClass();
		self::setAdminCap();
		self::$admin_user_id = static::factory()->user->create( [ 'role' => 'administrator' ] );
	}

	public function setUp() : void {
		parent::setUp();
		add_filter( 'pre_option_rocketcdn_api_key', [ $this, 'api_key' ] );
		add_filter( 'pre_option_rocketcdn_cdn_url', [ $this, 'cdn_url' ] );
		$this->action = 'rocketcdn_purge_cache';
	}

	/**
	 * @dataProvider providerTestData
	 */
	public function testShouldMaybeClearCache( $config, $expected ) {
		$this->setUserAndCapabilities( $config );
		$_POST['nonce'] = wp_create_nonce( 'rocketcdn_ajax' );
		$this->api      = $config['api'];
		$this->cdn      = $config['cdn'];

		$this->expectProcessGenerate( $config, $expected );
		$response = $this->callAjaxAction();
		if ( $expected['bailout'] ) {
			$this->assertFalse( $response->success );
		} else {
			$this->assertTrue( $response->success );
			$this->assertSame( $expected['data']['status'], $response->data->status );
		}
	}

	private function expectProcessGenerate( $config, $expected ) {
		if ( ! isset( $config['process_generate'] ) ) {
			return;
		}
		$headers = [
			'Authorization' => "token {$config['api']}",
		];

		if ( ! empty( $config['process_generate']['is_wp_error'] ) ) {
			Functions\expect( 'wp_remote_request' )
				->once()
				->with(
					 Client::ROCKETCDN_API . "website/{$this->cdn}/purge",
					[
						'method'  => 'DELETE',
						'headers' => $headers,
					 ]
					)
				->andReturn( new WP_Error( 'error', 'error_data' ) );
		} else {
			$message = $config['process_generate']['response'];
			Functions\expect( 'wp_remote_request' )
				->once()
				->with(
					 Client::ROCKETCDN_API . "website/{$this->cdn}/purge",
					[
						'method'  => 'DELETE',
						'headers' => $headers,
					 ]
					)
				->andReturn( [ 'body' => $message ] );
		}
	}

	public function setUserAndCapabilities( $config ) {
		if ( ! empty( $config['can_manage_options'] ) ) {
			$this->setRoleCap( 'administrator', 'manage_options' );
		}

		wp_set_current_user( self::$admin_user_id );
	}

	public function providerTestData() {
		$dir = dirname( __FILE__ );

		return $this->getTestData( $dir, 'purgeCache' );
	}

	public function api_key() {
		return $this->api;
	}

	public function cdn_url() {
		return $this->cdn;
	}
}
