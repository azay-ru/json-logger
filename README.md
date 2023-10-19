# One of the simplest implementations PSR-3 compatible logger

## Requirements

- PHP 7 or greater
- Json extension

## Feature
- JSON output format

## Installation

Installation is possible using Composer.

```shell
composer require azay/json-logger
```

## Log levels
Logger supports the logging levels described by [RFC 5424](https://datatracker.ietf.org/doc/html/rfc5424).

| Level  |Severity|Description|Name in Logger| Constant in Logger |
|:------:|-|-|-|-------------|
|   0    |Emergency|system is unusable|`emergency`| EMERGENCY |
|   1    |Alert|action must be taken immediately|`alert`| ALERT       |
|   2    |Critical|critical conditions|`critical`| CRITICAL    |
|   3    |Error|error conditions|`error`| ERROR       |
|   4    |Warning|warning conditions|`warning`| WARNING     |
|   5    |Notice|normal but significant condition|`notice`| NOTICE      |
|   6    |Informational|informational messages|`info`| INFO        |
|   7    |Debug|debug-level messages|`debug`| DEBUG       |

## Usage

```php
<?php

use Azay\Log\JsonLogger;

require_once __DIR__ . '/vendor/autoload.php';

$logger = new JsonLogger(
    '/tmp/logger',
    JsonLogger::DEFAULT_JSON_OPTIONS,  // JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    JsonLogger::DEFAULT_TIME_FORMAT    // 'Y-m-d H:i:s'
);

//Optional, set maximum severity level, default `debug`
$logger->setLevel('info');
// or as constant
$logger->setLevel($logger::INFO);

$logger->info(
    'Info message for json logger',
    [
        'Foo' => 'Bar'
    ]
);

// This message will be ignored by severity
$logger->debug(
    'Verbose debug message',
    [
        'details' => 'Lorem ipsum...'
    ]
);
```

### Output
file `/tmp/logger.json`
```json
{"time":"2023-10-19 11:29:34","level":"info","message":"Info message for json logger","Foo":"Bar"}
```