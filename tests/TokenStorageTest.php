<?php

declare(strict_types=1);

namespace Ruvents\SpiralJwt\Tests;

use Ruvents\SpiralJwt\Auth\Token;
use Ruvents\SpiralJwt\Auth\TokenStorage;
use Ruvents\SpiralJwt\Jwt\TokenEncoder;
use Firebase\JWT\JWT;
use PHPUnit\Framework\TestCase;
use Ruvents\SpiralJwt\Keys;

/**
 * @internal
 */
final class TokenStorageTest extends TestCase
{
    private const KEY = 'verysecretkey';
    private const ALGORITHM = 'HS256';
    private const ISSUED_AT = 1608540269;
    private const EXPIRES_AT = self::ISSUED_AT + 5;
    private const ID = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MDg1NDAyNjksImV4cCI6MTYwODU0MDI3NCwiZGF0YSI6eyJ1c2VybmFtZSI6InVzZXJAbWFpbC5ib3gifX0.o9QytswrIeTrRwxHGFJFpCUW3lCgO_MjoJHGc7Z8DjE';
    private const PAYLOAD = [
        'username' => 'user@mail.box',
    ];

    public function testTokenCreation(): void
    {
        $encoder = new TokenEncoder(new Keys(self::KEY), self::ALGORITHM);
        $storage = new TokenStorage(
            $encoder,
            'not important',
            $this->getTimeProvider(self::ISSUED_AT)
        );

        /** @var Token */
        $token = $storage->create(
            self::PAYLOAD,
            (new \DateTimeImmutable())->setTimestamp(self::EXPIRES_AT)
        );

        $this->assertInstanceOf(Token::class, $token);
        $this->assertSame(self::ID, $token->getID());
        $this->assertSame(self::PAYLOAD, $token->getPayload());
        $this->assertSame(self::ISSUED_AT, $token->getIssuedAt()->getTimestamp());
        $this->assertSame(self::EXPIRES_AT, $token->getExpiresAt()->getTimestamp());
    }

    public function testTokenLoading(): void
    {
        JWT::$timestamp = self::ISSUED_AT;

        $encoder = new TokenEncoder(new Keys(self::KEY), self::ALGORITHM);
        $storage = new TokenStorage($encoder, 'not important');

        /** @var Token */
        $token = $storage->load(self::ID);

        $this->assertInstanceOf(Token::class, $token);
        $this->assertSame(self::ID, $token->getId());
        $this->assertSame(self::PAYLOAD, $token->getPayload());
        $this->assertSame(self::ISSUED_AT, $token->getIssuedAt()->getTimestamp());
        $this->assertSame(self::EXPIRES_AT, $token->getExpiresAt()->getTimestamp());

        JWT::$timestamp = null;
    }

    public function testReturningNullOnJwtException(): void
    {
        JWT::$timestamp = self::ISSUED_AT;

        $encoder = new TokenEncoder(new Keys(self::KEY), self::ALGORITHM);
        $storage = new TokenStorage($encoder, 'not important');

        /** @var null|Token */
        $token = $storage->load(mb_substr(self::ID, 0, mb_strlen(self::ID) - 1));

        $this->assertNull($token);

        JWT::$timestamp = null;
    }

    private function getTimeProvider(int $timestamp): callable
    {
        return static function () use ($timestamp): \DateTimeImmutable {
            return (new \DateTimeImmutable())->setTimestamp($timestamp);
        };
    }
}
