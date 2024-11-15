<?php
declare(strict_types=1);

namespace RocketCDN\Activation;

use RocketCDN\Dependencies\LaunchpadCore\Activation\ActivationInterface;
use RocketCDN\Dependencies\LaunchpadFrameworkOptions\Interfaces\OptionsAwareInterface;
use RocketCDN\Dependencies\LaunchpadFrameworkOptions\Traits\OptionsAwareTrait;

class Activation implements ActivationInterface, OptionsAwareInterface {
	use OptionsAwareTrait;
	/**
	 * Executes this method on plugin activation
	 *
	 * @return void
	 */
	public function activate() {
		if ( '' === $this->options->get( 'current_version', '' ) ) {
			$this->options->set( 'current_version', rocketcdn_get_constant( 'ROCKETCDN_VERSION', '' ) );
		}
	}
}
