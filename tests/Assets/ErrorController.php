<?php
declare(strict_types = 1);

namespace Affinity4\MiddlewareFactory\Tests\Assets;

use Exception;

final class ErrorController
{
    public function __construct()
    {
        throw new Exception('Error Processing Request');
    }

    public function __invoke()
    {
    }
}
