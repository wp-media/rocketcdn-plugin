<?php
declare(strict_types=1);

namespace RocketCDN\API;

use RocketCDN\Dependencies\League\Container\ServiceProvider\AbstractServiceProvider;

class ServiceProvider extends AbstractServiceProvider {
	/**
	 * Services provided by this provider
	 *
	 * @var array
	 */
	protected $provides = [
		'api_client',
	];

	/**
	 * Registers the provided classes
	 *
	 * @return void
	 */
	public function register() {
		$this->getContainer()->add( 'api_client', 'RocketCDN\API\Client' )
			->addArgument( $this->getContainer()->get( 'options' ) );
	}
}
