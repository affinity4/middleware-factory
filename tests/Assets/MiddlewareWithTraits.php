<?php
declare(strict_types = 1);

namespace Affinity4\MiddlewareFactory\Tests\Assets;

use Affinity4\MiddlewareFactory\Traits\HasResponseFactory;
use Affinity4\MiddlewareFactory\Traits\HasStreamFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareWithTraits implements MiddlewareInterface
{
    use HasResponseFactory;
    use HasStreamFactory;

    /**
     * Process a request and return a response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->createResponse()->withBody($this->createStream());
    }
}
