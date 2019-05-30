# Affinity4: Middleware Factory

A fork of [Middlewares\Utils](https://github.com/middlewares/utils) package after they removed support and tests for Nyholm\Psr7. 

This fork also upgrades PHPUnit to 8.1+, requires PHP7.2+ and drops support for GuzzleHttp, Slim/Http and Zend Diactoros. The versions of these Http libraries in Middlewares\Utils were quite old anyways, and many of those libraries will soon have their own Psr17 implementations anyways

Nyholm\Psr7\Factory\Psr17Factory was chosen due to it's performance compared the other Psr7 implementations

## Factory

Used to create psr-7 instances of `ServerRequestInterface`, `ResponseInterface`, `StreamInterface` and `UriInterface`. Comes with support for [Nyholm/psr7](https://github.com/Nyholm/psr7) out of the box, but you can register any different factory using the [psr/http-factory](https://github.com/php-fig/http-factory) interface.

```php
use Affinity4\MiddlewareFactory\Factory;

$request = Factory::createServerRequest('GET', '/');
$response = Factory::createResponse(200);
$stream = Factory::createStream('Hello world');
$uri = Factory::createUri('http://example.com');

// By default MiddlewareFactory uses Nyholm\Psr7\Factory\Psr17Factory,
// but you can change it and add other classes
use Acme\Psr17Factory as AcmePsr17Factory
Factory::setStrategies([
    AcmePsr17Factory::class
]);

// And also register directly an initialized factory
Factory::setResponseFactory(new FooResponseFactory());

$fooResponse = Factory::createResponse();

// Get the PSR-17 factory used
$uriFactory = Factory::getUriFactory();
$uri = $uriFactory->createUri('http://hello-world.com');
```

## Dispatcher

Minimalist PSR-15 compatible dispatcher

```php
use Affinity4\MiddlewareFactory\Dispatcher;

$response = Dispatcher::run([
    new Middleware1(),
    new Middleware2(),
    new Middleware3(),
    function ($request, $next) {
        $response = $next->handle($request);
        return $response->withHeader('X-Foo', 'Bar');
    }
]);
```

## CallableHandler

To resolve and execute a callable. It can be used as a middleware, server request handler or a callable:

```php
use Affinity4\MiddlewareFactory\CallableHandler;

$callable = new CallableHandler(function () {
    return 'Hello world';
});

$response = $callable();

echo $response->getBody(); //Hello world
```

## HttpErrorException

General purpose exception used to represent HTTP errors

```php
use Affinity4\MiddlewareFactory\HttpErrorException;

$exception = HttpErrorException::create(500, [
    'problem' => 'Something bad happened',
]);

// Additional context can be get and set on the exception
$context = $exception->getContext();
```

## Traits

Common utilities shared between many middlewares like the ability to customize PSR-17 factories

* `HasResponseFactory`
* `HasStreamFactory`

---

## What Next

* Code style cleanup
* More tests to improve code coverage using @covers annotation to reduce false positives and ensure isolation of tests

Please see [CHANGELOG](CHANGELOG.md) for more information about recent changes and [CONTRIBUTING](CONTRIBUTING.md) for contributing details

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information

[link-packagist]: https://packagist.org/packages/affinity4/middleware-factory
[link-downloads]: https://packagist.org/packages/affinity4/middleware-factory
