<?php

namespace RocketCDN\Tests\Integration\src\Admin\Notices\Subscriber;

use RocketCDN\API\Client;
use RocketCDN\Tests\Integration\AdminTestCase;
use Brain\Monkey\Functions;
use WP_Error;

/**
 * @covers \RocketCDN\Admin\Notices\Subscriber::wrong_api_key_notice
 * @uses \RocketCDN\Admin\Notices\Notices::wrong_api_key_notice
 *
 * @group Admin
 */
class Test_WrongApiKeyNotice extends AdminTestCase {

	protected static $provider_class = 'Admin';

	protected $user_id = 0;
	private $option;

	public function setUp() : void {
		parent::setUp();
		add_filter( 'pre_option_rocketcdn_api_key', [ $this, 'api_key' ] );
	}

	public function tear_down() {
		parent::tear_down();
		remove_filter( 'pre_option_rocketcdn_api_key', [ $this, 'api_key' ] );
	}
	/**
	 * @dataProvider providerTestData
	 */
	public function testShouldMaybeDisplayNotice( $config, $expected ) {

		$this->option = $config['option'];

		$this->setCurrentUser( 'administrator' );

        $this->expectProcessGenerate($config, $expected);

		ob_start();

		do_action( 'admin_notices' );

		if ( $expected['contains'] ) {
			$this->assertContains( $expected['html'], ob_get_contents() );
		} else {
			$this->assertNotContains( $expected['html'], ob_get_contents() );
		}
		ob_end_clean();
	}

    private function expectProcessGenerate( $config, $expected ) {
        if ( ! isset( $config['process_generate'] ) ) {
            return;
        }

        $headers = [
            'Authorization' => "token {$config['option']['api_key']}",
        ];

        if ( ! empty( $config['process_generate']['is_wp_error'] ) ) {
            Functions\expect( 'wp_remote_get' )
                ->once()
                ->with( Client::ROCKETCDN_API . 'customer/me', [ 'headers' => $headers ] )
                ->andReturn( new WP_Error( 'error', 'error_data' ) );
        } else {
            $response = $config['process_generate']['response'];
            Functions\expect( 'wp_remote_get' )
                ->once()
                ->with( Client::ROCKETCDN_API . 'customer/me', [ 'headers' => $headers ] )
                ->andReturn( $response );
        }
    }

	public function api_key() {
		return $this->option['api_key'];
	}

	public function providerTestData() {
		$dir = dirname( __FILE__ );
		return $this->getTestData( $dir, 'wrongApiKeyNotice' );
	}
}
