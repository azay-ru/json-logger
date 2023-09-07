<?php

namespace Azay\Log;

use Psr\Log\AbstractLogger;

/**
 * One of the simplest implementations PSR-3 compatible Json logger
 */
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

    /**
     * @param string $filename
     * @param int $options JSON decoding options
     * @param string $timeFormat Time format ,
     * @param bool $insertBreaks Insert breaks line after each record
     */
    public function __construct(string $filename, int $options = self::DEFAULT_OPTIONS, string $timeFormat = self::DEFAULT_TIME_FORMAT, bool $insertBreaks = true)
    {
        $this->filename = $filename . (empty(pathinfo($filename, PATHINFO_EXTENSION)) ? '.json' : '');
        $this->options = $options;
        $this->timeFormat = $timeFormat;
        $this->break = $insertBreaks
            ? self::BREAKS
            : '';
    }

    public function log($level, $message='', array $context = [])
    {
        $record = [
            'time' => date($this->timeFormat),
            'level' => $level,
        ];

        if (!empty($message))
            $record['message'] = $message;

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