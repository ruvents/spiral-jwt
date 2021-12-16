<?php

declare(strict_types=1);

namespace Ruvents\SpiralJwt\Jwt;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final class TokenEncoder
{
    private string $key;

    private string $algorithm;

    public function __construct(string $key, string $algorithm)
    {
        $this->key = $key;
        $this->algorithm = $algorithm;
    }

    public function encode(array $payload): string
    {
        return JWT::encode($payload, $this->key, $this->algorithm);
    }

    public function decode(string $token): array
    {
        return (array) JWT::decode($token, new Key($this->key, $this->algorithm));
    }
}
