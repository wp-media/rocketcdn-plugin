<?php

namespace RocketCDN\Dependencies\LaunchpadFrameworkOptions\Interfaces;

use RocketCDN\Dependencies\LaunchpadOptions\Interfaces\OptionsInterface;

interface OptionsAwareInterface
{
    /**
     * Set options facade.
     *
     * @param OptionsInterface $options Options facade.
     * @return void
     */
    public function set_options(OptionsInterface $options);
}