<?php
declare(strict_types = 1);

namespace Affinity4\MiddlewareFactory\Tests;

use Affinity4\MiddlewareFactory\Factory;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

class FactoryTest extends TestCase
{
    public function testResponse()
    {
        $response = Factory::createResponse();
        $responseFactory = Factory::getResponseFactory();

        $this->assertInstanceOf(ResponseFactoryInterface::class, $responseFactory);
        $this->assertInstanceOf(Psr17Factory::class, $responseFactory);

        $this->assertInstanceOf(ResponseInterface::class, $response);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->getBody()->isWritable());
        $this->assertTrue($response->getBody()->isSeekable());
    }

    public function testStream()
    {
        $stream = Factory::createStream('Hello world');
        $streamFactory = Factory::getStreamFactory();

        $this->assertInstanceOf(StreamFactoryInterface::class, $streamFactory);
        $this->assertInstanceOf(Psr17Factory::class, $streamFactory);

        $this->assertInstanceOf(StreamInterface::class, $stream);

        $this->assertTrue($stream->isWritable());
        $this->assertTrue($stream->isSeekable());
        $this->assertEquals('Hello world', (string) $stream);
    }

    public function testStreamWithResource()
    {
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, 'Hello world');

        $stream = Factory::getStreamFactory()->createStreamFromResource($resource);

        $this->assertInstanceOf(StreamInterface::class, $stream);

        $this->assertTrue($stream->isWritable());
        $this->assertTrue($stream->isSeekable());
        $this->assertEquals('Hello world', (string) $stream);
    }

    public function testUri()
    {
        $uri = Factory::createUri('http://example.com/my-path');
        $uriFactory = Factory::getUriFactory();

        $this->assertInstanceOf(UriFactoryInterface::class, $uriFactory);
        $this->assertInstanceOf(Psr17Factory::class, $uriFactory);

        $this->assertInstanceOf(UriInterface::class, $uri);

        $this->assertEquals('/my-path', $uri->getPath());
    }

    public function testRequest()
    {
        $serverRequest = Factory::createServerRequest('GET', '/', []);
        $serverRequestFactory = Factory::getServerRequestFactory();

        $this->assertInstanceOf(ServerRequestFactoryInterface::class, $serverRequestFactory);
        $this->assertInstanceOf(Psr17Factory::class, $serverRequestFactory);

        $this->assertInstanceOf(ServerRequestInterface::class, $serverRequest);

        $this->assertEquals('/', $serverRequest->getUri()->getPath());
        $this->assertEquals('GET', $serverRequest->getMethod());
        $this->assertTrue($serverRequest->getBody()->isWritable());
        $this->assertTrue($serverRequest->getBody()->isSeekable());
    }

    public function strategiesDataProvider(): array
    {
        return [
            [
                [
                    Psr17Factory::class
                ],
                Psr17Factory::class,
                Psr17Factory::class,
                Psr17Factory::class,
                Psr17Factory::class,
            ],
            [
                [
                    'NotFound',
                    [
                        'serverRequest' => Psr17Factory::class,
                        'response' => Psr17Factory::class,
                        'stream' => Psr17Factory::class,
                        'uri' => Psr17Factory::class,
                    ],
                ],
                Psr17Factory::class,
                Psr17Factory::class,
                Psr17Factory::class,
                Psr17Factory::class
            ],
        ];
    }

    /**
     * @dataProvider strategiesDataProvider
     */
    public function testStrategies(
        array $strategies,
        string $serverRequestFactoryClass,
        string $responseFactoryClass,
        string $streamFactoryClass,
        string $uriFactoryClass
    ) {
        Factory::setStrategies($strategies);

        $serverRequestFactory = Factory::getServerRequestFactory();
        $responseFactory = Factory::getResponseFactory();
        $streamFactory = Factory::getStreamFactory();
        $uriFactory = Factory::getUriFactory();

        $this->assertInstanceOf($serverRequestFactoryClass, $serverRequestFactory);
        $this->assertInstanceOf($responseFactoryClass, $responseFactory);
        $this->assertInstanceOf($streamFactoryClass, $streamFactory);
        $this->assertInstanceOf($uriFactoryClass, $uriFactory);
    }
}
