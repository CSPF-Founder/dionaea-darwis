<?php

/**
 * Copyright (c) 2017 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

namespace Core;

use App\Config;
use Core\AppError;

class Error
{
    /**
     * Convert all errors into exceptions
     * @param type $level
     * @param type $message
     * @param type $file
     * @param type $line
     * @throws \ErrorException
     */
    public static function errorHandler($level, $message, $file, $line)
    {
        if (error_reporting() !== 0) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * @param $exception
     * @throws \Exception
     */
    public static function exceptionHandler($exception)
    {
        $code = $exception->getCode();
        try {
            if ($code != "404" && $code != 404) {
                AppLogger::error($exception->getMessage());
                AppLogger::error($exception->getTraceAsString());
            }
        } catch (\Exception $x) {
        }

        if ($code != 404 && $code != 403) {
            $code = 500;
        }
        http_response_code($code);

        if (Config::debugEnabled()) {
            if ($code && $code == "403") {
                AppLogger::error($exception->getMessage());
                View::render('ErrorPage/403.php', ['error_message' => $exception->getMessage()]);
            } elseif ($code && $code == "404") {
                View::render('ErrorPage/debug-error.php', ['exception' => $exception]);
            } else {
                AppLogger::error($exception->getMessage());
                View::render('ErrorPage/debug-error.php', ['exception' => $exception]);
            }
        } else {
            if ($exception instanceof AppError) {
                if ($code == 500) {
                    AppLogger::error($exception->getMessage());
                    View::render('ErrorPage/app-error.php', ['error_message' => $exception->getMessage()]);
                } elseif ($code) {

                    if ($code != "404" && $code != 404) {
                        AppLogger::error($exception->getMessage());
                    }

                    View::render('ErrorPage/' . $code . '.php', ['error_message' => $exception->getMessage()]);
                }
            } else {
                if ($code) {
                    View::render('ErrorPage/' . $code . ".php");
                }
            }
        }
    }
}
