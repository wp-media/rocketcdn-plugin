<?php


namespace RocketCDN\Tests\Unit\src\Options\Options;

use RocketCDN\Options\Options;
use RocketCDN\Tests\Unit\TestCase;
use Brain\Monkey\Functions;

/**
 * @covers \RocketCDN\Options\Options::get
 *
 * @group Options
 */
class Test_Get extends TestCase
{
    /**
     * @dataProvider configTestData
     */
    public function testShouldReturnExpected($config, $expected)
    {
        $option = new Options($config['prefix']);
        Functions\expect( 'get_option' )
            ->with($config['option_name'], $config['default'])
            ->andReturn( $config['option'] );
        $this->assertEquals($expected, $option->get($config['name'], $config['default']));
    }
}
