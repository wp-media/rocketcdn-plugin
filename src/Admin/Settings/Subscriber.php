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
			'admin_init'                      => 'configure_settings',
			'admin_menu'                      => 'add_options_page',
			'admin_enqueue_scripts'           => 'enqueue_assets',
			'wp_ajax_rocketcdn_validate_key'  => 'validate_api_key',
			'wp_ajax_rocketcdn_update_key'    => 'update_api_key',
			'wp_ajax_rocketcdn_purge_cache'   => 'purge_cache',
			'update_option_rocketcdn_api_key' => 'save_cdn_url',
			'add_option_rocketcdn_api_key'    => 'save_cdn_url',
		];
	}

	/**
	 * Configures the settings for the plugin
	 *
	 * @return void
	 */
	public function configure_settings() {
		$this->page->configure_settings();
	}

	/**
	 * Adds the plugin's options page
	 *
	 * @return void
	 */
	public function add_options_page() {
		add_options_page( 'RocketCDN', 'RocketCDN', 'manage_options', 'rocketcdn', [ $this->page, 'render_page' ] );
	}

	/**
	 * Enqueue the assets for the settings page
	 *
	 * @param string $hook_suffix Current page hook.
	 */
	public function enqueue_assets( $hook_suffix ) {
		$this->page->enqueue_assets( $hook_suffix );
	}

	/**
	 * Validates the API key & website synchronization
	 *
	 * @return void
	 */
	public function validate_api_key() {
		$this->page->validate_api_key();
	}

	/**
	 * Updates the API key if valid and website is sync
	 *
	 * @return void
	 */
	public function update_api_key() {
		$this->page->update_api_key();
	}

	/**
	 * Purges the CDN cache
	 *
	 * @return void
	 */
	public function purge_cache() {
		$this->page->purge_cache();
	}

	/**
	 * Saves the CDN URL into the database
	 *
	 * @return void
	 */
	public function save_cdn_url() {
		$this->page->save_cdn_url();
	}
}
