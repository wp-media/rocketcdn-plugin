<?php

namespace RocketCDN\Dependencies\LaunchpadFrameworkOptions\Interfaces;

use RocketCDN\Dependencies\LaunchpadOptions\Interfaces\TransientsInterface;

interface TransientsAwareInterface
{
    /**
     * Set transients facade.
     *
     * @param TransientsInterface $transients Transients facade.
     * @return void
     */
    public function set_transients(TransientsInterface $transients);
}