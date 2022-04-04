<?php

use RocketCDN\Tests\Integration\AdminTestCase;

class Test_EnqueueAssets extends AdminTestCase {

    public function setUp() : void {
        parent::setUp();

        $this->setRoleCap( 'administrator', 'rocket_manage_options' );
    }

    public function tearDown() {
        parent::tearDown();

        set_current_screen( 'front' );

        $this->removeRoleCap( 'administrator', 'rocket_manage_options' );
    }


    /**
     * @dataProvider providerTestData
     */
    public function testShouldMaybeEnqueueAssets($config, $expected) {
        $this->setCurrentUser( 'administrator' );

        set_current_screen( $config['hook'] );

        do_action( 'admin_enqueue_scripts', $config['hook'] );
        $wp_styles = wp_styles();
        $wp_scripts = wp_scripts();
        $wp_scripts->init();
        if($expected) {
            $this->assertArrayHasKey('rocketcdn-settings', $wp_styles->registered);
            $this->assertArrayHasKey('rocketcdn_ajax', $wp_scripts->registered);
            $this->assertNotEmpty($wp_scripts->registered['rocketcdn_ajax']->extra['data']);
        } else {
            $this->assertArrayNotHasKey('rocketcdn-settings', $wp_styles->registered);
            $this->assertArrayNotHasKey('rocketcdn_ajax', $wp_scripts->registered);
        }
    }

    public function providerTestData() {
        $dir = dirname( __FILE__ );
        return $this->getTestData( $dir, 'enqueueAssets' );
    }
}
