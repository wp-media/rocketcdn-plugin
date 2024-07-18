<?php
namespace RocketCDN\Tests\Integration\src\Admin\AdminBar\Subscriber;

use RocketCDN\Tests\Integration\AdminTestCase;
/**
 * @covers \RocketCDN\Admin\AdminBar\Subscriber::enqueue_style
 * @uses \RocketCDN\Admin\AdminBar\AdminBar::enqueue_style
 *
 * @group Admin
 */
class Test_EnqueueStyle extends AdminTestCase {
	/**
	 * @dataProvider providerTestData
	 */
	public function testShouldEnqueueStyle( $config ) {
		set_current_screen( $config['hook'] );

		do_action( 'admin_enqueue_scripts', $config['hook'] );
		$wp_styles = wp_styles();

		$this->assertArrayHasKey( 'rocketcdn-admin-bar', $wp_styles->registered );
	}


	public function providerTestData() {
		$dir = dirname( __FILE__ );
		return $this->getTestData( $dir, 'enqueueStyle' );
	}
}
