<?php

namespace RocketCDN\Dependencies\LaunchpadFrameworkOptions;


use RocketCDN\Dependencies\LaunchpadCore\Container\AbstractServiceProvider;
use RocketCDN\Dependencies\LaunchpadCore\Container\HasInflectorInterface;
use RocketCDN\Dependencies\LaunchpadCore\Container\InflectorServiceProviderTrait;
use RocketCDN\Dependencies\LaunchpadFrameworkOptions\Interfaces\OptionsAwareInterface;
use RocketCDN\Dependencies\LaunchpadFrameworkOptions\Interfaces\SettingsAwareInterface;
use RocketCDN\Dependencies\LaunchpadFrameworkOptions\Interfaces\TransientsAwareInterface;
use RocketCDN\Dependencies\LaunchpadOptions\Interfaces\OptionsInterface;
use RocketCDN\Dependencies\LaunchpadOptions\Interfaces\SettingsInterface;
use RocketCDN\Dependencies\LaunchpadOptions\Interfaces\TransientsInterface;
use RocketCDN\Dependencies\LaunchpadOptions\Options;
use RocketCDN\Dependencies\LaunchpadOptions\Settings;
use RocketCDN\Dependencies\LaunchpadOptions\Transients;
use RocketCDN\Dependencies\League\Container\Definition\DefinitionInterface;

class ServiceProvider extends AbstractServiceProvider implements HasInflectorInterface
{
    use InflectorServiceProviderTrait;

    protected function define()
    {
        $this->register_service(OptionsInterface::class)
            ->share()
            ->set_concrete(Options::class)
            ->set_definition(function (DefinitionInterface $definition) {
            $definition->addArgument('prefix');
        });

        $this->register_service(TransientsInterface::class)
            ->share()
            ->set_concrete(Transients::class)
            ->set_definition(function (DefinitionInterface $definition) {
            $definition->addArgument('prefix');
        });

        $this->register_service(SettingsInterface::class)
            ->share()
            ->set_concrete(Settings::class)
            ->set_definition(function (DefinitionInterface $definition) {
            $prefix = $this->container->get('prefix');
            $definition->addArguments([OptionsInterface::class, "{$prefix}settings"]);
        });
    }

    /**
     * Returns inflectors.
     *
     * @return array[]
     */
    public function get_inflectors(): array
    {
        return [
            OptionsAwareInterface::class => [
                'method' => 'set_options',
                'args' => [
                    OptionsInterface::class,
                ],
            ],
            TransientsAwareInterface::class => [
                'method' => 'set_transients',
                'args' => [
                    TransientsInterface::class,
                ],
            ],
            SettingsAwareInterface::class => [
                'method' => 'set_settings',
                'args' => [
                    SettingsInterface::class,
                ],
            ],
        ];
    }
}