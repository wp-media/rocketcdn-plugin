<?php
declare(strict_types=1);

namespace RocketCDN\Dependencies\LaunchpadOptions\Interfaces;

use RocketCDN\Dependencies\LaunchpadOptions\Interfaces\Actions\DeleteInterface;
use RocketCDN\Dependencies\LaunchpadOptions\Interfaces\Actions\FetchInterface;
use RocketCDN\Dependencies\LaunchpadOptions\Interfaces\Actions\FetchPrefixInterface;
use RocketCDN\Dependencies\LaunchpadOptions\Interfaces\Actions\SetInterface;

/**
 * Define mandatory methods to implement when using this package
 */
interface OptionsInterface extends  FetchPrefixInterface, DeleteInterface, FetchInterface, SetInterface {}