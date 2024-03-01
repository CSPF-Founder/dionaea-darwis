<?php

/**
 * Copyright (c) 2021 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

namespace Core\MongoDbWrapper;

use Exception;
use MongoDB\BSON\ObjectId;

class MongodbValidator
{
    public static function convertStringToMongodbId(string  $mongodb_id): ?ObjectId
    {
        try {
            return new ObjectId($mongodb_id);
        } catch (Exception $e) {
            return null;
        }
    }

    public static function isValidDynamicColumnName(string $field_name): bool
    {
        if (preg_match("/^[a-zA-Z0-9][a-zA-Z0-9.,_\\-\\& ]{1,190}$/", $field_name)) {
            return true;
        }
        return false;
    }
}
