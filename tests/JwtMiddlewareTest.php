<?php

declare(strict_types=1);

namespace Ruvents\SpiralJwt\Tests;

use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use Ruvents\SpiralJwt\Auth\TokenStorage;
use Ruvents\SpiralJwt\Jwt\TokenEncoder;
use Ruvents\SpiralJwt\Keys;
use Ruvents\SpiralJwt\JwtMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Spiral\Auth\TokenStorageInterface;

/**
 * @internal
 * @covers \Ruvents\SpiralJwt\JwtMiddleware
 */
class JwtMiddlewareTest extends TestCase
{
    public function testExpired(): void
    {
        $storage = new TokenStorage(new TokenEncoder(new Keys('1234567890'), 'HS256'), '+1 day');
        $token = $storage->create([], new \DateTimeImmutable('-1 second'));

        $request = (new ServerRequest())
            ->withUri(new Uri('/test'))
            ->withMethod('GET')
            ->withHeader('X-Auth-Token', $token->getID());

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
