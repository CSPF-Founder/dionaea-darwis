<?php

/**
 * Copyright (c) 2017 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

namespace Core\Security;

use App\Config;
use DateTime;

/**
 * Class Validator
 * @package Core
 */
class Validator
{
    /**
     * Function to filter XSS
     * @param $input
     * @return string
     */
    public static function sanitizeXss($input)
    {
        if ($input !== null) {
            $filtered_input = htmlentities($input, ENT_QUOTES, "UTF-8");
            $filtered_input = str_replace("(", '&#40;', $filtered_input);
            $filtered_input = str_replace(")", '&#41;', $filtered_input);
            $filtered_input = str_replace("+", '&#43;', $filtered_input);
            $filtered_input = str_replace("{", '&#123;', $filtered_input);
            $filtered_input = str_replace("}", '&#125;', $filtered_input);
            $filtered_input = str_replace("[", '&#91;', $filtered_input);
            $filtered_input = str_replace("]", '&#93;', $filtered_input);
            return $filtered_input;
        }
    }


    /**
     * Validates whether the field exists or not in the request
     * Checks whether it is empty or not
     * @param $paramName
     * @param string $httpMethod
     * @return bool
     */
    public static function checkParamExists($paramName, $httpMethod = "POST")
    {

        if ($paramName) {
            $data = null;
            if ($httpMethod == "POST") {
                $data = $_POST;
            } elseif ($httpMethod == "GET") {
                $data = $_GET;
            }

            if ($data) {
                if (isset($data[$paramName])) {
                    if (is_array($data[$paramName]) && $data[$paramName]) {
                        return true;
                    } else {
                        $param = trim($data[$paramName]);
                        if ($param !== "") {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    /***
     * Validates whether the field exists or not in the request
     * Checks whether it is empty or not
     * @param $requiredParams
     * @param string $httpMethod
     * @return bool
     */
    public static function checkAllParamsExists(array $requiredParams, string $httpMethod = "POST"): bool
    {
        if ($requiredParams) {
            $i = 0;
            foreach ($requiredParams as $paramName) {
                if (self::checkParamExists($paramName, $httpMethod)) {
                    $i = $i + 1;
                } else {
                    return false;
                }
            }

            if ($i === count($requiredParams)) {
                return true;
            }
        }
        return false;
    }

    /** Checks whether the given name is valid or not
     * Only allows:
     *        a-z A-Z .(dot character) and white space
     *        and allows 1 to 190 characters
     *        The first character should be a letter
     * @param $name
     * @return bool
     */
    public static function isValidName($name)
    {
        if ($name && is_string($name)) {
            if (preg_match("/^[A-Za-z][a-zA-Z. ]{1,190}$/", $name)) {
                return true;
            }
        }
        return false;
    }


    /** Checks whether the given organization is valid or not
     * Only allows:
     *        a-z,A-Z, numbers
     *        .(dot character), comma, ampersand,hyphen, underscore and white space
     *        and allows 1 to 190 characters
     *   The first character should be a letter or number
     * @param $name
     * @return bool
     */
    public static function isValidOrganizationName($name)
    {
        if ($name && is_string($name)) {
            if (preg_match("/^[a-zA-Z0-9][a-zA-Z0-9.,_\\-\\& ]{1,190}$/", $name)) {
                return true;
            }
        }
        return false;
    }

    public static function isSha256Hash($hash)
    {
        if ($hash && is_string($hash)) {
            if (preg_match("/^[A-Fa-f0-9]{64}$/", $hash)) {
                return true;
            }
        }
        return false;
    }
    /** Checks whether the given name is valid or not
     * Only allows:
     *        a-z A-Z .(dot character) -(hyphen) and _(underscore)
     *        and allows 2 to 31 characters
     *        The first character should be a letter
     * @param $username
     * @return bool
     */
    public static function isValidUsername($username)
    {
        if ($username && is_string($username)) {
            if (preg_match("/^[A-Za-z][a-zA-Z0-9_]{1,30}$/", $username)) {
                return true;
            }
        }
        return false;
    }

    public static function isValidStripeId($id_value)
    {
        if ($id_value && is_string($id_value)) {
            if (preg_match("/^[A-Za-z][a-zA-Z0-9_]{1,255}$/", $id_value)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Function to validate the Email address
     * @param $email
     * @return bool|mixed
     */
    public static function isValidEmail($email)
    {
        if ($email && is_string($email)) {
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            return $email;
        }
        return false;
    }

    /**
     * Function to Check whether the given input is a valid alpha numeric text
     * @param $data
     * @return bool
     */
    public static function isValidAlphaNumeric($data)
    {
        if ($data && is_string($data)) {
            if (ctype_alnum($data)) {
                return true;
            }
        }

        return false;
    }


    /**
     * check if it is valid date & then return DateTime object
     * The date format should be YYYY-MM-DD (Y-M-D format)
     * Eg:
     *  2017-01-01 -> valid
     *  2017-1-1 -> not valid
     *  2017-28-01 -> not valid
     * @param $date_string
     * @param string $format
     * @return mixed
     * @internal param $date
     */
    public static function getDateTimeFromString($date_string, $format = "Y-m-d")
    {
        if ($date_string && is_string($date_string)) {
            $d = DateTime::createFromFormat($format, $date_string, Config::getTimeZone());
            if ($d && $d->format($format) === $date_string) {
                return $d;
            }
        }
        return false;
    }

    /**
     * check if it is valid column name for Database
     * @param $columnName
     * @return bool
     */
    public static function isValidColumnName($columnName)
    {
        return static::isValidTableName($columnName);
    }

    /**
     * check if it is valid table name for Database
     * allowed characters are characters, '_' '-' and length should be minimum 2 and maximum 40
     * @param $tableName
     * @return bool
     */
    public static function isValidTableName($tableName)
    {
        if ($tableName && is_string($tableName)) {
            if (preg_match("/^[A-Za-z][a-zA-Z0-9._-]{1,40}$/", $tableName)) {
                return true;
            }
        }
        return false;
    }


    /**
     * Validate given input is valid url
     * @param $url
     * @return bool
     */
    public static function isValidURL($url)
    {
        if ($url && is_string($url)) {
            $url = trim($url);
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                return true;
            }
        }
        return false;
    }

    /**
     * To check the url is relative url
     * @param $url
     * @return bool
     */
    public static function isRelativeUrl($url)
    {
        if ($url && is_string($url)) {
            if (strlen($url) === 1 && $url === "/") {
                return true;
            }

            $parsed = parse_url($url);
            if (
                empty($parsed['host']) && preg_match("/^\/[A-Za-z]/", $url)
                && ($url[1] != '/' && $url[1] != '\\')
            ) {
                return true;
            }
        }
        return false;
    }

    public static function isValidDomain($domain_name): bool
    {
        if ($domain_name && is_string($domain_name)) {
            if (preg_match("/^([0-9a-z-]+\.)?[0-9a-z-]+\.[a-z]{2,7}$/", $domain_name)) {
                return (filter_var('http://' . $domain_name, FILTER_VALIDATE_URL) === false) ? false : true;
            }
        }
        return false;
    }

    /**
     * Validate Ipv4
     * @param $ip
     * @return bool
     */
    public static function isValidIp($ip)
    {
        if ($ip && is_string($ip)) {
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Validate IPv6
     * @param $ip
     * @return bool
     */
    public static function isValidIpv6($ip)
    {
        if ($ip && is_string($ip)) {
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                if (strpos($ip, '::1') !== false) { //equivalent of 127.0.0.1 in IPv6
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Validate Range
     * Accepted Range Formats:
     *   x.x.x.0/24
     *   x.x.x.x-y
     *   x.x.x.x,x.x.x.y
     * @param $ip_range
     * @return bool
     */
    public static function isValidIpRange($ip_range, $allow_private_ip = false)
    {
        if ($ip_range && is_string($ip_range)) {
            $ip_range = trim($ip_range);
            if (strpos($ip_range, '/') !== false) {
                // if it is a cidr notation
                $range = array();
                $cidr = explode('/', $ip_range);
                $cidr_suffix = (int)$cidr[1];
                if ($cidr_suffix < 16) {
                    return false;
                }
                $range[0] = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
                $range[1] = long2ip((ip2long($range[0])) + pow(2, (32 - (int)$cidr[1])) - 1);

                if ($range[0]) {
                    $ip_network = $range[0];
                    if (Validator::isValidIp($ip_network, $allow_private_ip) || Validator::isValidIpv6($ip_network, $allow_private_ip)) {
                        return true;
                    }
                } else {
                    return false;
                }
            } elseif (strpos($ip_range, '-') !== false) {
                // if it is a range with hyphen
                $ip_network = explode('-', $ip_range);
                if ($ip_network) {
                    $ip_network = $ip_network[0];
                } else {
                    return false;
                }
                if (Validator::isValidIp($ip_network, $allow_private_ip) || Validator::isValidIpv6($ip_network, $allow_private_ip)) {
                    return true;
                }
            }
        }
        return false;
    }
}
