<?php

namespace RocketCDN\Tests\Unit\src\Options\Options;

use RocketCDN\Options\Options;
use RocketCDN\Tests\Unit\TestCase;
use Brain\Monkey\Functions;

/**
 * @covers \RocketCDN\Options\Options::set
 *
 * @group Options
 */
class Test_Set extends TestCase {

	/**
	 * @dataProvider configTestData
	 */
	public function testShouldReturnExpected( $config, $expected ) {
		$option = new Options( $config['prefix'] );
		Functions\expect( 'update_option' )
			->with( $expected['option_name'], $expected['value'] );
		$option->set( $config['name'], $config['value'] );
	}
}
