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

## Basic usage

```php
<?php

use Azay\Log\JsonLogger;

require_once __DIR__ . '/vendor/autoload.php';

$logger = new JsonLogger(/tmp/logger);
```

## Log levels
Logger supports the logging levels described by [RFC 5424](https://datatracker.ietf.org/doc/html/rfc5424).

|    Level    |Severity|Description|Name in Logger| Constant in Logger |
|:-----------:|-|-|-|-------------|
|      0      |Emergency|system is unusable|`emergency`| EMERGENCY |
|      1      |Alert|action must be taken immediately|`alert`| ALERT       |
|      2      |Critical|critical conditions|`critical`| CRITICAL    |
|      3      |Error|error conditions|`error`| ERROR       |
|      4      |Warning|warning conditions|`warning`| WARNING     |
|      5      |Notice|normal but significant condition|`notice`| NOTICE      |
|      6      |Informational|informational messages|`info`| INFO        |
| 7 (default) |Debug|debug-level messages|`debug`| DEBUG       |


### Set logging level for your app
```php
// Optional, set maximum severity level, default `debug`
$logger->setLevel('info');
// or as constant
$logger->setLevel($logger::INFO);
```

## Break Lines
```php
$logger->setBreakLines();   // Set default break line "\n" between records

$logger->setBreakLines("\n\r");  // Set custom break line
```

## Timestamp format
```php
$logger->setTimeFormat('r');  // Set timestamp to RFC 2822 format
```

## Flat mode
If "Flat Mode" is enabled, all contextual data is recorded at the same level.
Otherwise, the contextual data is inserted as it is.

```php
$logger->setFlatMode();     // Optional
```

## Example #1

```php
<?php

use Azay\Log\JsonLogger;

require_once __DIR__ . '/vendor/autoload.php';

$logger = new JsonLogger(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'simple_log');
$logger->setLevel(JsonLogger::INFO)
    ->setBreakLines()
    ->setTimeFormat('r')
    ->setFlatMode();

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
file `logger.json`
```json
{"timestamp":"Tue, 03 Jun 2025 12:47:25 +0000","level":"info","message":"Info message for json logger","Foo":"Bar"}
```

## Example #2

```php
<?php

use Azay\Log\JsonLogger;

require_once __DIR__ . '/vendor/autoload.php';

$logger = new JsonLogger(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'simple_log');

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
file `logger.json`
```json
{"timestamp":"2025-06-03 12:49:39","level":"info","message":"Info message for json logger","context":{"Foo":"Bar"}}{"timestamp":"2025-06-03 12:49:39","level":"debug","message":"Verbose debug message","context":{"details":"Lorem ipsum..."}}
```