<?php

namespace RocketCDN\Tests\Unit\src\Options\Options;

use RocketCDN\Options\Options;
use RocketCDN\Tests\Unit\TestCase;
use Brain\Monkey\Functions;

class Test_Delete extends TestCase
{
    /**
     * @dataProvider configTestData
     */
    public function testShouldReturnExpected($config, $expected) {
        $option = new Options($config['prefix']);
        Functions\expect( 'delete_option' )
            ->with($expected);
        $option->delete($config['name']);
    }
}
