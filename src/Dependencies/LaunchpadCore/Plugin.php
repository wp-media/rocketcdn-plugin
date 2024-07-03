<?php

namespace RocketCDN\Dependencies\LaunchpadCore;

use RocketCDN\Dependencies\LaunchpadCore\Container\AbstractServiceProvider;
use RocketCDN\Dependencies\LaunchpadCore\Container\HasInflectorInterface;
use RocketCDN\Dependencies\LaunchpadCore\Container\PrefixAwareInterface;
use RocketCDN\Dependencies\LaunchpadCore\Dispatcher\DispatcherAwareInterface;
use RocketCDN\Dependencies\LaunchpadCore\Dispatcher\Sanitizer\SubscriberSignaturesSanitizer;
use RocketCDN\Dependencies\LaunchpadCore\EventManagement\ClassicSubscriberInterface;
use RocketCDN\Dependencies\LaunchpadCore\EventManagement\OptimizedSubscriberInterface;
use RocketCDN\Dependencies\LaunchpadCore\EventManagement\Wrapper\SubscriberWrapper;
use RocketCDN\Dependencies\LaunchpadDispatcher\Dispatcher;
use RocketCDN\Dependencies\Psr\Container\ContainerExceptionInterface;
use RocketCDN\Dependencies\Psr\Container\ContainerInterface;
use RocketCDN\Dependencies\LaunchpadCore\Container\IsOptimizableServiceProvider;
use RocketCDN\Dependencies\LaunchpadCore\Container\ServiceProviderInterface;
use RocketCDN\Dependencies\LaunchpadCore\EventManagement\EventManager;
use RocketCDN\Dependencies\LaunchpadCore\EventManagement\SubscriberInterface;
use RocketCDN\Dependencies\Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class Plugin {

	/**
	 * Instance of Container class.
	 *
	 * @var ContainerInterface instance
	 */
	private $container;

	/**
	 * Instance of the event manager.
	 *
	 * @var EventManager
	 */
	private $event_manager;

	/**
	 * Wraps subscriber under the common subscriber.
	 *
	 * @var SubscriberWrapper
	 */
	private $subscriber_wrapper;

	/**
	 * Hook dispatcher.
	 *
	 * @var Dispatcher
	 */
	protected $dispatcher;

	/**
	 * Creates an instance of the Plugin.
	 *
	 * @param ContainerInterface $container Instance of the container.
	 * @param EventManager       $event_manager WordPress event manager.
	 * @param SubscriberWrapper  $subscriber_wrapper Wraps subscriber under the common subscriber.
	 * @param Dispatcher         $dispatcher Hook dispatcher.
	 */
	public function __construct( ContainerInterface $container, EventManager $event_manager, SubscriberWrapper $subscriber_wrapper, Dispatcher $dispatcher ) {
		$this->container          = $container;
		$this->event_manager      = $event_manager;
		$this->subscriber_wrapper = $subscriber_wrapper;
		$this->dispatcher         = $dispatcher;
	}

	/**
	 * Returns the Rocket container instance.
	 *
	 * @return ContainerInterface
	 */
	public function get_container() {
		return $this->container;
	}

	/**
	 * Loads the plugin into WordPress.
	 *
	 * @param array<string,mixed> $params Parameters to pass to the container.
	 * @param array               $providers List of providers from the plugin.
	 *
	 * @return void
	 *
	 * @throws ContainerExceptionInterface Error from the container.
	 * @throws NotFoundExceptionInterface Error when a class is not found on the container.
	 * @throws ReflectionException Error when a classname is invalid.
	 */
	public function load( array $params, array $providers = [] ) {

		foreach ( $params as $key => $value ) {
			$this->container->share( $key, $value );
		}

		/**
		 * Runs before the plugin is loaded.
		 */
		$this->dispatcher->do_action( "{$this->container->get('prefix')}before_load" );

		add_filter( "{$this->container->get('prefix')}container", [ $this, 'get_container' ] );

		$this->container->share( 'event_manager', $this->event_manager );
		$this->container->share( 'dispatcher', $this->dispatcher );

		$this->container->inflector( PrefixAwareInterface::class )->invokeMethod( 'set_prefix', [ $this->container->get( 'prefix' ) ] );
		$this->container->inflector( DispatcherAwareInterface::class )->invokeMethod( 'set_dispatcher', [ $this->container->get( 'dispatcher' ) ] );

		$providers = array_map(
			function ( $classname ) {
				if ( is_string( $classname ) ) {
					return new $classname();
				}

				return $classname;
			},
			$providers
			);

		$providers = $this->optimize_service_providers( $providers );

		foreach ( $providers as $service_provider ) {
			$this->container->addServiceProvider( $service_provider );
		}

		foreach ( $providers as $service_provider ) {
			if ( ! $service_provider instanceof HasInflectorInterface ) {
				continue;
			}
			$service_provider->register_inflectors();
		}

		foreach ( $providers as $service_provider ) {
			$this->load_init_subscribers( $service_provider );
		}

		foreach ( $providers as $service_provider ) {
			$this->load_subscribers( $service_provider );
		}

		/**
		 * Runs after the plugin is loaded.
		 */
		$this->dispatcher->do_action( "{$this->container->get('prefix')}after_load" );
	}

	/**
	 * Optimize service providers to keep only the ones we need to load.
	 *
	 * @param ServiceProviderInterface[] $providers Providers given to the plugin.
	 *
	 * @return ServiceProviderInterface[]
	 *
	 * @throws ContainerExceptionInterface Error from the container.
	 * @throws NotFoundExceptionInterface Error when a class is not found on the container.
	 */
	protected function optimize_service_providers( array $providers ): array {
		$optimized_providers = [];

		foreach ( $providers as $provider ) {
			if ( ! $provider instanceof IsOptimizableServiceProvider ) {
				$optimized_providers[] = $provider;
				continue;
			}
			$subscribers = array_merge( $provider->get_common_subscribers(), $provider->get_init_subscribers(), is_admin() ? $provider->get_admin_subscribers() : $provider->get_front_subscribers() );

			/**
			 * Plugin Subscribers from a provider.
			 *
			 * @param SubscriberInterface[] $subscribers Subscribers.
			 * @param AbstractServiceProvider $provider Provider.
			 *
			 * @return SubscriberInterface[]
			 */
			$subscribers = $this->dispatcher->apply_filters( "{$this->container->get('prefix')}load_provider_subscribers", new SubscriberSignaturesSanitizer(), $subscribers, $provider );

			if ( count( $subscribers ) === 0 ) {
				continue;
			}

			$optimized_providers[] = $provider;
		}

		return $optimized_providers;
	}

	/**
	 * Load list of event subscribers from service provider.
	 *
	 * @param ServiceProviderInterface $service_provider_instance Instance of service provider.
	 *
	 * @return void
	 *
	 * @throws ContainerExceptionInterface Error from the container.
	 * @throws NotFoundExceptionInterface Error when a class is not found on the container.
	 * @throws ReflectionException Error when a classname is invalid.
	 */
	private function load_init_subscribers( ServiceProviderInterface $service_provider_instance ) {
		$subscribers = $service_provider_instance->get_init_subscribers();

		/**
		 * Plugin Init Subscribers.
		 *
		 * @param SubscriberInterface[] $subscribers Subscribers.
		 *
		 * @return SubscriberInterface[]
		 */
		$subscribers = $this->dispatcher->apply_filters( "{$this->container->get('prefix')}load_init_subscribers", new SubscriberSignaturesSanitizer(), $subscribers );

		if ( empty( $subscribers ) ) {
			return;
		}

		foreach ( $subscribers as $subscriber ) {
			$subscriber_object = $this->container->get( $subscriber );
			if ( ! $subscriber_object instanceof ClassicSubscriberInterface ) {
				$subscriber_object = $this->subscriber_wrapper->wrap( $subscriber_object );
			}

			$this->event_manager->add_subscriber( $subscriber_object );
		}
	}

	/**
	 * Load list of event subscribers from service provider.
	 *
	 * @param ServiceProviderInterface $service_provider_instance Instance of service provider.
	 *
	 * @return void
	 *
	 * @throws ContainerExceptionInterface Error from the container.
	 * @throws NotFoundExceptionInterface Error when a class is not found on the container.
	 * @throws ReflectionException Error when a classname is invalid.
	 */
	private function load_subscribers( ServiceProviderInterface $service_provider_instance ) {

		$subscribers = $service_provider_instance->get_common_subscribers();

		if ( ! is_admin() ) {
			$subscribers = array_merge( $subscribers, $service_provider_instance->get_front_subscribers() );
		} else {
			$subscribers = array_merge( $subscribers, $service_provider_instance->get_admin_subscribers() );
		}

		/**
		 * Plugin Subscribers.
		 *
		 * @param SubscriberInterface[] $subscribers Subscribers.
		 * @param AbstractServiceProvider $service_provider_instance Provider.
		 *
		 * @return SubscriberInterface[]
		 */
		$subscribers = $this->dispatcher->apply_filters( "{$this->container->get('prefix')}load_subscribers", new SubscriberSignaturesSanitizer(), $subscribers, $service_provider_instance );

		if ( empty( $subscribers ) ) {
			return;
		}

		foreach ( $subscribers as $subscriber ) {
			$subscriber_object = $this->container->get( $subscriber );
			if ( ! $subscriber_object instanceof ClassicSubscriberInterface ) {
				$subscriber_object = $this->subscriber_wrapper->wrap( $subscriber_object );
			}

			$this->event_manager->add_subscriber( $subscriber_object );
		}
	}
}
