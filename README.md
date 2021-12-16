# Spiral JWT

This package implements authorization with
[JWT tokens](https://jwt.io/introduction) in Spiral applications. 
`firebase/php-jwt` is used for JWT-related work, reference its documentation
for list of supported algorithms.

## Installation

```sh
composer require ruvents/spiral-jwt
```

Then add `JwtAuthBootloader` to your `App.php`:

```php
use Ruvents\SpiralJwt\Bootloader\JwtAuthBootloader;

class App extends Kernel {
    protected const LOAD = [
        ...
        JwtAuthBootloader::class,
    ]
}
```


## Configuration

Default configuration is following:

```php
<?php

declare(strict_types=1);

return [
    'algorithm' => 'HS256',
    'expiresAt' => '+1 week',
];
```

Copy it and put to your configuration directory into `jwt.php` file:
`app/config/jwt.php`.

You **must** supply `key` for encryption and decryption.

Symmetric encryption:

```php
<?php

use Ruvents\SpiralJwt\Keys;

declare(strict_types=1);

return [
    'algorithm' => 'HS256',
    'expiresAt' => '+1 week',
    'key' => new Keys('*PRIVATE KEY*'),
];
```

Asymmetric encryption:

```php
<?php

use Ruvents\SpiralJwt\Keys;

declare(strict_types=1);

return [
    'algorithm' => 'RS256',
    'expiresAt' => '+1 week',
    'key' => new Keys('*PRIVATE KEY*', '*PUBLIC KEY*'),
];
```
