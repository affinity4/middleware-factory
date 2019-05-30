<?php
namespace Affinity4\MiddlewareFactory\Tests;

use PHPUnit\Framework\TestCase;
use Affinity4\MiddlewareFactory\HttpErrorException;
use \RuntimeException;

class HttpErrorExceptionTest extends TestCase
{
    public function testContext()
    {
        // Execute
        $exception = HttpErrorException::create(500, ['context' => 'problem']);

        // Verify
        $this->assertSame(500, $exception->getCode());
        $this->assertSame(['context' => 'problem'], $exception->getContext());
    }

    public function testInvalid()
    {
        // Expect
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage((string) PHP_INT_MAX);

        // Execute
        HttpErrorException::create(PHP_INT_MAX);
    }
}
