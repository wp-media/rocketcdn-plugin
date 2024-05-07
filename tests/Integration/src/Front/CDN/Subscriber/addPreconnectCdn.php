<?php
namespace RocketCDN\Tests\Integration\src\Front\CDN\Subscriber;

use RocketCDN\Tests\Integration\TestCase;

/**
 * @covers \RocketCDN\Front\Subscriber::add_preconnect_cdn
 * @uses \RocketCDN\Front\CDN::add_preconnect_cdn
 *
 * @group Admin
 */
class Test_AddPreconnectCdn extends TestCase {

	protected $cdn_url;

	public function set_up() {
		$this->unregisterAllCallbacksExcept( 'wp_resource_hints', 'add_preconnect_cdn', 10 );

		parent::set_up();
	}

	public function tear_down() {
		$this->restoreWpFilter( 'wp_resource_hints' );

		parent::tear_down();
	}

	/**
	 * @dataProvider providerTestData
	 */
	public function testShouldAddPreconnectCdn( $config, $expected ) {
		$this->cdn_url = $config['cdn_url'];

		add_filter( 'pre_option_rocketcdn_cdn_url', [ $this, 'setCdnUrl' ] );

		ob_start();
		wp_resource_hints();

		$this->assertSame(
			$this->format_the_html( $expected['html'] ),
			$this->format_the_html( ob_get_clean() )
		);
	}

	public function providerTestData() {
		return $this->getTestData( __DIR__, 'addPreconnectCdn' );
	}

	public function setCdnUrl() {
		return $this->cdn_url;
	}
}
