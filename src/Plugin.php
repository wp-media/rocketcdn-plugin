<?php
declare(strict_types=1);

namespace RocketCDN;

use RocketCDN\Dependencies\League\Container\Container;
use RocketCDN\Dependencies\League\Container\ServiceProvider\ServiceProviderInterface;
use RocketCDN\EventManagement\EventManager;
use RocketCDN\EventManagement\SubscriberInterface;

class Plugin {
	/**
	 * Container instance.
	 *
	 * @var Container
	 */
	private $container;

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

		$this->container = new Container();

		$this->container->add(
			'event_manager',
			function () {
				return new EventManager();
			}
			);

		foreach ( $this->get_service_providers() as $service_provider ) {
			$service_provider_instance = new $service_provider();
			$this->container->addServiceProvider( $service_provider_instance );

			// Load each service provider's subscribers if found.
			$this->load_subscribers( $service_provider_instance );
		}

		$this->loaded = true;
	}

	/**
	 * Get list of service providers' classes.
	 *
	 * @return array Service providers.
	 */
	private function get_service_providers() {
		return [
			'RocketCDN\Admin\Settings\ServiceProvider',
		];
	}

	/**
	 * Load list of event subscribers from service provider.
	 *
	 * @param ServiceProviderInterface $service_provider_instance Instance of service provider.
	 *
	 * @return void
	 */
	private function load_subscribers( ServiceProviderInterface $service_provider_instance ) {
		if ( empty( $service_provider_instance->subscribers ) ) {
			return;
		}

		foreach ( $service_provider_instance->subscribers as $subscriber ) {
			$subscriber_object = $this->container->get( $subscriber );

			if ( $subscriber_object instanceof SubscriberInterface ) {
				$this->container->get( 'event_manager' )->add_subscriber( $subscriber_object );
			}
		}
	}
}
