# Tiny PSR-3 compatible Json logger

## Requirements

- PHP 7 or greater
- Json extension

## Limitations PSR-3 support
Not support direct call "log" method, use instead "info", "warning" etc.

## Installation

Installation is possible using Composer.

```shell
composer require azay/json-logger
```

## Usage
```php
use Azay\Log\JsonLogger;

$logger = new JsonLogger(
    '/tmp/log',
    JsonLogger::DEFAULT_OPTIONS,
    JsonLogger::DEFAULT_TIME_FORMAT
);

$logger->info(
    'Info message for json logger',
    [
        'Foo' => 'Boo'
    ]
);
```