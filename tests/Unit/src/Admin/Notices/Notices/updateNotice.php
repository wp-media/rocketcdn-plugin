<?php
declare(strict_types=1);

namespace RocketCDN\Tests\Unit\src\Admin\Notices\Notices;

use Brain\Monkey\Functions;
use Mockery;
use RocketCDN\Admin\Notices\Notices;
use RocketCDN\API\Client;
use RocketCDN\Dependencies\LaunchpadOptions\Options;
use RocketCDN\Tests\Unit\TestCase;

/**
 * @covers \RocketCDN\Admin\Notices\Notices::update_notice
 *
 * @group Admin
 * @group Notices
 */
class TestUpdateNotice extends TestCase {
	protected $options;
	protected $client;
	protected $notices;

	protected function setUp(): void {
        parent::setUp();

		$this->options = Mockery::mock( Options::class );
		$this->client  = Mockery::mock( Client::class );
		$this->notices = new Notices( $this->client );
        $this->notices->set_options( $this->options );

        $this->stubTranslationFunctions();
	}

	/**
	 * @dataProvider configTestData
	 */
	public function testShouldReturnExpected( $config, $expected ) {
		Functions\when( 'current_user_can' )->justReturn( $config['current_user_can'] );

        Functions\when( 'get_current_screen' )->justReturn( (object) [ 'id' => $config['current_screen_id'] ] );

        $this->options->shouldReceive( 'get' )
            ->with( 'api_key', '' )
            ->andReturn( $config['api_key'] );

        $this->options->shouldReceive( 'get' )
            ->with( 'cdn_url', '' )
            ->andReturn( $config['cdn_url'] );

        $this->options->shouldReceive( 'get' )
            ->with( 'previous_cdn_url', '' )
            ->andReturn( $config['previous_cdn_url'] );

        $this->options->shouldReceive( 'get' )
            ->with( 'previous_version', '' )
            ->andReturn( $config['previous_version'] );

        Functions\when( 'get_current_user_id' )->justReturn( 1 );
        Functions\when( 'get_user_meta' )->justReturn( $config['user_meta'] );

        $this->options->shouldReceive( 'get' )
            ->with( 'cdn_url', '' )
            ->andReturn( $config['cdn_url'] );

        $this->options->shouldReceive( 'get' )
            ->with( 'previous_cdn_url', '' )
            ->andReturn( $config['previous_cdn_url'] );

        ob_start();
        $this->notices->update_notice();

        $this->assertStringContainsString(
            $expected,
            ob_get_contents()
        );

        ob_end_clean();
	}
}
