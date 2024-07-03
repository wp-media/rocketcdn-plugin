<?php

namespace RocketCDN\Dependencies\LaunchpadFrameworkOptions\Traits;

use RocketCDN\Dependencies\LaunchpadOptions\Interfaces\TransientsInterface;

trait TransientsAwareTrait
{
    /**
     * Transients facade.
     *
     * @var TransientsInterface
     */
    protected $transients;

    /**
     * Set transients facade.
     *
     * @param TransientsInterface $transients Transients facade.
     * @return void
     */
    public function set_transients(TransientsInterface $transients)
    {
        $this->transients = $transients;
    }
}