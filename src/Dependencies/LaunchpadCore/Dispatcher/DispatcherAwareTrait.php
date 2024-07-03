<?php

namespace RocketCDN\Dependencies\LaunchpadCore\Dispatcher;

use RocketCDN\Dependencies\LaunchpadDispatcher\Dispatcher;

trait DispatcherAwareTrait {

	/**
	 * WordPress hooks dispatcher.
	 *
	 * @var Dispatcher
	 */
	protected $dispatcher;

	/**
	 * Setup WordPress hooks dispatcher.
	 *
	 * @param Dispatcher $dispatcher WordPress hooks dispatcher.
	 *
	 * @return void
	 */
	public function set_dispatcher( Dispatcher $dispatcher ): void {
		$this->dispatcher = $dispatcher;
	}
}
