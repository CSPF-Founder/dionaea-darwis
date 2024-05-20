<?php
/*
 * Copyright (c) 2022 CySecurity Pte. Ltd. - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by CySecurity Pte. Ltd.
 */

namespace App\ApiClient;

class Response
{
    public $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    public function body()
    {
        return (string) $this->response->getBody();
    }

    public function json($asArray = true)
    {
        return json_decode($this->response->getBody(), $asArray);
    }

    public function header($header, $asArray = false)
    {
        return $this->response->getHeader($header, $asArray);
    }

    public function headers()
    {
        return $this->response->getHeaders();
    }

    public function status()
    {
        return $this->response->getStatusCode();
    }

    public function __call($method, $args)
    {
        return $this->response->{$method}(...$args);
    }
}
