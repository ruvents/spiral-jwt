<?php

declare(strict_types=1);

namespace Ruvents\SpiralJwt\Auth;

use DateTimeInterface;
use Spiral\Auth\TokenInterface;

final class Token implements TokenInterface
{
    private string $id;

    private array $token;

    private array $payload;

    private \DateTimeImmutable $issuedAt;

    private \DateTimeImmutable $expiresAt;

    public function __construct(
        string $id,
        array $token,
        array $payload,
        \DateTimeImmutable $issuedAt,
        \DateTimeImmutable $expiresAt
    ) {
        $this->id = $id;
        $this->payload = $payload;
        $this->issuedAt = $issuedAt;
        $this->expiresAt = $expiresAt;
        $this->token = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function getID(): string
    {
        return $this->id;
    }

    public function getToken(): array
    {
        return $this->token;
    }

    /**
     * {@inheritdoc}
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getIssuedAt(): \DateTimeImmutable
    {
        return $this->issuedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getExpiresAt(): DateTimeInterface
    {
        return $this->expiresAt;
    }
}
