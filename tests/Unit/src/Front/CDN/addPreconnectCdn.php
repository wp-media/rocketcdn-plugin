<?php

namespace RocketCDN\Tests\Unit\src\Front\CDN;

use Mockery;
use RocketCDN\Dependencies\LaunchpadOptions\Options;
use RocketCDN\Front\CDN;
use RocketCDN\Tests\Unit\TestCase;

/**
 * @covers \RocketCDN\Front\CDN::add_preconnect_cdn
 *
 * @group Front
 */
class Test_AddPreconnectCdn extends TestCase {

	protected $options;
	protected $cdn;

	protected function setUp(): void {
		parent::setUp();
		$this->options = Mockery::mock( Options::class );
		$this->cdn     = Mockery::mock( CDN::class . '[should_rewrite]' )->shouldAllowMockingProtectedMethods();
        $this->cdn->set_options($this->options);
	}
	/**
	 * @dataProvider configTestData
	 */
	public function testShouldReturnExpected( $config, $expected ) {
		if ( $config['relation_type'] == 'preconnect' && $config['rewrite'] ) {
			$this->options->expects()->get( 'cdn_url' )->andReturn( $config['cdn'] );
		}
		if ( $config['relation_type'] == 'preconnect' ) {
			$this->cdn->expects()->should_rewrite()->andReturn( $config['rewrite'] );
		}
		$this->assertEquals( $expected, $this->cdn->add_preconnect_cdn( $config['urls'], $config['relation_type'] ) );
	}
}
