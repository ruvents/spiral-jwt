<?php

declare(strict_types=1);

namespace Ruvents\SpiralJwt\Jwt;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Ruvents\SpiralJwt\Keys;

/**
 * Encodes and decodes payload to and from JWT token using given key and
 * algorithm.
 */
final class TokenEncoder
{
    public function __construct(private Keys $key, private string $algorithm)
    {
    }

    /**
     * Encodes given payload to JWT token.
     */
    public function encode(array $payload): string
    {
        return JWT::encode(
            $payload,
            $this->key->getPrivateKey(),
            $this->algorithm
        );
    }

    /**
     * Decodes given JWT token back to payload.
     */
    public function decode(string $token): array
    {
        return (array) JWT::decode(
            $token,
            new Key(
                $this->key->isSymmetric()
                    ? $this->key->getPrivateKey()
                    : $this->key->getPublicKey(),
                $this->algorithm
            )
        );
    }
}
