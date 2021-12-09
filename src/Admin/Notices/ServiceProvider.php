<?php
declare(strict_types=1);

namespace RocketCDN\Admin\Notices;

use RocketCDN\Dependencies\League\Container\ServiceProvider\AbstractServiceProvider;

class ServiceProvider extends AbstractServiceProvider {
	/**
	 * Services provided by this provider
	 *
	 * @var array
	 */
	protected $provides = [
		'admin_notices',
		'admin_notices_subscriber',
	];

	/**
	 * Subscribers provided by this provider
	 *
	 * @var array
	 */
	public $subscribers = [
		'admin_notices_subscriber',
	];

	/**
	 * Registers the provided classes
	 *
	 * @return void
	 */
	public function register() {
		$this->getContainer()->add( 'admin_notices', 'RocketCDN\Admin\Notices\Notices' )
			->addArgument( $this->getContainer()->get( 'options' ) )
			->addArgument( $this->getContainer()->get( 'api_client' ) );
		$this->getContainer()->add( 'admin_notices_subscriber', 'RocketCDN\Admin\Notices\Subscriber' )
			->addArgument( $this->getContainer()->get( 'admin_notices' ) );
	}
}
