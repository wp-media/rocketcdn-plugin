<?php
namespace RocketCDN\Tests\Unit\src\Admin\Settings\Page;

use Mockery;
use RocketCDN\Admin\Settings\Page;
use RocketCDN\API\Client;
use RocketCDN\Tests\Unit\TestCase;
use Brain\Monkey\Functions;

/**
 * @covers \RocketCDN\Admin\Settings\Page::enqueue_assets
 *
 * @group Settings
 */
class Test_EnqueueAssets extends TestCase {
	protected $client;
	protected $page;

	protected function setUp(): void {
		parent::setUp();
		$this->client  = Mockery::mock( Client::class );
		$this->page    = new Page( $this->client,  WP_ROCKET_CDN_PLUGIN_ROOT . '/views/', '/' );
	}
	/**
	 * @dataProvider configTestData
	 */
	public function testShouldMaybeRegister( $config, $expected ) {
		if ( $config['should_register'] ) {
			Functions\expect( 'wp_create_nonce' )
				->with( 'rocketcdn_ajax' )
				->andReturn( 'nonce' );
			Functions\expect( 'wp_enqueue_style' )->with( $expected['style']['id'], $expected['style']['url'], $expected['style']['dependencies'], $expected['version'] );
			Functions\expect( 'wp_enqueue_script' )->with( $expected['script']['id'], $expected['script']['url'], $expected['script']['dependencies'], $expected['version'], true );
			Functions\expect( 'wp_localize_script' )->with(
				$expected['localize']['handle'],
				$expected['localize']['object'],
				[
					'nonce' => 'nonce',
				]
				);
		}else {
			Functions\expect( 'wp_enqueue_style' )->never();
			Functions\expect( 'wp_enqueue_script' )->never();
			Functions\expect( 'wp_localize_script' )->never();
		}
		$this->page->enqueue_assets( $config['hook'] );
	}
}
