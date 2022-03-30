<?php

namespace RocketCDN\Tests\Integration;

use RocketCDN\Tests\SettingsTrait;
use WPMedia\PHPUnit\Integration\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use SettingsTrait;

}
