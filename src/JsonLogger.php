<?php

namespace Azay\Log;

use Psr\Log\AbstractLogger;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;

/**
 * One of the simplest implementations PSR-3 compatible Json logger
 */
class JsonLogger extends AbstractLogger
{
    const EMERGENCY = 0;
    const ALERT = 1;
    const CRITICAL = 2;
    const ERROR = 3;
    const WARNING = 4;
    const NOTICE = 5;
    const INFO = 6;
    const DEBUG = 7;

    const LEVELS = [
            self::EMERGENCY => LogLevel::EMERGENCY,
            self::ALERT     => LogLevel::ALERT,
            self::CRITICAL  => LogLevel::CRITICAL,
            self::ERROR     => LogLevel::ERROR,
            self::WARNING   => LogLevel::WARNING,
            self::NOTICE    => LogLevel::NOTICE,
            self::INFO      => LogLevel::INFO,
            self::DEBUG     => LogLevel::DEBUG
    ];

    const DEFAULT_JSON_OPTIONS = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
    const DEFAULT_TIME_FORMAT = 'Y-m-d H:i:s';
    const BREAKS = "\n";
    const DUPLICATE_PREFIX = 'context_';

    /**
     * @var string $filename
     */
    private $filename;
    /**
     * @var int $options
     */
    private $options;
    /**
     * @var string $timeFormat
     */
    private $timeFormat;
    /**
     * @var string $string
     */
    private $break;
    /**
     * @var int $level
     */
    private $level = 7;

    /**
     * @param string $filename
     * @param int    $jsonOptions  JSON decoding options
     * @param string $timeFormat   Time format ,
     * @param bool   $insertBreaks Insert breaks line after each record
     */
    public function __construct(string $filename, int $jsonOptions = self::DEFAULT_JSON_OPTIONS, string $timeFormat = self::DEFAULT_TIME_FORMAT, bool $insertBreaks = true)
    {
        $this->filename = $filename . (empty(pathinfo($filename, PATHINFO_EXTENSION)) ? '.json':'');
        $this->options = $jsonOptions;
        $this->timeFormat = $timeFormat;
        $this->break = $insertBreaks
                ? self::BREAKS
                :'';
    }

    public function log($level, $message = '', array $context = [])
    {
        $currentLevel = $this->getLevel($level);

        if ($currentLevel > $this->level) {
            return;
        }

        $record = [
                'time'  => date($this->timeFormat),
                'level' => self::LEVELS[$currentLevel]
        ];

        if ( ! empty($message)) {
            $record['message'] = $message;
        }

        foreach ($context as $key => $value) {
            $recordKey = array_key_exists($key, $record)
                    ? self::DUPLICATE_PREFIX . $key
                    :$key;
            $record[$recordKey] = $value;
        }

        file_put_contents(
                $this->filename,
                json_encode($record, $this->options) . $this->break,
                FILE_APPEND
        );
    }

    public function setLevel($level)
    {
        $this->level = $this->getLevel($level);
    }

    private function getLevel($level): int
    {
        if (is_int($level)) {
            return max(self::EMERGENCY, min(self::DEBUG, $level));
        }

        if (is_string($level)) {
            $lower = strtolower($level);
            $index = array_search($lower, self::LEVELS);

            if ($index===false) {
                throw new InvalidArgumentException('Level "' . $level . '" is not defined');
            }

            return $index;
        }

        throw new InvalidArgumentException('Level "' . var_export($level, true) . '" is not integer or string');
    }

}