<?php

/**
 * Copyright (c) 2023 CySecurity Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by CySecurity Pte. Ltd.
 */

namespace App\Models;

use Core\DataModel;
use PDO;

class AppConfig extends DataModel
{
    public const TABLE_NAME = "app_config";

    //properties
    public $errors;

    protected $id;
    private $name;
    private $value;

    public function getError()
    {
        return $this->errors;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        if ($name) {
            $this->name = $name;
        } else {
            $this->errors[] = "Invalid Name";
        }
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setValue($value)
    {
        if ($value) {
            $this->value = $value;
        } else {
            $this->errors[] = "Invalid Value";
        }
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function nameExists($name)
    {
        $db = static::getDBInstance();

        $query = "SELECT name FROM  " . static::TABLE_NAME . " WHERE name=:name";
        $row_count = $db->getRowCount($query, array(
            "name" => $name
        ));

        if ($row_count == 0) {
            return false;
        }
        return true;
    }

    public function save()
    {
        $db = static::getDBInstance();

        if ($this->nameExists($this->name)) {
            // if exists, just updated it:
            $query = "UPDATE " . static::TABLE_NAME . " SET value=:value WHERE name=:name;";
            $updated_row = $db->modify($query, array(
                "name" => $this->name,
                "value" => $this->value
            ));

            if ($updated_row) {
                return true;
            }
        } else {
            $query = "INSERT INTO " . static::TABLE_NAME . " (name, value)"
                . " VALUES(:name, :value);";

            $inserted_row = $db->modify($query, array(
                "name" => $this->name,
                "value" => $this->value
            ));

            if ($inserted_row) {
                return true;
            }
        }

        return false;
    }


    public static function getValueByName($name)
    {
        if (!$name) {
            return null;
        }

        $db = static::getDBInstance();

        $query = "SELECT value FROM " . static::TABLE_NAME . " WHERE name=:name";

        $row = $db->fetchOne($query, array(
            array("name", $name, PDO::PARAM_STR)
        ), "bindParam");

        if ($row) {
            $value = json_decode($row["value"], true);
            if ($value) {
                return $value;
            } elseif (is_string($row["value"])) {
                return $row["value"];
            }
        }

        return null;
    }
}
