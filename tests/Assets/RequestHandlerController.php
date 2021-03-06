<?php
declare(strict_types=1);

namespace Affinity4\MiddlewareFactory\Tests\Assets;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RequestHandlerController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
    }
}
