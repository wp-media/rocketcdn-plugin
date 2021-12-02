<?php
declare(strict_types=1);

namespace RocketCDN;

use RocketCDN\Dependencies\League\Container\Container;
use RocketCDN\Dependencies\League\Container\ServiceProvider\ServiceProviderInterface;
use RocketCDN\EventManagement\EventManager;
use RocketCDN\EventManagement\SubscriberInterface;
use RocketCDN\Options\Options;

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

		$this->container->add( 'template_basepath', realpath( plugin_dir_path( __DIR__ ) ) . '/views/' );
		$this->container->add(
			'options',
			function () {
				return new Options( 'rocketcdn_' );
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
			'RocketCDN\Admin\Notices\ServiceProvider',
			'RocketCDN\Admin\AdminBar\ServiceProvider',
			'RocketCDN\API\ServiceProvider',
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
