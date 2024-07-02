<?php
declare(strict_types=1);

namespace RocketCDN\Front;

use RocketCDN\Dependencies\LaunchpadCore\Container\AbstractServiceProvider;
use RocketCDN\Dependencies\League\Container\Definition\DefinitionInterface;

class ServiceProvider extends AbstractServiceProvider {
	/**
	 * Register the services.
	 *
	 * @return void
	 */
	public function define() {
		$this->register_service( CDN::class );
		$this->register_service( \RocketCDN\Front\Subscriber::class )->share()
			->set_definition(
				function ( DefinitionInterface $definition ) {
					$definition->addArgument( CDN::class );
				}
				);
	}

	/**
	 * Returns common subscribers.
	 *
	 * @return string[]
	 */
	public function get_common_subscribers(): array {
		return [
			\RocketCDN\Front\Subscriber::class,
		];
	}
}
