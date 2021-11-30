<?php
declare(strict_types=1);

namespace RocketCDN\Admin\AdminBar;

use RocketCDN\Dependencies\League\Container\ServiceProvider\AbstractServiceProvider;

class ServiceProvider extends AbstractServiceProvider {
	/**
	 * Services provided by this provider
	 *
	 * @var array
	 */
	protected $provides = [
		'admin_bar',
		'admin_bar_subscriber',
	];

	/**
	 * Subscribers provided by this provider
	 *
	 * @var array
	 */
	public $subscribers = [
		'admin_bar_subscriber',
	];

	/**
	 * Registers the provided classes
	 *
	 * @return void
	 */
	public function register() {
		$this->getContainer()->add( 'admin_bar', 'RocketCDN\Admin\AdminBar\AdminBar' )
			->addArgument( $this->getContainer()->get( 'options' ) );
		$this->getContainer()->add( 'admin_bar_subscriber', 'RocketCDN\Admin\AdminBar\Subscriber' )
			->addArgument( $this->getContainer()->get( 'admin_bar' ) );
	}
}
