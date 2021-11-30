<?php
declare(strict_types=1);

namespace RocketCDN\Admin\Notices;

use RocketCDN\EventManagement\SubscriberInterface;

class Subscriber implements SubscriberInterface {
	/**
	 * Notices instance
	 *
	 * @var Notices
	 */
	private $notices;

	/**
	 * Instantiates the class
	 *
	 * @param Notices $notices Notices instance.
	 */
	public function __construct( Notices $notices ) {
		$this->notices = $notices;
	}

	/**
	 * Events this subscriber listens to
	 *
	 * @return array
	 */
	public static function get_subscribed_events() {
		return [
			'admin_notices' => [
				[ 'empty_api_key_notice' ],
				[ 'wrong_api_key_notice' ],
			],
		];
	}

	/**
	 * Displays a notice if the API key is empty
	 *
	 * @return void
	 */
	public function empty_api_key_notice() {
		$this->notices->empty_api_key_notice();
	}

	/**
	 * Displays a notice if the API key is wrong
	 *
	 * @return void
	 */
	public function wrong_api_key_notice() {
		$this->notices->wrong_api_key_notice();
	}
}
