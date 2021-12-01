<?php
declare(strict_types=1);

namespace RocketCDN\Front;

use RocketCDN\EventManagement\SubscriberInterface;

class Subscriber implements SubscriberInterface {
	/**
	 * CDN instance
	 *
	 * @var CDN
	 */
	private $cdn;

	/**
	 * Instantiates the class
	 *
	 * @param CDN $cdn CDN instance.
	 */
	public function __construct( CDN $cdn ) {
		$this->cdn = $cdn;
	}

	/**
	 * Events this subscriber listens to
	 *
	 * @return array
	 */
	public static function get_subscribed_events() {
		return [
			'template_redirect' => [ 'start_buffering', 2 ],
		];
	}

	/**
	 * Setup output buffering
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function start_buffering() {
		$this->cdn->start_buffering();
	}
}
