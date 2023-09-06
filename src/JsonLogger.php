<?php

namespace Azay\Log;

use Psr\Log\AbstractLogger;

class JsonLogger extends AbstractLogger
{
    const DEFAULT_OPTIONS = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
    const DEFAULT_TIME_FORMAT = 'Y-m-d H:i:s';
    const BREAKS = "\n";
    const DUPLICATE_PREFIX = 'context_';

    private $filename;
    private $options;
    private $timeFormat;
    private $break;

    public function __construct(string $filename, int $options = self::DEFAULT_OPTIONS, string $timeFormat = self::DEFAULT_TIME_FORMAT, bool $insertBreaks = false)
    {
        $this->filename = $filename . (empty(pathinfo($filename, PATHINFO_EXTENSION)) ? '.json' : '');
        $this->options = $options;
        $this->timeFormat = $timeFormat;
        $this->break = $insertBreaks
            ? self::BREAKS
            : '';
    }

    public function log($level, $message, array $context = [])
    {
        $record = [
            'time' => date($this->timeFormat),
            'level' => $level,
            'message' => $message,
        ];

        foreach ($context as $key => $value) {
            $recordKey = array_key_exists($key, $record)
                ? self::DUPLICATE_PREFIX . $key
                : $key;
            $record[$recordKey] = $value;
        }

        file_put_contents(
            $this->filename,
            json_encode($record, $this->options) . $this->break,
            FILE_APPEND
        );

    }
}