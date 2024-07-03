<?php

namespace RocketCDN\Dependencies\LaunchpadFrameworkOptions\Interfaces;

use RocketCDN\Dependencies\LaunchpadOptions\Interfaces\SettingsInterface;

interface SettingsAwareInterface
{
    /**
     * Set settings facade.
     *
     * @param SettingsInterface $settings Settings facade.
     * @return void
     */
    public function set_settings(SettingsInterface $settings);
}