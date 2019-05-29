<?php
declare(strict_types = 1);

namespace Affinity4\MiddlewareFactory\Tests;

use GuzzleHttp\Psr7\Stream;
use Middlewares\Tests\Assets\MiddlewareWithTraits;
use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\Factory\GuzzleFactory;
use Middlewares\Utils\Factory\SlimFactory;
use PHPUnit\Framework\TestCase;
use Slim\Http\Response;

class TraitsTest extends TestCase
{
    public function testCreation()
    {
        $response = Dispatcher::run([
            (new MiddlewareWithTraits())
                ->streamFactory(new GuzzleFactory())
                ->responseFactory(new SlimFactory()),
        ]);

        $this->assertInstanceOf(Stream::class, $response->getBody());
        $this->assertInstanceOf(Response::class, $response);
    }
}
