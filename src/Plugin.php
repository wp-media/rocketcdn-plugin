<?php
declare(strict_types=1);

namespace RocketCDN;

use RocketCDN\Dependencies\League\Container\Container;
use RocketCDN\EventManagement\EventManager;

class Plugin {
	/**
	 * Instance of Container class.
	 *
	 * @var Container instance
	 */
	private $container;

	/**
	 * Is the plugin loaded
	 *
	 * @var boolean
	 */
	private $loaded = false;

	/**
	 * Creates an instance of the Plugin.
	 *
	 * @param Container $container     Instance of the container.
	 */
	public function __construct( Container $container ) {
		$this->container = $container;

		add_filter( 'rocketcdn_container', [ $this, 'get_container' ] );
	}

	/**
	 * Returns the RocketCDN container instance.
	 *
	 * @return Container
	 */
	public function get_container() {
		return $this->container;
	}

	/**
	 * Checks if the plugin is loaded
	 *
	 * @return boolean
	 */
	private function is_loaded(): bool {
		return $this->loaded;
	}

	/**
	 * Loads the plugin in WordPress
	 *
	 * @return void
	 */
	public function load() {
		if ( $this->is_loaded() ) {
			return;
		}

		$this->container->add(
			'event_manager',
			function () {
				return new EventManager();
			}
			);

		$this->loaded = true;
	}
}
