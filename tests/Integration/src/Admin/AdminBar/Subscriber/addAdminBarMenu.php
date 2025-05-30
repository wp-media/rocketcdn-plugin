<?php

namespace RocketCDN\Tests\Integration\src\Admin\AdminBar\Subscriber;

use RocketCDN\API\Client;
use RocketCDN\Tests\Integration\AdminTestCase;
use Brain\Monkey\Functions;
use WP_Error;

/**
 * @covers \RocketCDN\Admin\AdminBar\Subscriber::add_admin_bar_menu
 * @uses \RocketCDN\Admin\AdminBar\AdminBar::add_admin_bar_menu
 *
 * @group Admin
 */
class Test_AddAdminBarMenu extends AdminTestCase {

	protected static $provider_class = 'Admin';

	protected $user_id = 0;
	private $option;

	public function set_up() : void {
		parent::set_up();

		add_filter( 'show_admin_bar', [ $this, 'return_true' ] );
		add_filter( 'pre_option_rocketcdn_api_key', [ $this, 'api_key' ] );
	}

	public function tear_down() {
		parent::tear_down();

		remove_filter( 'show_admin_bar', [ $this, 'return_true' ] );
		remove_filter( 'pre_option_rocketcdn_api_key', [ $this, 'api_key' ] );

		unset( $_SERVER['REQUEST_URI'] );
	}

	/**
	 * @dataProvider providerTestData
	 */
	public function testShouldDoExpected( $config, $expected ) {
		if ( $config['cap'] ) {
			$this->setCurrentUser( 'administrator' );
		} else {
			$this->setCurrentUser( 'editor' );
		}

		$this->option           = $config['option'];
		$_SERVER['REQUEST_URI'] = $config['request'];
		$wp_admin_bar           = $this->initAdminBar();

		if ( false === $config['admin'] ) {
			set_current_screen( 'front' );
		}

		Functions\when( 'wp_create_nonce' )->justReturn( 'wp_rocket_nonce' );

        $this->expectProcessGenerate($config, $expected);

		// Fire the hook.
		do_action_ref_array( 'admin_bar_menu', [ $wp_admin_bar ] );

		// Check the results.
		$base     = $wp_admin_bar->get_node( 'rocketcdn' );
		$connect  = $wp_admin_bar->get_node( 'rocketcdn-connect' );
		$settings = $wp_admin_bar->get_node( 'rocketcdn-settings' );
		$cache    = $wp_admin_bar->get_node( 'rocketcdn-purge-cache' );
		$faq      = $wp_admin_bar->get_node( 'rocketcdn-faq' );
		$support  = $wp_admin_bar->get_node( 'rocketcdn-support' );
		if ( count( $expected ) > 0 ) {
			$this->assertEquals( $base, $expected['base'] );
			$this->assertEquals( $expected['connect'], $connect );
			$this->assertEquals( $settings, $expected['settings'] );
			$this->assertEquals( $cache, $expected['cache'] );
			$this->assertEquals( $faq, $expected['faq'] );
			$this->assertEquals( $support, $expected['support'] );
		} else {
			$this->assertNull( $base );
			$this->assertNull( $connect );
			$this->assertNull( $settings );
			$this->assertNull( $cache );
			$this->assertNull( $faq );
			$this->assertNull( $support );
		}
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

	protected function initAdminBar() {
		global $wp_admin_bar;

		set_current_screen( 'edit.php' );
		$this->assertTrue( _wp_admin_bar_init() );

		$this->assertTrue( is_admin_bar_showing() );
		$this->assertInstanceOf( 'WP_Admin_Bar', $wp_admin_bar );

		return $wp_admin_bar;
	}


	public function api_key() {
		return $this->option['api_key'];
	}

	public function providerTestData() {
		$dir = dirname( __FILE__ );
		return $this->getTestData( $dir, 'addAdminBarMenu' );
	}
}
