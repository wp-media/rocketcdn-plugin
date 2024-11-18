<?php
declare(strict_types=1);

namespace RocketCDN\Tests\Unit\src\Admin\Settings\Page;

use Brain\Monkey\Functions;
use Mockery;
use RocketCDN\Admin\Settings\Page;
use RocketCDN\API\Client;
use RocketCDN\Dependencies\LaunchpadOptions\Options;
use RocketCDN\Tests\Unit\TestCase;

/**
 * @covers \RocketCDN\Admin\Settings\Page::update_api_key
 *
 * @group Settings
 */
class Test_UpdateApiKey extends TestCase {
	protected $options;
	protected $client;
	protected $page;

	protected function setUp(): void {
		parent::setUp();

		$this->options = Mockery::mock( Options::class );
		$this->client  = Mockery::mock( Client::class );
		$this->page    = new Page( $this->client,  WP_ROCKET_CDN_PLUGIN_ROOT . '/views/', '/' );
        $this->page->set_options( $this->options );

		$this->stubTranslationFunctions();
	}

	/**
	 * @dataProvider configTestData
	 */
	public function testShouldReturnAsExcepted( $config, $expected ) {
		$_POST['api_key'] = $config['api'];

		Functions\expect( 'check_ajax_referer' );
		Functions\when( 'sanitize_key' )->returnArg();
		Functions\when( 'current_user_can' )->justReturn( $config['has_right'] );

			Functions\expect( 'delete_transient' )
				->with( 'rocketcdn_customer_data' )
				->atMost()
				->once();

			$this->client->expects()->get_customer_data( $config['api'] )
				->atMost()
				->once()
				->andReturn( $config['is_valid'] );

			$this->client->expects()->is_website_sync( $config['api'] )
				->atMost()
				->once()
				->andReturn( $config['is_sync'] );

			$this->options->expects()->set( 'api_key', $config['api'] )
				->atMost()
				->once();
	
			Functions\when( 'wp_send_json_success' )->alias(
				function( $message ) use ( $expected ) {
					throw new \Exception( $expected );
				}
			);

			Functions\when( 'wp_send_json_error' )->alias(
				function( $message ) use ( $expected ) {
					throw new \Exception( $expected );
				}
			);

		$this->expectException( \Exception::class );
		$this->expectExceptionMessage( $expected );

		$this->page->update_api_key();
	}
}
