<?php

declare(strict_types=1);

namespace Ruvents\SpiralJwt;

use Ruvents\SpiralJwt\Keys;
use Spiral\Core\InjectableConfig;

final class JwtConfig extends InjectableConfig
{
    public const CONFIG = 'jwt';

    private Keys $key;

    private string $algorithm;

    private string $expiresAt;

    public function __construct(array $config)
    {
        [
            'key' => $this->key,
            'algorithm' => $this->algorithm,
            'expiresAt' => $this->expiresAt
        ] = $config;
    }

    public function getKey(): Keys
    {
        return $this->key;
    }

    public function getAlgorithm(): string
    {
        return $this->algorithm;
    }

    public function getExpiresAt(): string
    {
        return $this->expiresAt;
    }
}
