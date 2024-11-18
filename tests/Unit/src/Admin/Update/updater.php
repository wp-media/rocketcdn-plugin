<?php
declare(strict_types=1);

namespace RocketCDN\Tests\Unit\src\Admin\Update;

use Brain\Monkey\Functions;
use Mockery;
use RocketCDN\Admin\Update\Update;
use RocketCDN\API\Client;
use RocketCDN\Dependencies\LaunchpadOptions\Options;
use RocketCDN\Tests\Unit\TestCase;

/**
 * @covers \RocketCDN\Admin\Update\Update::updater
 *
 * @group Admin
 * @group Update
 */
class TestUpdater extends TestCase {
    private $api_client;
    private $options;
    private $update;

    protected function setUp(): void {
        parent::setUp();

        $this->options    = Mockery::mock( Options::class );
        $this->api_client = Mockery::mock( Client::class );
        $this->update     = new Update( $this->api_client );

        $this->update->set_options( $this->options );

        $this->stubEscapeFunctions();
    }

    protected function tearDown(): void {
        unset( $_GET['page'] );

        parent::tearDown();
    }

    /**
     * @dataProvider configTestData
     */
    public function testShouldDoExpected( $config, $expected ) {
        $this->options->shouldReceive( 'get' )
            ->with( 'current_version', '' )
            ->andReturn( $config['current_version'] );

        Functions\when( 'rocketcdn_get_constant' )
            ->justReturn( $config['plugin_version'] );

        $this->options->shouldReceive( 'set' )
            ->with( 'current_version', $config['plugin_version'] );

        if ( ! empty( $config['current_version'] ) ) {
            $this->options->shouldReceive( 'set' )
                ->with( 'previous_version', $config['current_version'] );
        } else {
            $this->options->shouldReceive( 'set' )
                ->with( 'previous_version', $config['plugin_version'] );
        }

        Functions\when( 'sanitize_key' )->returnArg();

        $_GET['page'] = $config['page'];

        Functions\when( 'admin_url' )
            ->alias( function( $path ) {
                return 'http://example.org/' . $path;
            } );

        Functions\when( 'wp_safe_redirect' )
            ->alias( function() use ( $expected ) {
                throw new \Exception( $expected );
            } );

        if ( ! $expected ) {
            $this->options->shouldReceive( 'set' )
                ->never();
        } else {
            $this->expectException( \Exception::class );
            $this->expectExceptionMessage( $expected );
        }

        $this->update->updater();
    }
}
