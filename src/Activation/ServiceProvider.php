<?php
declare(strict_types=1);

namespace RocketCDN\Activation;

use RocketCDN\Dependencies\LaunchpadCore\Container\AbstractServiceProvider;
use RocketCDN\Dependencies\LaunchpadCore\Activation\HasActivatorServiceProviderInterface;

class ServiceProvider extends AbstractServiceProvider implements HasActivatorServiceProviderInterface {
	/**
	 * Returns list of activators.
	 *
	 * @return string[]
	 */
	public function get_activators(): array {
		return [
			Activation::class,
		];
	}

	/**
	 * Register the services.
	 *
	 * @return void
	 */
	public function define() {
		$this->register_service( Activation::class );
	}
}
