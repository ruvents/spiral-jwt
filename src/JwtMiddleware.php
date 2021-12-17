<?php

declare(strict_types=1);

namespace Ruvents\SpiralJwt;

use Firebase\JWT\ExpiredException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Spiral\Http\Exception\HttpException;

final class JwtMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        try {
            return $handler->handle($request);
        } catch (ExpiredException $exception) {
            throw new HttpException($exception->getMessage(), 401);
        }
    }
}
