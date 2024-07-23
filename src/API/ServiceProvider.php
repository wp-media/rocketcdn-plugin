<?php
declare(strict_types=1);

namespace RocketCDN\API;

use RocketCDN\Dependencies\LaunchpadCore\Container\AbstractServiceProvider;
class ServiceProvider extends AbstractServiceProvider {

	/**
	 * Register the services.
	 *
	 * @return void
	 */
	public function define() {
		$this->register_service( Client::class );
	}
}
