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
 * @covers \RocketCDN\Admin\Notices\Notices::dismiss_notice
 *
 * @group Admin
 * @group Notices
 */
class TestDismissNotice extends TestCase {
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
	public function testShouldDoExpected( $config, $expected ) {
        Functions\when( 'check_ajax_referer' )->justReturn( true );

		Functions\when( 'current_user_can' )->justReturn( $config['current_user_can'] );

        Functions\when( 'sanitize_key' )->returnArg();

        $_POST['notice_id'] = $config['notice_id'];

        Functions\when( 'get_current_user_id' )->justReturn( 1 );
        Functions\when( 'get_user_meta' )->justReturn( $config['user_meta'] );

        Functions\expect( 'update_user_meta' )
            ->atMost()
            ->once()
            ->with( 1, 'dismissed_notices', [ $config['notice_id'] ] );

        Functions\when( 'wp_send_json_error' )
            ->alias( function() use ( $expected ) {
                throw new \Exception( $expected );
            } );
        Functions\when( 'wp_send_json_success' )
            ->alias( function() use ( $expected ) {
                throw new \Exception( $expected );
            } );

        $this->expectException( \Exception::class );
        $this->expectExceptionMessage( $expected );

        $this->notices->dismiss_notice();
	}
}
