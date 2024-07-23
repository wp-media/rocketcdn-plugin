<?php

namespace RocketCDN\Tests\Integration\src\Admin\AdminBar\Subscriber;

use RocketCDN\Tests\Integration\CapTrait;
use RocketCDN\Tests\Integration\TestCase;
use Brain\Monkey\Functions;
use WPDieException;
/**
 * @covers \RocketCDN\Admin\AdminBar\Subscriber::purge_cache
 * @uses \RocketCDN\Admin\AdminBar\AdminBar::purge_cache
 *
 * @group Admin
 */
class Test_PurgeCache extends TestCase
{
    use CapTrait;

    public static function setUpBeforeClass() : void {
        parent::setUpBeforeClass();

        self::hasAdminCapBeforeClass();
        self::setAdminCap();
    }

    public static function tearDownAfterClass(): void {
        parent::tearDownAfterClass();

        self::resetAdminCap();
    }

    public function set_up() {
        parent::set_up();

        unset( $_GET['_wpnonce'] );
    }

    public function tear_down() {
        unset( $_GET['_wpnonce'] );

        parent::tear_down();

        // Clean up.
        remove_filter( 'wp_redirect', [ $this, 'return_empty_string' ] );
    }

    public function testShouldWPNonceAysWhenNonceIsMissing() {
        Functions\expect( 'current_user_can' )->never();
        $this->expectException( WPDieException::class );
        $this->expectExceptionMessage( 'The link you followed has expired.' );
        do_action( 'admin_post_rocketcdn-purge-cache' );
    }

    public function testShouldWPNonceAysWhenNonceInvalid() {
        $_GET['_wpnonce'] = 'invalid';

        Functions\expect( 'current_user_can' )->never();

        $this->expectException( WPDieException::class );
        $this->expectExceptionMessage( 'The link you followed has expired.' );
        do_action( 'admin_post_rocketcdn-purge-cache' );
    }

    /**
     * Test should wp_die() when the current user doesn't have 'manage_options' capability.
     */
    public function testShouldWPDieWhenCurrentUserCant() {
        $user_id = $this->factory->user->create( [ 'role' => 'contributor' ] );
        wp_set_current_user( $user_id );
        $_GET['_wpnonce'] = wp_create_nonce( 'rocketcdn_purge_cache' );

        $this->assertFalse( current_user_can( 'manage_options' ) );

        $this->expectException( WPDieException::class );
        do_action( 'admin_post_rocketcdn-purge-cache' );
    }

    public function testSetTransientAndRedirectWhenCurrentUserCan() {
        // Set up everything.
        $user_id = $this->factory->user->create( [ 'role' => 'administrator' ] );
        wp_set_current_user( $user_id );
        $_REQUEST['_wp_http_referer'] = addslashes( 'http://example.com/wp-admin/options-general.php?page=wprocket#page_cdn' );
        $_SERVER['REQUEST_URI']       = $_REQUEST['_wp_http_referer'];
        $_GET['_wpnonce']             = wp_create_nonce( 'rocketcdn_purge_cache' );
        add_filter( 'wp_redirect', [ $this, 'return_empty_string' ] );

        // Yes, we do expect wp_die() when running tests.
        $this->expectException( WPDieException::class );

        // Run it.
        do_action( 'admin_post_rocketcdn-purge-cache' );

        $this->assertTrue( current_user_can( 'manage_options' ) );

    }

    public function  return_empty_string() {
        return '';
    }
}
