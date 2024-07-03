<?php

namespace RocketCDN\Dependencies\LaunchpadCore\Dispatcher;

use RocketCDN\Dependencies\LaunchpadDispatcher\Dispatcher;

interface DispatcherAwareInterface {

	/**
	 * Setup WordPress hooks dispatcher.
	 *
	 * @param Dispatcher $dispatcher WordPress hooks dispatcher.
	 *
	 * @return void
	 */
	public function set_dispatcher( Dispatcher $dispatcher ): void;
}
