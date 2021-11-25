<?php
declare(strict_types=1);

namespace RocketCDN;

use RocketCDN\Dependencies\League\Container\Container;
use RocketCDN\EventManagement\EventManager;

class Plugin {
	/**
	 * Is the plugin loaded
	 *
	 * @var boolean
	 */
	private $loaded = false;

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

		$container = new Container();

		$container->add(
			'event_manager',
			function () {
				return new EventManager();
			}
			);

		$this->loaded = true;
	}
}
