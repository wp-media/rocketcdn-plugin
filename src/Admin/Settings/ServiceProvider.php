<?php
declare(strict_types=1);

namespace RocketCDN\Admin\Settings;

use RocketCDN\Dependencies\League\Container\ServiceProvider\AbstractServiceProvider;

class ServiceProvider extends AbstractServiceProvider {
	/**
	 * Services provided by this provider
	 *
	 * @var array
	 */
	protected $provides = [
		'admin_page',
		'admin_subscriber',
	];

	/**
	 * Subscribers provided by this provider
	 *
	 * @var array
	 */
	public $subscribers = [
		'admin_subscriber',
	];

	/**
	 * Registers the provided classes
	 *
	 * @return void
	 */
	public function register() {
		$this->getContainer()->add( 'admin_page', 'RocketCDN\Admin\Settings\Page' )
			->addArgument( $this->getContainer()->get( 'options' ) )
			->addArgument( $this->getContainer()->get( 'api_client' ) )
			->addArgument( $this->getContainer()->get( 'template_basepath' ) )
			->addArgument( $this->getContainer()->get( 'assets_baseurl' ) );
		$this->getContainer()->add( 'admin_subscriber', 'RocketCDN\Admin\Settings\Subscriber' )
			->addArgument( $this->getContainer()->get( 'admin_page' ) );
	}
}
