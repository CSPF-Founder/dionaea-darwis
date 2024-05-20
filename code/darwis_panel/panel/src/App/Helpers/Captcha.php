<?php

/**
 * Copyright (c) 2022 CySecurity Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by CySecurity Pte. Ltd.
 */

namespace App\Helpers;

class Captcha
{
    /**
     * CAPTCHA validtor
     * @return bool
     */
    public static function validate()
    {

        if (static::isCaptchaDisabled()) {
            // for local network & testing:
            return true;
        }

        $captcha = null;
        if (isset($_POST['g-recaptcha-response'])) {
            $captcha = $_POST['g-recaptcha-response'];
        }

        if (!$captcha) {
            return false;
        }

        try {
            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($_ENV['RECAPTCHA_SECRET_KEY'])
                .  '&response=' . urlencode($captcha);
            $response = file_get_contents($url);
            $responseKeys = json_decode($response, true);

            return $responseKeys["success"] ? true : false;
        } catch (\Exception $exception) {
        }
        return false;
    }

    public static function isCaptchaDisabled()
    {
        if (!isset($_ENV['DISABLE_CAPTCHA_CHECKING'])) {
            return false;
        }

        if (is_string($_ENV['DISABLE_CAPTCHA_CHECKING']) && $_ENV['DISABLE_CAPTCHA_CHECKING'] === 'true') {
            return true;
        } elseif (is_bool($_ENV['DISABLE_CAPTCHA_CHECKING']) && $_ENV['DISABLE_CAPTCHA_CHECKING'] === true) {
            return true;
        }

        return false;
    }
}
