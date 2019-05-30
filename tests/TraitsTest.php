<?php
declare(strict_types = 1);

namespace Affinity4\MiddlewareFactory\Tests;

use PHPUnit\Framework\TestCase;

use Affinity4\MiddlewareFactory\Tests\Assets\MiddlewareWithTraits;

use Affinity4\MiddlewareFactory\Dispatcher;

use Nyholm\Psr7\Factory\Psr17Factory;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class TraitsTest extends TestCase
{
    public function testCreation()
    {
        $response = Dispatcher::run([
            (new MiddlewareWithTraits())
                ->responseFactory(new Psr17Factory())
                ->streamFactory(new Psr17Factory())
        ]);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(StreamInterface::class, $response->getBody());
    }
}
