<?php

namespace RocketCDN\Dependencies\LaunchpadFrameworkOptions\Traits;

use RocketCDN\Dependencies\LaunchpadOptions\Interfaces\OptionsInterface;

trait OptionsAwareTrait
{
    /**
     * Options facade.
     *
     * @var OptionsInterface
     */
    protected $options;

    /**
     * Set options facade.
     *
     * @param OptionsInterface $options Options facade.
     * @return void
     */
    public function set_options(OptionsInterface $options)
    {
        $this->options = $options;
    }
}