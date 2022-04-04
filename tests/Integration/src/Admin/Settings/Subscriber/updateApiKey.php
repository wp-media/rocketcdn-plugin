<?php

use RocketCDN\API\Client;
use RocketCDN\Tests\Integration\AjaxTestCase;
use RocketCDN\Tests\Integration\CapTrait;
use Brain\Monkey\Functions;

class Test_UpdateApiKey extends AjaxTestCase {
    protected $api_key;
    private static   $admin_user_id      = 0;

    public static function setUpBeforeClass() : void {
        parent::setUpBeforeClass();
        CapTrait::setAdminCap();
        self::$admin_user_id = static::factory()->user->create( [ 'role' => 'administrator' ] );
    }

    public function setUp() : void {
        parent::setUp();
        add_filter( 'pre_option_rocketcdn_api_key', [ $this, 'api_key' ] );
        $this->action = 'rocketcdn_update_key';
        delete_transient( 'rocketcdn_customer_data' );
    }

    public function testCallbackIsRegistered() {
        $this->assertCallbackRegistered( 'wp_ajax_rocketcdn_validate_key', 'validate_api_key' );
    }

    public function tearDown() {

        parent::tearDown();
        delete_transient( 'rocketcdn_customer_data' );
        remove_filter( 'pre_option_rocketcdn_api_key', [ $this, 'api_key' ] );
    }

    /**
     * @dataProvider providerTestData
     */
    public function testShouldMaybeChangeAPIKey($config, $expected) {
        $this->setUserAndCapabilities($config);

        $_POST['api_key'] = $config['api'];
        $_POST['nonce'] = wp_create_nonce( 'rocketcdn_ajax' );

        if ( isset( $config['rocketcdn_customer_data'] ) ) {
            set_transient( 'rocketcdn_customer_data', $config['rocketcdn_customer_data'], HOUR_IN_SECONDS );
        }

        $this->expectProcessGenerate( $config, $expected );
        $response        = $this->callAjaxAction();

        if ( $expected['bailout'] ) {
            $this->assertFalse( $response->success );
        } else {
            $this->assertTrue( $response->success );
            $this->assertSame( $expected['data']['status'], $response->data->status );
        }
    }

    private function expectProcessGenerate( $config, $expected ) {
        if ( ! isset( $config['process_generate'] )) {
            return;
        }
        $headers = [
            'Authorization'        => "token {$config['api']}",
        ];

        if ( ! empty( $config['process_generate']['is_wp_error'] ) ) {
            Functions\expect( 'wp_remote_get' )
                ->once()
                ->with( Client::ROCKETCDN_API . 'customer/me', [ 'headers' => $headers ] )
                ->andReturn( new WP_Error( 'error', 'error_data' ) );
        } else {
            $message = $config['process_generate']['response'];
            Functions\expect( 'wp_remote_get' )
                ->once()
                ->with( Client::ROCKETCDN_API . 'customer/me', [ 'headers' => $headers ] )
                ->andReturn( [ 'body' => $message ] );
        }
    }

    public function setUserAndCapabilities( $config ) {
        if ( ! empty( $config['can_manage_options'] ) ) {
            $this->setRoleCap( 'administrator', 'manage_options' );
        }

        wp_set_current_user( self::$admin_user_id );
    }

    public function api_key() {
        return $this->api_key;
    }


    public function providerTestData() {
        $dir = dirname( __FILE__ );

        return $this->getTestData( $dir, 'updateApiKey' );
    }
}
