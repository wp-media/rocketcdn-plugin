<?php

namespace RocketCDN\Dependencies\LaunchpadCore\Deactivation;

interface DeactivationInterface {

	/**
	 * Executes this method on plugin deactivation
	 *
	 * @return void
	 */
	public function deactivate();
}
