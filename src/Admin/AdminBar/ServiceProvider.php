<?php
declare(strict_types=1);

namespace RocketCDN\Admin\AdminBar;

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
			\RocketCDN\Admin\AdminBar\Subscriber::class,
		];
	}

	/**
	 * Register the services.
	 *
	 * @return void
	 */
	public function define() {
		$this->register_service( AdminBar::class )->set_definition(
			function ( DefinitionInterface $definition ) {
				$definition->addArguments(
				[
					Client::class,
					'assets_baseurl',
				]
				);
			}
			);

		$this->register_service( \RocketCDN\Admin\AdminBar\Subscriber::class )->share()->set_definition(
			function ( DefinitionInterface $definition ) {
				$definition->addArgument( AdminBar::class );
			}
			);
	}
}
