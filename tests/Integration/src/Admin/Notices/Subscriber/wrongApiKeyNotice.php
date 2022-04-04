<?php

namespace RocketCDN\Tests\Integration\src\Admin\Notices\Subscriber;

use RocketCDN\Tests\Integration\AdminTestCase;
/**
 * @covers \RocketCDN\Admin\Notices\Notices::wrong_api_key_notice
 *
 * @group Admin
 */
class Test_WrongApiKeyNotice extends AdminTestCase
{
    protected static $provider_class = 'Admin';

    protected $user_id = 0;
    private   $option;

    public function setUp() : void {
        parent::setUp();
        add_filter( 'pre_option_rocketcdn_api_key', [ $this, 'api_key' ] );
    }

    public function tearDown() {
        parent::tearDown();
        remove_filter( 'pre_option_rocketcdn_api_key', [ $this, 'api_key' ] );
    }
    /**
     * @dataProvider providerTestData
     */
    public function testShouldMaybeDisplayNotice( $config, $expected ) {

        $this->option = $config['option'];

        $this->setCurrentUser( 'administrator' );

        ob_start();

        do_action( 'admin_notices' );

        if($expected['contains']) {
            $this->assertContains($expected['html'], ob_get_contents());
        } else {
            $this->assertNotContains($expected['html'], ob_get_contents());
        }
        ob_end_clean();
    }

    public function api_key() {
        return $this->option['api_key'];
    }

    public function providerTestData() {
        $dir = dirname( __FILE__ );
        return $this->getTestData( $dir, 'wrongApiKeyNotice' );
    }
}
