<?php

namespace RocketCDN\Tests\Unit\src\Front\CDN;

use Mockery;
use RocketCDN\Front\CDN;
use RocketCDN\Tests\Unit\TestCase;
use Brain\Monkey\Functions;

/**
 * @covers \RocketCDN\Front\CDN::start_buffering
 * @covers \RocketCDN\Front\CDN::should_rewrite
 *
 * @group Front
 */
class Test_StartBuffering extends TestCase {

	protected $cdn;

	protected function setUp(): void {
		parent::setUp();
		$this->cdn = new CDN();
	}
	/**
	 * @dataProvider configTestData
	 */
	public function testShouldReturnExpected( $config, $expected ) {
		if ( $config['has_request_method'] ) {
			$_SERVER['REQUEST_METHOD'] = $config['request_method'];
		} else {
			unset( $_SERVER['REQUEST_METHOD'] );
		}
		if ( $expected ) {
			Functions\expect( 'ob_start' )->once();
		} else {
			Functions\expect( 'ob_start' )->never();
		}
		Functions\expect( 'is_admin' )->zeroOrMoreTimes()->andReturn( $config['is_admin'] );
		Functions\expect( 'is_customize_preview' )->zeroOrMoreTimes()->andReturn( $config['is_customize_preview'] );
		Functions\expect( 'is_embed' )->zeroOrMoreTimes()->andReturn( $config['is_embed'] );
		$this->cdn->start_buffering();
	}
}
