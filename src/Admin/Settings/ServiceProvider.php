<?php
declare(strict_types=1);

namespace RocketCDN\Admin\Settings;

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
			\RocketCDN\Admin\Settings\Subscriber::class,
		];
	}

	/**
	 * Register the services.
	 *
	 * @return void
	 */
	public function define() {
		$this->register_service( Page::class )->set_definition(
			function ( DefinitionInterface $definition ) {
				$definition->addArguments(
				[
					Client::class,
					'template_basepath',
					'assets_baseurl',
				]
				);
			}
			);

		$this->register_service( \RocketCDN\Admin\Settings\Subscriber::class )->share()->set_definition(
			function ( DefinitionInterface $definition ) {
				$definition->addArgument( Page::class );
			}
			);
	}
}
