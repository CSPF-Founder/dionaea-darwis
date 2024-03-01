<?php

/**
 * Copyright (c) 2017 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

namespace App\ApiClient;

use App\Config;
use App\Models\AppConfig;
use Core\AppError;
use Core\View;
use DateTime;
use Exception;
use GuzzleHttp\Client;

class ApiBuilder
{
    private $base_uri;

    public const LOCAL_TOKEN_SETTING_NAME = "API_TOKEN_INFO";

    public $client;
    public $request;

    public function __construct(string $base_uri = null)
    {
        if ($base_uri) {
            $this->base_uri = $base_uri;
        } else {
            $this->base_uri = Config::getXdtApiUrl();
        }
        $this->client = new Client(["base_uri" => $this->base_uri, 'verify' => false]);
        $this->request = new Request($this->client);
        $this->request->withHeaders([
            "x-api-key" => $this->getApiKey()
        ]);
    }

    /**
     * Get access token for accessing the API
     */
    public function getApiKey(): ?string
    {
        $api_key = AppConfig::getValueByName(Config::LICENSE_KEY_SETTING_NAME);
        return $api_key;
    }

    public function getAccessTokenFromApi(): ?string
    {
        $api_response = $this->authenticate();
        if (!$api_response) {
            throw new AppError("Unable to check the license");
        }

        if (!isset($api_response["access_token"]) || !$api_response["access_token"]) {
            $error_msgs = "";
            if (
                isset($api_response["messages"]) && is_array($api_response["messages"])
            ) {
                foreach ($api_response["messages"] as $msg) {
                    $error_msgs .= $msg . "\n";
                }
            } else {
                $error_msgs = "Unable to check the license";
            }

            throw new AppError($error_msgs);
        }


        $access_token = $api_response["access_token"];
        $expires_in = intval($api_response["expires_in"]) - 120;
        $expiry_time = date('Y-m-d H:i:s', (time() + $expires_in));

        $customer_name = "Customer";
        if (isset($api_response["name"]) && $api_response["name"]) {
            $customer_name = $api_response["name"];
        }

        $info_to_store = array(
            "access_token" => $access_token,
            "expiry_time" => $expiry_time
        );

        $config_obj = new AppConfig();
        $config_obj->setName(static::LOCAL_TOKEN_SETTING_NAME);
        $config_obj->setValue(json_encode($info_to_store));
        $config_obj->save();

        $config_obj = new AppConfig();
        $config_obj->setName("CUSTOMER_NAME");
        $config_obj->setValue($customer_name);
        $config_obj->save();


        // Store the services list
        $serviecs = [];
        if (isset($api_response["services"]) && $api_response["services"] && is_array($api_response["services"])) {
            $serviecs = $api_response["services"];
        }

        $config_obj = new AppConfig();
        $config_obj->setName("services");
        $config_obj->setValue(json_encode($serviecs));
        $config_obj->save();

        return $access_token;
    }

    public function authenticate(): ?array
    {
        $base_uri = "https://" . $_ENV["MAIN_DOMAIN"] . "/api/v1/";
        $auth_client = new Client(["base_uri" => $base_uri, 'verify' => false]);
        $auth_request = new Request($auth_client);
        $api_key = AppConfig::getValueByName(Config::LICENSE_KEY_SETTING_NAME);
        return $auth_request->asJson()->post('auth/token', [
            "api_key" => $api_key,
            "method" => "key",
            "get_services" => true,
        ])->json();
    }

    /**
     * Get the locally stored access token from the db
     * NOTE: returns value only if the local token is not expired yet
     */
    public function getStoredAccessToken(): ?string
    {
        $token_info = AppConfig::getValueByName(static::LOCAL_TOKEN_SETTING_NAME);
        if (!$token_info) {
            return null;
        }

        //if it is stored as string, decode it:
        if (!is_array($token_info)) {
            $token_info = json_decode($token_info);
        }

        if (!$token_info || !$token_info["access_token"] || !$token_info["expiry_time"]) {
            return null;
        }

        $current_time = new DateTime();
        $token_expiry_time = DateTime::createFromFormat('Y-m-d H:i:s', $token_info["expiry_time"]);

        if ($current_time < $token_expiry_time) {
            return $token_info["access_token"];
        }

        return null;
    }
}
