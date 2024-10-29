<?php
declare(strict_types=1);

namespace RocketCDN\Admin\Update;

use RocketCDN\EventManagement\SubscriberInterface;

class Subscriber implements SubscriberInterface {
    /**
     * Update instance
     *
     * @var Update
     */
    private $update;

    /**
     * Instantiates the class
     *
     * @param Update $upgrade Upgrade instance.
     */
    public function __construct( Update $update ) {
        $this->update = $update;
    }

    /**
     * Events this subscriber listens to
     *
     * @return array
     */
    public static function get_subscribed_events() {
        return [
            'admin_init' => [
                [ 'updater' ],
            ],
            'rocketcdn_update' => [
                [ 'update_cdn_url' ],
            ],
        ];
    }

    /**
     * Updater routine
     *
     * @return void
     */
    public function updater() {
        $this->update->updater();
    }

    /**
     * Update CDN URL
     *
     * @param string $old_version Old version.
     * @return void
     */
    public function update_cdn_url( $old_version ) {
        $this->update->update_cdn_url( $old_version );
    }
}