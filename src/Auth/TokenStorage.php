<?php

declare(strict_types=1);

namespace Ruvents\SpiralJwt\Auth;

use Ruvents\SpiralJwt\Jwt\TokenEncoder;
use Firebase\JWT\ExpiredException;
use Spiral\Auth\TokenInterface;
use Spiral\Auth\TokenStorageInterface;

final class TokenStorage implements TokenStorageInterface
{
    private TokenEncoder $tokenEncoder;

    private string $expiresAt;

    /** @var callable */
    private $time;

    public function __construct(
        TokenEncoder $tokenEncoder,
        string $expiresAt,
        callable $time = null
    ) {
        $this->tokenEncoder = $tokenEncoder;
        $this->expiresAt = $expiresAt;
        $this->time = $time ?? static function (string $offset): \DateTimeImmutable {
            return new \DateTimeImmutable($offset);
        };
    }

    /**
     * {@inheritdoc}
     */
    public function load(string $id): ?TokenInterface
    {
        try {
            $token = $this->tokenEncoder->decode($id);
        } catch (ExpiredException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            return null;
        }

        if (
            false === isset($token['data'])
            || false === isset($token['iat'])
            || false === isset($token['exp'])
        ) {
            return null;
        }

        return new Token(
            $id,
            $token,
            (array) $token['data'],
            (new \DateTimeImmutable())->setTimestamp($token['iat']),
            (new \DateTimeImmutable())->setTimestamp($token['exp'])
        );
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $payload, \DateTimeInterface $expiresAt = null): TokenInterface
    {
        $issuedAt = ($this->time)('now');
        $expiresAt = $expiresAt ?? ($this->time)($this->expiresAt);
        $token = [
            'iat' => $issuedAt->getTimestamp(),
            'exp' => $expiresAt->getTimestamp(),
            'data' => $payload,
        ];

        return new Token(
            $this->tokenEncoder->encode($token),
            $token,
            $payload,
            $issuedAt,
            $expiresAt
        );
    }

    /**
     * {@inheritdoc}
     */
    public function delete(TokenInterface $token): void
    {
    }
}
