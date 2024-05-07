<?php

namespace RocketCDN\Tests\Integration\src\Front\CDN\Subscriber;

use RocketCDN\Tests\Integration\TestCase;

class Test_startBuffering extends TestCase
{
    protected $config;

    public function set_up()
    {
        parent::set_up();
        add_filter('pre_option_rocketcdn_cdn_url', [$this, 'cdn_url']);
    }

    public function tear_down()
    {
        remove_filter('pre_option_rocketcdn_cdn_url', [$this, 'cdn_url']);
        parent::tear_down();
    }

    /**
     * @dataProvider providerTestData
     */
    public function testShouldRewrite($config, $expected)
    {
        $this->config = $config;
        $container = apply_filters('rocketcdn_container', null);
        $cdn = $container->get('cdn');
        $this->assertSame($expected['html'], $cdn->end_buffering($config['html']));
    }

    public function providerTestData() {
        return $this->getTestData( __DIR__, 'startBuffering' );
    }

    public function cdn_url()
    {
        return $this->config['cdn_url'];
    }
}