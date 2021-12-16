<?php

declare(strict_types=1);

namespace Ruvents\SpiralJwt\Bootloader;

use Ruvents\SpiralJwt\Auth\TokenStorage;
use Ruvents\SpiralJwt\Config\JwtConfig;
use Ruvents\SpiralJwt\Jwt\TokenEncoder;
use Ruvents\SpiralJwt\Middleware\JwtMiddleware;
use Spiral\Auth\TokenStorageInterface;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Bootloader\Auth\HttpAuthBootloader;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Config\Patch\Prepend;
use Spiral\Core\FactoryInterface;

final class JwtAuthBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        HttpAuthBootloader::class,
    ];

    protected const SINGLETONS = [
        TokenStorageInterface::class => TokenStorage::class,
        TokenEncoder::class => [self::class, 'tokenEncoder'],
        TokenStorage::class => [self::class, 'tokenStorage'],
    ];

    public function boot(ConfiguratorInterface $configurator): void
    {
        $configurator->setDefaults('jwt', [
            'algorithm' => 'HS256',
            'expiresAt' => '+1 week',
        ]);

        $configurator->modify('http', new Prepend('middleware', null, JwtMiddleware::class));
    }

    protected function tokenEncoder(
        FactoryInterface $factory,
        JwtConfig $config
    ): TokenEncoder {
        return $factory->make(TokenEncoder::class, [
            'key' => $config->getKey(),
            'algorithm' => $config->getAlgorithm(),
        ]);
    }

    protected function tokenStorage(
        FactoryInterface $factory,
        JwtConfig $config
    ): TokenStorageInterface {
        return $factory->make(TokenStorage::class, [
            'expiresAt' => $config->getExpiresAt(),
        ]);
    }
}
