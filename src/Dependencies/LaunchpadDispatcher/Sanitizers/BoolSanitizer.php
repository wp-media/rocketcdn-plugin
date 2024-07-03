<?php

namespace RocketCDN\Dependencies\LaunchpadDispatcher\Sanitizers;

use RocketCDN\Dependencies\LaunchpadDispatcher\Interfaces\SanitizerInterface;
use RocketCDN\Dependencies\LaunchpadDispatcher\Traits\IsDefault;

class BoolSanitizer implements SanitizerInterface {

    use IsDefault;

    public function sanitize($value)
    {
        return (bool) $value;
    }
}