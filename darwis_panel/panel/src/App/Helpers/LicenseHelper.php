<?php

/**
 * Copyright (c) 2021 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

namespace App\Helpers;

use App\Config;
use App\Models\AppConfig;
use App\Models\User;

class LicenseHelper
{
    public static function isLicenseKeyExists()
    {
        if (AppConfig::getValueByName(Config::LICENSE_KEY_SETTING_NAME)) {
            return true;
        }
        return false;
    }

    public static function localLicenseKeyMatch(string $license_key): bool
    {
        if (strlen($license_key) < 10) {
            return false;
        }

        $key_from_db = AppConfig::getValueByName(Config::LICENSE_KEY_SETTING_NAME);
        if (!$key_from_db) {
            return false;
        }

        if (
            substr($key_from_db, 0, 10) === substr($license_key, 0, 10)
            ||
            $key_from_db === $license_key
        ) {
            return true;
        }
        return false;
    }

    public static function isUserExists()
    {
        if (User::getNumberOfRows()) {
            return true;
        }
        return false;
    }

    public static function isSetupDone()
    {
        return static::isLicenseKeyExists() && static::isUserExists();
    }
}
