<?php
namespace RocketCDN\Tests\Integration\src\Front\CDN\Subscriber;
/**
 * @covers \RocketCDN\Front\CDN::add_preconnect_cdn
 *
 * @group Admin
 */
class Test_AddPreconnectCdn extends \RocketCDN\Tests\Integration\TestCase {

    protected $cdn_url;

    public function setUp() : void {
        $this->unregisterAllCallbacksExcept( 'wp_resource_hints', 'add_preconnect_cdn', 10 );

        parent::setUp();
    }

    public function tearDown() {
        $this->restoreWpFilter( 'wp_resource_hints' );

        parent::tearDown();
    }

    /**
     * @dataProvider providerTestData
     */
    public function testShouldAddPreconnectCdn($config, $expected) {
        $this->cdn_url = $config['cdn_url'];

        add_filter( 'pre_option_rocketcdn_cdn_url', [ $this, 'setCdnUrl'] );

        ob_start();
        wp_resource_hints();

        $this->assertSame(
            $this->format_the_html($expected['html']),
            $this->format_the_html(ob_get_clean())
        );
    }

    public function providerTestData() {
        return $this->getTestData( __DIR__, 'addPreconnectCdn' );
    }

    public function setCdnUrl() {
        return $this->cdn_url;
    }
}
