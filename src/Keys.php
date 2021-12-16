<?php

declare(strict_types=1);

namespace Ruvents\SpiralJwt;

/**
 * Storage for keys for both symmetric and asymmetric algorithms.
 */
final class Keys
{
    public function __construct(private string $privateKey, private ?string $publicKey = null) {
    }

    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }

    public function getPublicKey(): ?string
    {
        return $this->publicKey;
    }

    public function isSymmetric(): bool
    {
        return $this->publicKey === null;
    }
}
