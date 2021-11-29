<?php
declare(strict_types=1);

namespace RocketCDN\Admin\Settings;

use RocketCDN\EventManagement\SubscriberInterface;

class Subscriber implements SubscriberInterface {
	/**
	 * Page instance
	 *
	 * @var Page
	 */
	private $page;

	/**
	 * Instantiates the class
	 *
	 * @param Page $page Page instance.
	 */
	public function __construct( Page $page ) {
		$this->page = $page;
	}

	/**
	 * Events this subscriber listens to
	 *
	 * @return array
	 */
	public static function get_subscribed_events() {
		return [
			'admin_init' => 'configure',
			'admin_menu' => 'add_options_page',
		];
	}

	/**
	 * Configures the settings for the plugin
	 *
	 * @return void
	 */
	public function configure() {
		$this->page->configure();
	}

	/**
	 * Adds the plugin's options page
	 *
	 * @return void
	 */
	public function add_options_page() {
		add_options_page( 'RocketCDN', 'RocketCDN', 'manage_options', 'rocketcdn', [ $this->page, 'render_page' ] );
	}
}
