<?php

namespace RocketCDN\Dependencies\League\Container\Exception;

use RocketCDN\Dependencies\Psr\Container\NotFoundExceptionInterface;
use InvalidArgumentException;

class NotFoundException extends InvalidArgumentException implements NotFoundExceptionInterface
{
}
