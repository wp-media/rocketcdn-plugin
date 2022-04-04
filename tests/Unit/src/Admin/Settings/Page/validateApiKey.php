<?php
namespace RocketCDN\Tests\Unit\src\Admin\Settings\Page;

use Brain\Monkey\Functions;
use Mockery;
use RocketCDN\Admin\Settings\Page;
use RocketCDN\API\Client;
use RocketCDN\Options\Options;

/**
 * @covers \RocketCDN\Admin\Settings\Page::validate_api_key
 *
 * @group Settings
 */
class Test_ValidateApiKey extends \RocketCDN\Tests\Unit\TestCase {

	protected $options;
	protected $client;
	protected $page;

	protected function setUp(): void {
		parent::setUp();
		$this->options = Mockery::mock( Options::class );
		$this->client  = Mockery::mock( Client::class );
		$this->page    = new Page( $this->options, $this->client,  WP_ROCKET_CDN_PLUGIN_ROOT . '/views/', '/' );
	}

	/**
	 * @dataProvider configTestData
	 */
	public function testShouldReturnAsExcepted( $config, $expected ) {
		$_POST['api_key'] = $config['api'];
		Functions\expect( '__' )->zeroOrMoreTimes()->andReturnFirstArg();
		Functions\expect( 'sanitize_key' )->zeroOrMoreTimes()->andReturnFirstArg();
		Functions\expect( 'check_ajax_referer' )->zeroOrMoreTimes();
		Functions\expect( 'current_user_can' )->with( 'manage_options' )->andReturn( $config['has_right'] );

		if ( $config['api'] ) {
			$this->client->expects()->get_customer_data( $config['api'] )->andReturn( $config['is_valid'] );
		}
		if ( ! empty( $config['is_valid'] ) ) {
			$this->client->expects()->is_website_sync( $config['api'] )->andReturn( $config['is_sync'] );
		}

		if ( $config['is_sync'] ) {
			Functions\expect( 'wp_send_json_success' )->with();
		} else {
			Functions\expect( 'wp_send_json_error' )->with( $expected['message'] );
		}
		$this->page->validate_api_key();
	}
}
