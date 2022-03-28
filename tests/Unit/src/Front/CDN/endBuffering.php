<?php

namespace RocketCDN\Tests\Unit\src\Front\CDN;

use Mockery;
use RocketCDN\Front\CDN;
use RocketCDN\Options\Options;
use RocketCDN\Tests\Unit\TestCase;
use Brain\Monkey\Functions;
use function _PHPStan_76800bfb5\RingCentral\Psr7\str;

class Test_EndBuffering extends TestCase
{
    protected $options;
    protected $cdn;

    protected function setUp(): void
    {
        parent::setUp();
        $this->options = Mockery::mock( Options::class);
        $this->cdn = Mockery::mock(CDN::class.'[rewrite_url]', [$this->options]);
    }
    /**
     * @dataProvider configTestData
     */
    public function testShouldReturnExpected($config, $expected)
    {
        Functions\expect( 'home_url' )
            ->with()
            ->andReturn( $config['homeurl'] );
        Functions\expect( 'content_url' )
            ->with()
            ->andReturn( $config['wp-content'] );
        Functions\expect( 'includes_url' )
            ->with()
            ->andReturn( $config['wp-includes'] );
        Functions\expect( 'home_url' )
            ->with()
            ->andReturn( $config['homeurl'] );
        Functions\expect( 'wp_upload_dir' )
            ->with()
            ->andReturn( $config['wp_upload_dir'] );
        Functions\expect( 'wp_parse_url' )
            ->with($config['homeurl'], PHP_URL_HOST)
            ->andReturnFirstArg();
        Functions\expect( 'wp_parse_url' )
            ->andReturnUsing(function ($arg, $mode = null) use ($config) {
                if($mode == PHP_URL_PATH) {
                    return $arg;
                }
                if($mode == PHP_URL_HOST) {
                    return $config['hostname'];
                }
                return $arg;
            });
        $this->cdn->shouldReceive('rewrite_url')->atLeast()->once()->andReturnUsing
        (function($arg) use ($config) {
            // execute closure
            if(str_contains($arg, $config['cdn'])) {
                return $arg;
            }
            if(str_contains($arg, 'http://')) {
                $v = str_replace('http://', 'http://' . $config['cdn'] . '.', $arg);
            } else {
                if(!str_contains($arg, 'https://')) {
                    $v = 'http://' . $config['cdn'] . '.' . $config['hostname'] . $arg;
                } else {
                    $v = str_replace('https://', 'http://' . $config['cdn'] . '.', $arg);
                }
            }
            return $v;
        });
        $this->assertEquals($expected, $this->cdn->end_buffering($config['html']));
    }
}
