<?php

namespace RocketCDN\Tests\Unit\src\Options\Options;

use RocketCDN\Options\Options;
use RocketCDN\Tests\Unit\TestCase;

/**
 * @covers \RocketCDN\Options\Options::get_option_name
 *
 * @group API
 */
class Test_GetOptionName extends TestCase
{
    /**
     * @dataProvider configTestData
     */
    public function testShouldReturnExpected($config, $expected) {
        $option = new Options($config['prefix']);
        $this->assertEquals($expected, $option->get_option_name($config['name']));
    }
}
