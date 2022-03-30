<?php

use RocketCDN\Tests\Integration\AdminTestCase;
use RocketCDN\Tests\Integration\TestCase;

class Test_EnqueueStyle extends AdminTestCase {
    /**
     * @dataProvider providerTestData
     */
    public function testShouldEnqueueStyle( $config ) {
        set_current_screen( $config['hook'] );

        do_action( 'admin_enqueue_scripts', $config['hook'] );
        $wp_styles = wp_styles();


        $this->assertArrayHasKey('rocketcdn-admin-bar', $wp_styles->registered);
    }


    public function providerTestData() {
        $dir = dirname( __FILE__ );
        return $this->getTestData( $dir, 'enqueueStyle' );
    }
}
