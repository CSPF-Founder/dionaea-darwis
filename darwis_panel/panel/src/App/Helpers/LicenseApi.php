<?php

/**
 * Copyright (c) 2020 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

namespace App\Helpers;

use App\Config;
use App\Models\AppConfig;
use Core\AppError;
use Core\AppLogger;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class LicenseApi
{
    private const MODULE_NAME = "deception_tech";

    public static function getBaseUrl(): string
    {
        if (isset($_ENV["LICENSE_VALIDATION_API"]) && $_ENV["LICENSE_VALIDATION_API"]) {  // For local testing
            return $_ENV["LICENSE_VALIDATION_API"];
        } else {
            return "https://" . $_ENV["MAIN_DOMAIN"] . "/api/v1/";
        }
    }

    /**
     * @param $key
     * @return array|null
     */

    public static function activateLicense($key)
    {
        $headers = array(
            'Content-Type' => 'application/json',
        );

        try {
            $client = new Client(["base_uri" => static::getBaseUrl(), 'verify' => false]);

            $post_data = [
                "activation_key" => $key,
            ];

            $option = [
                'headers' => $headers,
                'json' => $post_data,
                'timeout' => 80, // Response timeout
                'connect_timeout' => 80, // Connection timeout
            ];

            $response = $client->post("license", $option);
            $response_content = json_decode($response->getbody(), true);

            return $response_content;
        } catch (\Exception $e) {
            if ($e instanceof ClientException) {
                $response = $e->getResponse();
                $response_content = json_decode($response->getbody(), true);
                return $response_content;
            }

            AppLogger::error($e->getMessage());
            return [];
        }
    }

    /**
     * To check if the current license still valid
     **/
    public static function validateLicense(): array
    {

        $output = [];

        $license_key = AppConfig::getValueByName(Config::LICENSE_KEY_SETTING_NAME);
        if (!$license_key || empty($license_key)) {
            return false;
        }

        try {
            $post_data = [
                "license_key" => $license_key,
                "module" => static::MODULE_NAME,
            ];

            $headers = array(
                'Content-Type' => 'application/json',
                'x-api-key' => $license_key,
            );

            $option = [
                'headers' => $headers,
                'json' => $post_data,
                'timeout' => 80, // Response timeout
                'connect_timeout' => 80, // Connection timeout
            ];

            $client = new Client(["base_uri" => static::getBaseUrl(), 'verify' => false]);
            $response = $client->post("license/validate", $option);
            if (!$response) {
                return [];
            }

            $response_content = json_decode($response->getbody(), true);

            if (
                isset($response_content['is_valid_license'])
                &&
                $response_content['is_valid_license'] == "true"
                &&
                isset($response_content['name'])
                &&
                !empty($response_content['name'])
            ) {
                return [
                    "name" => $response_content['name'],
                ];
            } elseif (isset($response_content['messages'])) {
                $error_message = "";
                if (is_array($response_content['messages'])) {
                    $error_message = implode("\n", $response_content['messages']);
                }
                throw new AppError($error_message);
            }
        } catch (AppError $e) {
            throw $e;
        } catch (\Exception $e) {
            if ($e instanceof ClientException) {
                $response = $e->getResponse();
                $response_content = json_decode($response->getbody(), true);

                if (isset($response_content['messages'])) {
                    $error_message = "";
                    if (is_array($response_content['messages'])) {
                        $error_message = implode("\n", $response_content['messages']);
                    }
                    throw new AppError($error_message);
                } else {
                    return $output;
                }
            }
            AppLogger::error($e->getMessage());
            return $output;
        }

        return $output;
    }
}
