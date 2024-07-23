<?php
namespace RocketCDN\Tests\Unit\src\Admin\Settings\Page;

use Mockery;
use RocketCDN\Admin\Settings\Page;
use RocketCDN\API\Client;
use RocketCDN\Dependencies\LaunchpadOptions\Options;
use RocketCDN\Tests\Unit\TestCase;

/**
 * @covers \RocketCDN\Admin\Settings\Page::save_cdn_url
 *
 * @group Settings
 */
class Test_SaveCdnUrl extends TestCase {
	protected $options;
	protected $client;
	protected $page;

	protected function setUp(): void {
		parent::setUp();
		$this->options = Mockery::mock( Options::class );
		$this->client  = Mockery::mock( Client::class );
		$this->page    = new Page( $this->client,  WP_ROCKET_CDN_PLUGIN_ROOT . '/views/', '/' );
        $this->page->set_options($this->options);
	}

	/**
	 * @dataProvider configTestData
	 */
	public function testShouldReturnAsExcepted( $config ) {
		$this->client->expects()->get_website_cdn_url()->andReturn( $config['cdn'] );
		if ( $config['cdn'] ) {
			$this->options->expects()->set( 'cdn_url', $config['cdn'] );
		}
		$this->page->save_cdn_url();
	}
}
