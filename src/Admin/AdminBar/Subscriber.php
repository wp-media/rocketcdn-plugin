<?php
declare(strict_types=1);

namespace RocketCDN\Admin\AdminBar;

use RocketCDN\EventManagement\SubscriberInterface;

class Subscriber implements SubscriberInterface {
	/**
	 * AdminBar instance
	 *
	 * @var AdminBar
	 */
	private $admin_bar;

	/**
	 * Instantiate the class
	 *
	 * @param AdminBar $admin_bar AdminBar instance.
	 */
	public function __construct( AdminBar $admin_bar ) {
		$this->admin_bar = $admin_bar;
	}

	/**
	 * Events this subscriber listens to
	 *
	 * @return array
	 */
	public static function get_subscribed_events() {
		return [
			'admin_bar_menu'                   => [ 'add_admin_bar_menu', PHP_INT_MAX ],
			'admin_post_rocketcdn-purge-cache' => 'purge_cache',
			'admin_enqueue_scripts'            => 'enqueue_style',
		];
	}

	/**
	 * Adds RocketCDN admin bar menu
	 *
	 * @param \WP_Admin_Bar $wp_admin_bar WP_Admin_Bar instance.
	 *
	 * @return void
	 */
	public function add_admin_bar_menu( $wp_admin_bar ) {
		$this->admin_bar->add_admin_bar_menu( $wp_admin_bar );
	}

	/**
	 * Purges the cache from the admin bar
	 *
	 * @return void
	 */
	public function purge_cache() {
		$this->admin_bar->purge_cache();
	}

	/**
	 * Enqueue style for the custom part of the admin bar
	 *
	 * @return void
	 */
	public function enqueue_style() {
		$this->admin_bar->enqueue_style();
	}
}
