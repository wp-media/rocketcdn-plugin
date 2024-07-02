<?php
declare(strict_types=1);

namespace RocketCDN\Admin\Notices;

use RocketCDN\API\Client;
use RocketCDN\Dependencies\LaunchpadCore\Container\AbstractServiceProvider;
use RocketCDN\Dependencies\League\Container\Definition\DefinitionInterface;

class ServiceProvider extends AbstractServiceProvider {

	/**
	 * Returns common subscribers.
	 *
	 * @return string[]
	 */
	public function get_common_subscribers(): array {
		return [
			\RocketCDN\Admin\Notices\Subscriber::class,
		];
	}

	/**
	 * Register the services.
	 *
	 * @return void
	 */
	public function define() {
		$this->register_service( Notices::class )->set_definition(
			function ( DefinitionInterface $definition ) {
				$definition->addArgument( Client::class );
			}
			);

		$this->register_service( \RocketCDN\Admin\Notices\Subscriber::class )->share()->set_definition(
			function ( DefinitionInterface $definition ) {
				$definition->addArgument( Notices::class );
			}
			);
	}
}
