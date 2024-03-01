<?php

/**
 * Copyright (c) 2017 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

namespace Core;

use App\Config;
use Core\Security\Validator;
use DateTime;
use DateTimeZone;
use finfo;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Utils
{
    public static function redirectJSON($url)
    {

        echo '{"redirect":' . json_encode($url) . "}";
        exit();
    }
    public static function printError($msg)
    {
        echo $msg;
        exit();
    }

    public static function jsonError($message, $exitScript = true)
    {
        /*
         * To create error message in JSON format
         */
        echo '{"error":' . json_encode($message) . '}';
        if ($exitScript) {
            exit();
        }
    }
    public static function jsonSuccess($message, $exitScript = true)
    {
        /*
         * To create Success message in JSON format
         */
        echo '{"success":' . json_encode($message) . '}';
        if ($exitScript) {
            exit();
        }
    }

    /**
     * A method to print html content
     * Deliberately created replacement of 'echo' method to avoid using echo directly in the app
     * @param string $html
     */
    public static function printHtml(string $html)
    {
        echo $html;
    }

    /**
     * Function to delete the non-empty folder
     */
    public static function deleteFolder($path)
    {
        if (is_dir($path) === true) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($files as $file) {
                if (in_array($file->getBasename(), array('.', '..')) !== true) {
                    if ($file->isDir() === true) {
                        rmdir($file->getPathName());
                    } elseif (($file->isFile() === true) || ($file->isLink() === true)) {
                        unlink($file->getPathname());
                    }
                }
            }

            return rmdir($path);
        } elseif ((is_file($path) === true) || (is_link($path) === true)) {
            return unlink($path);
        }

        return false;
    }

    /**
     * Security Headers
     */
    public static function setSecurityHeaders()
    {
        foreach (Config::SECURITY_HEADERS as $header_value) {
            header($header_value);
        }
    }

    public static function calculateExpiryFromDuration($duration)
    {
        if (is_numeric($duration)) {
            if ($duration && $duration >= 1) {
                return new \DateTime('today +' . intval($duration) . "day", Config::getTimeZone());
            }
        }
    }

    /**
     * Get Random byte string
     * @param int $length
     * @return string
     */
    public static function getRandomBytes($length = 32)
    {
        $random_bytes = openssl_random_pseudo_bytes($length, $cstrong);
        return bin2hex($random_bytes);
    }


    public static function getRandomString(int $length = 32): string
    {
        $random_bytes = openssl_random_pseudo_bytes($length, $cstrong);
        return bin2hex($random_bytes);
    }


    /**
     * Convert Absolute url into relative url
     * @param $url
     * @return string
     */
    public static function getRelativeFromAbsoluteUrl($url)
    {
        if ($url) {
            $parsed = parse_url($url);
            $relative = $parsed["path"];
            if (isset($parsed["query"]) && $parsed["query"]) {
                $relative = $relative . "?" . $parsed["query"];
            }
            if ($relative && Validator::isRelativeUrl($relative)) {
                return $relative;
            }
        }
        return "";
    }

    /**
     * Gets the referer only if it is internal
     * @return string
     */
    public static function getInternalReferer()
    {
        return Utils::getRelativeFromAbsoluteUrl($_SERVER['HTTP_REFERER']);
    }

    /**
     * Rearrange the multiple uploaded-files array into a cleaner code:
     * Reference:
     * http://php.net/manual/en/features.file-upload.multiple.php
     * @param $files
     * @return mixed
     */
    public static function rearrangeFilesArray($files)
    {
        $cleanerArray = null;
        foreach ($files as $key => $all) {
            foreach ($all as $i => $val) {
                $cleanerArray[$i][$key] = $val;
            }
        }
        return $cleanerArray;
    }

    /**
     * @param $file_path
     * @return string|null
     */
    public static function getFileMimeType($file_path)
    {
        $result = new finfo();

        if ($result) {
            return $result->file($file_path, FILEINFO_MIME_TYPE);
        }

        return null;
    }

    public static function getDomainName()
    {
        $domain = 'apsaas.local.example';
        switch ($_SERVER['HTTP_HOST']) {
            case 'apsaas.local.example':
                $domain = 'apsaas.local.example';
                break;
        }
        return $domain;
    }

    public static function getSiteUrl()
    {
        return "https://" . static::getDomainName();
    }

    public static function getFileCreditCountFromSize($file_size)
    {
        $file_size = filter_var($file_size, FILTER_VALIDATE_INT);
        if (!$file_size) {
            return false;
        }

        if ($file_size > Config::FILE_SIZE_PER_FILE_CREDIT) {
            return ceil($file_size / Config::FILE_SIZE_PER_FILE_CREDIT);
        } else {
            return 1;
        }
    }

    public static function getHashCreditCountFromSize($file_size)
    {
        $file_size = filter_var($file_size, FILTER_VALIDATE_INT);
        if (!$file_size) {
            return false;
        }

        if ($file_size > Config::FILE_SIZE_PER_HASH_CREDIT) {
            return ceil($file_size / Config::FILE_SIZE_PER_HASH_CREDIT);
        } else {
            return 1;
        }
    }

    /**
     * @param $time_stamp
     * @param $time_zone
     * @param $dt_format
     * @return string
     * @throws \Exception
     */
    public static function timeStampToDateString($time_stamp, $time_zone, $dt_format)
    { /* input: 1518404518,America/Los_Angeles */
        $date = new DateTime(date("d F Y H:i:s", $time_stamp));
        $date->setTimezone(new DateTimeZone($time_zone));
        return $date->format($dt_format);
    }

    /**
     * Converts CIDR notation to ip range array
     * example input: 192.168.56.1/24
     * example output: array("start"=>"192.168.56.1", "end" => "192.168.56.255");
     */
    public static function cidrToRange(string $cidr): array
    {
        $range = [];
        $cidr = explode('/', $cidr);
        $cidr_size = (int)$cidr[1];

        if ($cidr_size < 1 || $cidr_size > 32) {
            // Incorrect cidr:
            return null;
        }

        $range["start"] = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr_size))));
        $range["end"] = long2ip((ip2long($range["start"])) + pow(2, (32 - (int)$cidr_size)) - 1);
        return $range;
    }

    /**
     * convert ip range into ip list
     * example inputs: 192.168.56.1-192.168.56.3 or 192.168.56.1-3
     * example output: array("192.168.56.1", "192.168.56.2", "192.168.56.3");
     */
    public static function ipRangeToList($starting_part, $ending_part)
    {
        if (!filter_var($starting_part, FILTER_VALIDATE_IP)) {
            return null;
        }

        $part1_splitted = explode(".", $starting_part);
        $part1_ip_prefix = $part1_splitted[0] . "." . $part1_splitted[1] . "." . $part1_splitted[2];

        $start_value = filter_var($part1_splitted[3], FILTER_VALIDATE_INT);
        if ($start_value === false) {
            return null;
        }

        $end_value = null;

        if (!str_contains($ending_part, ".")) {
            /**
             * If the ending part does not have dot, it means it is not ip but number
             * i.e in this format: 192.168.56.1-3, $ending_part will have the number "3"
             */
            $end_value = filter_var($ending_part, FILTER_VALIDATE_INT);
        } elseif (filter_var($ending_part, FILTER_VALIDATE_IP)) {
            /**
             * If ending part is valid IP
             * i.e in this format: 192.168.56.1-192.168.56.3, the $ending_part will be the "192.168.56.3"
             */
            $part2_splitted = explode(".", $ending_part);
            $part2_ip_prefix = $part2_splitted[0] . "." . $part2_splitted[1] . "." . $part2_splitted[2];

            if ($part1_ip_prefix != $part2_ip_prefix) {
                return null;
            }

            $end_value = filter_var($part2_splitted[3], FILTER_VALIDATE_INT);
        }

        if ($end_value === false || $start_value > $end_value) {
            return null;
        }

        if ($end_value > 255) {
            return null;
        }

        for ($i = $start_value; $i <= $end_value; $i++) {
            $ip = $part1_ip_prefix . "." . (string)$i;
            $ip_list[] = $ip;
        }
        return $ip_list;
    }

    public static function cidrToIPList(string $input_data): array
    {
        $ip_list = [];
        try {
            $ip_range = Utils::cidrToRange($input_data);

            if ($ip_range && isset($ip_range["start"]) && isset($ip_range["end"])) {
                $ip_list = Utils::ipRangeToList($ip_range["start"], $ip_range["end"]);
            }

            if ($ip_list) {
                return $ip_list;
            }
        } catch (\Exception $ex) {
        }
        return [];
    }

    /**
     * It always returns a string even if it is invalid input
     * * This is useful function for displaying in views
     * @param $json
     * @return string
     */
    public static function convertJsonToString($json): string
    {
        try {
            $json = json_encode($json, true);
            if ($json) {
                return $json;
            }
        } catch (\Exception $e) {
            return "";
        }
        return "";
    }


    /**
     * * Returns empty array if the array key or the array itself is empty
     */
    public static function getEmptyArrayIfKeyNotExists($array_input, $array_key)
    {
        return isset($array_input[$array_key]) ? $array_input[$array_key] : [];
    }

    /**
     * * Convert array of message string into a single string format
     */
    public static function arrayMessageToString($messages)
    {
        if (!$messages) {
            return "";
        }

        if (is_string($messages)) {
            return $messages;
        }

        $message_string = "";
        foreach ($messages as $index => $message) {
            $message_string .= $message . ",\n";
        }
        $message_string = rtrim($message_string, ",\n");
        return $message_string;
    }
}
