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
			'wp_resource_hints' => [ 'add_preconnect_cdn', 10, 2 ],
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

	/**
	 * Adds a preconnect tag for the CDN.
	 *
	 * @since 1.0
	 *
	 * @param array  $urls          The initial array of wp_resource_hint urls.
	 * @param string $relation_type The relation type for the hint: eg., 'preconnect', 'prerender', etc.
	 *
	 * @return array
	 */
	public function add_preconnect_cdn( array $urls, string $relation_type ): array {
		return $this->cdn->add_preconnect_cdn( $urls, $relation_type );
	}
}
