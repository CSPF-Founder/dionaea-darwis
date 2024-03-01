<?php
/*
 * Copyright (c) 2022 CySecurity Pte. Ltd. - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by CySecurity Pte. Ltd.
 */

namespace App\ApiClient;

use Core\AppError;
use Core\AppLogger;
use Exception;
use GuzzleHttp\Client;

class Request
{
    public $client;
    public $options;
    public $bodyFormat;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->bodyFormat = 'json';
        $this->options = [
            'http_errors' => false,
        ];
    }

    public static function new(...$args)
    {
        return new self(...$args);
    }

    public function asJson()
    {
        return $this->bodyFormat('json')->contentType('application/json');
    }

    public function asFormParams()
    {
        return $this->bodyFormat('form_params')->contentType('application/x-www-form-urlencoded');
    }

    public function asMultipart()
    {
        return $this->bodyFormat('multipart');
    }

    public function bodyFormat($format)
    {
        $this->bodyFormat = $format;
        return $this;
    }

    public function contentType($contentType)
    {
        return $this->withHeaders(['Content-Type' => $contentType]);
    }

    public function accept($header)
    {
        return $this->withHeaders(['Accept' => $header]);
    }

    public function withHeaders($headers)
    {

        $this->options = array_merge_recursive($this->options, [
            'headers' => $headers
        ]);

        return $this;
    }

    public function get($url, $query_params = [])
    {
        return $this->send('GET', $url, $query_params = $query_params);
    }

    public function post($url, $params = [])
    {
        return $this->send('POST', $url, $params);
    }

    public function patch($url, $params = [])
    {
        return $this->send('PATCH', $url, $params);
    }

    public function put($url, $params = [])
    {
        return $this->send('PUT', $url, $params);
    }

    public function delete($url, $params = [])
    {
        return $this->send('DELETE', $url, $params);
    }

    public function send($method, $url, $params = [], $query_params = [])
    {
        $options = $this->options;
        // $query_params_from_url = $this->parseQueryParams($url);
        // if ($query_params_from_url) {
        // $query_params = $this->mergeOptions($query_params_from_url);
        // }

        if ($query_params) {
            $options['query'] = $query_params;
        }

        if ($this->bodyFormat == 'multipart') {
            $options[$this->bodyFormat] = $params;
            return $this->sendMultipart($method, $url, $options);
        } else {
            if ($params) {
                $options[$this->bodyFormat] = $params;
            }
            try {
                return new Response(
                    $this->client->request($method, $url, $options)
                );
            } catch (Exception $exception) {
                AppLogger::error($exception->getMessage());
                throw new AppError("Unable to access API");
            }
        }
    }

    protected function sendMultipart($method, $url, $options)
    {

        try {
            return new Response($this->client->request($method, $url, $options));
        } catch (Exception $exception) {
            throw new AppError("Internal Server Error");
        }
    }

    protected function mergeOptions(...$options)
    {
        return array_merge_recursive($this->options, ...$options);
    }

    protected function parseQueryParams($url)
    {
        $params = [];
        $parsed_query_string = parse_url($url, PHP_URL_QUERY);
        if ($parsed_query_string) {
            parse_str($parsed_query_string, $params);
        }
        return $params;
    }
}
