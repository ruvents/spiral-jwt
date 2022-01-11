<?php

declare(strict_types=1);

namespace Ruvents\SpiralJwt;

use Firebase\JWT;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Spiral\Http\Exception\ClientException\UnauthorizedException;
use Spiral\Http\Exception\HttpException;

final class JwtMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        try {
            return $handler->handle($request);
        } catch (JWT\ExpiredException $exception) {
            throw new UnauthorizedException('JWT token is expired');
        } catch (JWT\BeforeValidException $exception) {
            throw new UnauthorizedException('JWT token is not valid yet');
        } catch (JWT\SignatureInvalidException $exception) {
            throw new UnauthorizedException('JWT token signature is invalide');
        }
    }
}
