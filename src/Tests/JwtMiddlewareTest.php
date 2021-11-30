<?php

declare(strict_types=1);

namespace Ruvents\SpiralJwt\Tests;

use Ruvents\SpiralJwt\Auth\TokenStorage;
use Ruvents\SpiralJwt\Jwt\TokenEncoder;
use Ruvents\SpiralJwt\Middleware\JwtMiddleware;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Spiral\Auth\TokenStorageInterface;

/**
 * @internal
 */
class JwtMiddlewareTest extends TestCase
{
    public function testExpired(): void
    {
        $storage = new TokenStorage(new TokenEncoder('1234567890', 'HS256'), '+1 day');
        $token = $storage->create([], new \DateTimeImmutable('-1 second'));

        $request = new ServerRequest('GET', '/test', ['X-Auth-Token' => $token->getID()]);
        $handler = new class($storage) implements RequestHandlerInterface {
            public function __construct(private TokenStorageInterface $storage)
            {
            }

            public function handle(
                ServerRequestInterface $request,
            ): ResponseInterface {
                $this->storage->load($request->getHeaderLine('X-Auth-Token'));
            }
        };

        $this->expectExceptionCode(401);

        $middleware = new JwtMiddleware();
        $middleware->process($request, $handler);
    }
}
