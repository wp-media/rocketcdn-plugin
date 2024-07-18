<?php

namespace RocketCDN\Tests\Unit\src\Admin\AdminBar\AdminBar;

use Mockery;
use RocketCDN\Admin\AdminBar\AdminBar;
use RocketCDN\API\Client;
use RocketCDN\Options\Options;
use RocketCDN\Tests\Unit\TestCase;
use Brain\Monkey\Functions;
use WP_Admin_Bar;

/**
 * @covers \RocketCDN\Admin\AdminBar\AdminBar::add_admin_bar_menu
 *
 * @group Admin
 */
class Test_AddAdminBarMenu extends TestCase {
	protected $options;
	protected $client;
	protected $admin_bar_menu;
	protected $admin_bar;

	protected function setUp(): void {
		$this->options        = Mockery::mock( Options::class );
		$this->client         = Mockery::mock( Client::class );
		$this->admin_bar      = Mockery::mock( WP_Admin_Bar::class );
		$this->admin_bar_menu = new AdminBar( $this->options, $this->client, '/' );
		parent::setUp();
	}

	/**
	 * @dataProvider configTestData
	 */
	public function testShouldReturnExpected( $config, $expected ) {
		Functions\expect( '__' )->zeroOrMoreTimes()
			->andReturnFirstArg();
		Functions\expect( 'admin_url' )->zeroOrMoreTimes()
			->andReturnFirstArg();
		Functions\expect( 'wp_nonce_url' )->zeroOrMoreTimes()
			->andReturnFirstArg();
		Functions\expect( 'wp_unslash' )->zeroOrMoreTimes()
			->andReturnFirstArg();
		Functions\expect( 'is_admin' )
			->with()
			->andReturn( $config['is_admin'] );

		if ( $config['is_admin'] ) {
			Functions\expect( 'current_user_can' )
				->with( 'manage_options' )
				->andReturn( $config['has_right'] );
		}
		if ( $config['has_right'] ) {
			$this->options->expects()->get( 'api_key', '' )->andReturn( $config['api'] );
		}
		if ( $config['api'] ) {
			$this->client->expects()->get_customer_data( $config['api'] )->andReturn( $config['data'] );
		}

		$_SERVER['REQUEST_URI'] = $config['request_uri'];

		foreach ( $expected as $node ) {
			$this->admin_bar->expects()->add_node( $node );
		}
		if ( count( $expected ) == 0 ) {
			$this->admin_bar->shouldReceive( 'addNode' )->never();
		}

		$this->admin_bar_menu->add_admin_bar_menu( $this->admin_bar );
	}
}
