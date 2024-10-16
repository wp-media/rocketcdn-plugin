<?php

namespace RocketCDN\Tests\Unit\src\Front\CDN;

use Mockery;
use RocketCDN\Dependencies\LaunchpadOptions\Options;
use RocketCDN\Front\CDN;
use RocketCDN\Tests\Unit\TestCase;
use Brain\Monkey\Functions;

/**
 * @covers \RocketCDN\Front\CDN::rewrite_url
 * @covers \RocketCDN\Front\CDN::get_home_host
 *
 * @group Front
 */
class Test_RewriteUrl extends TestCase {

	protected $options;
	protected $cdn;

	protected function setUp(): void {
		parent::setUp();
		$this->options = Mockery::mock( Options::class );
		$this->cdn     = new CDN();
        $this->cdn->set_options($this->options);
	}

	/**
	 * @dataProvider configTestData
	 */
	public function testShouldReturnExpected( $config, $expected ) {

		if( ! key_exists('is_admin', $config) || ! $config['is_admin']) {
			$this->options->expects()->get( 'cdn_url' )->andReturn( $config['cdn'] );
		}

		Functions\expect( 'home_url' )
			->with()
			->andReturn( $config['homeurl'] );

		Functions\expect( 'admin_url' )
			->with()
			->andReturn( $config['admin_url'] );

		Functions\expect( 'wp_parse_url' )
			->with( $config['url'] )->andReturnUsing(
				function( $arg1, $arg2 = null ) use ( $config ) {
					if ( $arg2 ) {
						return $config['host'];
					}
					return $config['parsed_url'];
				}
				);

		$this->assertEquals( $expected, $this->cdn->rewrite_url( $config['url'] ) );
	}
}
