<?php

namespace RocketCDN\Dependencies\LaunchpadDispatcher\Sanitizers;

use RocketCDN\Dependencies\LaunchpadDispatcher\Interfaces\SanitizerInterface;
use RocketCDN\Dependencies\LaunchpadDispatcher\Traits\IsDefault;

class StringSanitizer implements SanitizerInterface
{
    use IsDefault;

    public function sanitize($value)
    {
        if ( is_object($value) && ! method_exists($value, '__toString')) {
            return false;
        }

        if (is_array($value)) {
            return false;
        }

        return (string) $value;
    }
}