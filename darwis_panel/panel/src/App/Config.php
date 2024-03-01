<?php

/**
 * Copyright (c) 2017 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

namespace App;

define('ROOT_DIR', dirname(__DIR__) . '/');

use Core\DataModel;

class Config
{
    /**
     * Minimum number of password characters required
     */
    public const MIN_PASSWORD_LENGTH = 10;

    /**
     * App Session Configurations
     */
    public const SECURE_COOKE_FLAG = true;
    public const HTTP_ONLY_FLAG = true;
    public const SAME_SITE_FLAG = 'lax';

    //const SESSION_EXPIRY_TIME = 3600;
    public const SESSION_EXPIRY_TIME = 259200; //1day=86400 , 86400 * 3 days;
    public const SESSION_ID_NAME = "AppSessionId";

    //Security Headers
    public const SECURITY_HEADERS = array(
        "Strict-Transport-Security: max-age=31536000; includeSubDomains",
        "X-XSS-Protection: 1; mode=block",
        "X-Frame-Options: SAMEORIGIN",
        "X-Content-Type-Options: nosniff"
    );

    /**
     * App timezone
     */
    public const TIME_ZONE = 'Asia/Kolkata';

    /**
     * convert timezone string to datetimezone and return
     * @return \DateTimeZone
     */
    public static function getTimeZone()
    {
        return new \DateTimeZone(static::TIME_ZONE);
    }

    /**
     * Configuration to Load from Database
     */
    public const CONFIG_TABLE = "app_config";

    /**
     * Get Configuration from Database
     * @param $name
     */
    public static function get($name)
    {
    }

    /**
     * Set configuration in Database
     * @param $name
     * @param $value
     */
    public static function set($name, $value)
    {
    }

    public const LICENSE_KEY_SETTING_NAME = "LICENSE_KEY";

    // public const DATA_DIR = ROOT_DIR . "/data/";
    public const WEB_RESOURCE_DIR = ROOT_DIR . "App/Resources/";

    public const PUBLIC_RESOURCES_DIR =  ROOT_DIR . "public/resources/";

    public const INFO_LOG_PATH = ROOT_DIR . "../logs/web_info.log";
    public const ERROR_LOG_PATH = ROOT_DIR . "../logs/web_error.log";

    public const DT_MALWARE_ROOT_FOLDER = "/processed_files/";

    /**
     * Create table in database
     */
    public static function createTable()
    {
        $db = DataModel::getDBInstance();
        $db->dbSchemaModify(" CREATE TABLE " . static::CONFIG_TABLE . " (
                              `name` varchar(64) NOT NULL,
                              `value` text NOT NULL
                            ) 
                    ");
    }

    public static function allowSetup()
    {
        if (isset($_ENV["ALLOW_SETUP"]) && $_ENV["ALLOW_SETUP"] && $_ENV["ALLOW_SETUP"] === "true") {
            return true;
        }
        return false;
    }

    public static function debugEnabled()
    {
        if (isset($_ENV["DEBUG_MODE"]) && $_ENV["DEBUG_MODE"] && $_ENV["DEBUG_MODE"] === "true") {
            return true;
        }
        return false;
    }

    public static function captchaDisabled()
    {
        if (isset($_ENV["DISABLE_CAPTCHA_CHECKING"]) && $_ENV["DISABLE_CAPTCHA_CHECKING"] && $_ENV["DISABLE_CAPTCHA_CHECKING"] === "true") {
            return true;
        }
        return false;
    }

    public static function getXdtApiUrl(): string
    {
        return "https://" . $_ENV["MAIN_DOMAIN"] . "/xdt/api/v1/";
    }
}
