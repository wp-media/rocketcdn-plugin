<?php
declare(strict_types=1);

namespace RocketCDN\Front;

use RocketCDN\Dependencies\League\Container\ServiceProvider\AbstractServiceProvider;

class ServiceProvider extends AbstractServiceProvider {
	/**
	 * Services provided by this provider
	 *
	 * @var array
	 */
	protected $provides = [
		'cdn',
		'cdn_subscriber',
	];

	/**
	 * Subscribers provided by this provider
	 *
	 * @var array
	 */
	public $subscribers = [
		'cdn_subscriber',
	];

	/**
	 * Registers the provided classes
	 *
	 * @return void
	 */
	public function register() {
		$this->getContainer()->add( 'cdn', 'RocketCDN\Front\CDN' )
			->addArgument( $this->getContainer()->get( 'options' ) );
		$this->getContainer()->add( 'cdn_subscriber', 'RocketCDN\Front\Subscriber' )
			->addArgument( $this->getContainer()->get( 'cdn' ) );
	}
}
