<?php
namespace RocketCDN\Tests\Unit\src\Admin\Settings\Page;

use Mockery;
use RocketCDN\Admin\Settings\Page;
use RocketCDN\API\Client;
use RocketCDN\Options\Options;
use Brain\Monkey\Functions;

/**
 * @covers \RocketCDN\Admin\Settings\Page::render_page
 *
 * @group Settings
 */
class Test_RenderPage extends \RocketCDN\Tests\Unit\TestCase {
    protected $options;
    protected $client;
    protected $page;

    protected function setUp(): void
    {
        parent::setUp();
        $this->options = Mockery::mock( Options::class);
        $this->client = Mockery::mock( Client::class);
        $this->page = new Page($this->options, $this->client,  WP_ROCKET_CDN_PLUGIN_ROOT . '/views/', '/');
    }

    /**
     * @dataProvider configTestData
     */
    public function testShouldReturnAsExcepted($config, $expected) {
        Functions\expect( '__' )->andReturnFirstArg()->zeroOrMoreTimes();
        Functions\expect( 'esc_html__' )->andReturnFirstArg()->zeroOrMoreTimes();
        Functions\expect( 'esc_url' )->andReturnFirstArg()->zeroOrMoreTimes();
        Functions\expect( 'esc_html' )->andReturnFirstArg()->zeroOrMoreTimes();
        Functions\expect( 'esc_html_e' )->andReturnFirstArg()->zeroOrMoreTimes();
        Functions\expect( 'esc_attr' )->andReturnFirstArg()->zeroOrMoreTimes();
        Functions\expect( 'get_admin_page_title' )->andReturn($config['title'])->zeroOrMoreTimes();
        Functions\expect( 'settings_fields' )->with('rocketcdn')->andReturn($config['configs'])->zeroOrMoreTimes();
        $this->options->shouldReceive('get')->with('api_key')->andReturn($config['api'])->twice();
        $this->options->shouldReceive('get')->with('cdn_url')->andReturn($config['api'])->zeroOrMoreTimes();
        if($config['api']) {
            $this->client->expects()->is_website_sync($config['api'])->andReturn($config['is_sync']);
        }
        ob_start();
        $this->page->render_page();
        $this->assertEquals($this->format_the_html(ob_get_contents()), $this->format_the_html($expected));
        ob_end_clean();
    }
}