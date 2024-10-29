<?php
declare(strict_types=1);

namespace RocketCDN\Admin\Update;

use RocketCDN\API\Client;
use RocketCDN\Dependencies\LaunchpadCore\Container\AbstractServiceProvider;
use RocketCDN\Dependencies\League\Container\Definition\DefinitionInterface;

class ServiceProvider extends AbstractServiceProvider {
    /**
	 * Return IDs from admin subscribers.
	 *
	 * @return string[]
	 */
	public function get_admin_subscribers(): array {
		return [
            Subscriber::class,
        ];
	}

    /**
	 * Register the services.
	 *
	 * @return void
	 */
	public function define() {
        $this->register_service( Update::class )->set_definition(
			function ( DefinitionInterface $definition ) {
				$definition->addArgument( Client::class );
			}
		);
        $this->register_service( Subscriber::class )->share()->set_definition(
			function ( DefinitionInterface $definition ) {
				$definition->addArgument( Update::class );
			}
		);
    }
}
