<?php
declare(strict_types=1);

namespace RocketCDN\API;

use RocketCDN\Dependencies\LaunchpadCore\Container\AbstractServiceProvider;
use RocketCDN\Dependencies\LaunchpadOptions\Interfaces\OptionsInterface;

class ServiceProvider extends AbstractServiceProvider {
    public function define() {
        $this->register_service(Client::class);
    }
}
