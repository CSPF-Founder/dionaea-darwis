<?php

/**
 * Copyright (c) 2020 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

namespace Core;

use App\Config;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use Monolog\Logger;

class AppLogger
{
    /** @var Logger $debug_logger */
    public Logger $debug_logger;
    /** @var Logger $error_logger */
    public Logger $error_logger;

    public function __construct()
    {
        $output = "[%datetime%] %channel%.%level_name%: %message%\n";
        $formatter = new LineFormatter($output);

        $this->debug_logger = new Logger('App');
        $debug_rotating_handler = new RotatingFileHandler(
            Config::INFO_LOG_PATH,
            10,
            Level::Debug
        );
        $debug_rotating_handler->setFormatter($formatter);
        $this->debug_logger->pushHandler($debug_rotating_handler);

        $this->error_logger = new Logger('App');
        $error_rotating_handler = new RotatingFileHandler(
            Config::ERROR_LOG_PATH,
            10,
            Level::Debug
        );
        $error_rotating_handler->setFormatter($formatter);
        $this->error_logger->pushHandler($error_rotating_handler);
    }

    public static function getAppLogger()
    {
        static $app_logger;
        if (!$app_logger) {
            $app_logger = new AppLogger();
        }
        return $app_logger;
    }


    /**
     * @param $message
     */
    public static function debug($message)
    {
        if (is_string($message)) {
            // $message = Validator::sanitizeXss($message);
            static::getAppLogger()->debug_logger->debug($message);
        }
    }

    /**
     * @param $message
     */
    public static function info($message)
    {
        if (is_string($message)) {
            // $message = Validator::sanitizeXss($message);
            static::getAppLogger()->debug_logger->debug($message);
        }
    }

    /**
     * @param $message
     */
    public static function warning($message)
    {
        if (is_string($message)) {
            // $message = Validator::sanitizeXss($message);
            static::getAppLogger()->debug_logger->warning($message);
        }
    }

    /**
     * @param $message
     */
    public static function error($message)
    {
        if (is_string($message)) {
            // $message = Validator::sanitizeXss($message);
            static::getAppLogger()->error_logger->error($message);
        }
    }
}
