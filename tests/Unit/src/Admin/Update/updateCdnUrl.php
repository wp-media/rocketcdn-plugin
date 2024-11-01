<?php
declare(strict_types=1);

namespace RocketCDN\Tests\Unit\src\Admin\Update;

use Brain\Monkey\Functions;
use Mockery;
use RocketCDN\Admin\Update\Update;
use RocketCDN\Api\Client;
use RocketCDN\Dependencies\LaunchpadOptions\Options;
use RocketCDN\Tests\Unit\TestCase;

/**
 * @covers \RocketCDN\Admin\Update\Update::update_cdn_url
 *
 * @group Admin
 * @group Update
 */
class TestUpdateCdnUrl extends TestCase {
    private $api_client;
    private $options;
    private $update;

    protected function setUp(): void {
        parent::setUp();

        $this->options    = Mockery::mock( Options::class );
        $this->api_client = Mockery::mock( Client::class );
        $this->update     = new Update( $this->api_client );

        $this->update->set_options( $this->options );
    }

    /**
     * @dataProvider configTestData
     */
    public function testShouldDoExpected( $config, $expected ) {
        $this->options->shouldReceive( 'get' )
            ->with( 'cdn_url', '' )
            ->andReturn( $config['cdn_url'] );

        $this->options->shouldReceive( 'get' )
            ->with( 'previous_cdn_url', '' )
            ->andReturn( $config['previous_cdn_url'] );

        Functions\when( 'delete_transient' )->justReturn();

        $this->api_client->shouldReceive( 'get_website_cdn_url' )
            ->andReturn( $config['remote_cdn_url'] );

        if ( $expected ) {
            $this->options->shouldReceive( 'set' )
                ->with( 'cdn_url', $config['remote_cdn_url'] )
                ->once();
            $this->options->shouldReceive( 'set' )
                ->with( 'previous_cdn_url', $config['cdn_url'] )
                ->once();
        } else {
            $this->options->shouldReceive( 'set' )
                ->with( 'cdn_url', $config['remote_cdn_url'] )
                ->never();
            $this->options->shouldReceive( 'set' )
                ->with( 'previous_cdn_url', $config['cdn_url'] )
                ->never();
        }

        $this->update->update_cdn_url( $config['old_version'] );
    }
}