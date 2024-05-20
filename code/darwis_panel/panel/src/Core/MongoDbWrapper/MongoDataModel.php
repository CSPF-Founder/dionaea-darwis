<?php

/**
 * Copyright (c) 2017 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

namespace Core\MongoDbWrapper;

use Exception;
use MongoDB\BSON\ObjectId;
use MongoDB\Collection;
use MongoDB\Model\BSONDocument;

abstract class MongoDataModel extends MongoDatabase
{
    protected ObjectId $id;

    abstract public static function getDBInstance(): ?MongoDatabase;
    abstract public static function getCollectionName(): string;

    public static function getCurrentCollection(): ?Collection
    {
        $db = static::getDBInstance();
        $collection_name = static::getCollectionName();
        if ($collection_name) {
            return $db->getCollectionInstance($collection_name);
        }

        return null;
    }

    /**
     * Check if id string is valid mongo object id
     *
     * @param  string  $id
     * @return bool
     */
    public static function isValidObjectId($id)
    {
        return preg_match('/^[0-9a-fA-F]{24}$/', $id) === 1;
    }

    /**
     * Get Model object with id
     *
     * @return $this
     */
    public static function findById(ObjectId $id, $format = "object"): null | static | BSONDocument
    {
        if ($id && $id instanceof ObjectId) {
            $collection = static::getCurrentCollection();
            if ($collection) {
                $document = $collection->findOne(['_id' => $id]);

                if ($format == "array") {
                    return $document;
                } else {
                    return static::documentToObject($document);
                }
            }
        }

        return null;
    }



    /**
     * Get Model object with dynamic key provided to this function
     *
     * @param array
     * @return $this
     */
    protected static function findByFilters(array $filters): ?static
    {
        if (!empty($filters)) {
            $collection = static::getCurrentCollection();
            if ($collection) {
                $document = $collection->findOne($filters);

                return static::documentToObject($document);
            }
        }

        return null;
    }

    protected static function getListByFilters(array $filters, array $options = []): ?array
    {
        if (!empty($filters)) {
            $collection = static::getCurrentCollection();
            if ($collection) {
                $documents = $collection->find($filters, $options);

                $object_list = [];
                foreach ($documents as $document) {
                    $object_list[] = static::documentToObject($document);
                }

                return $object_list;
            }
        }

        return null;
    }

    /**
     * Insert object in collection
     *
     * @param array
     */
    public static function insertOne(array $data): ObjectId|false|null
    {
        $collection = static::getCurrentCollection();
        if ($collection) {
            // Insert data using parameterized query
            $result = $collection->insertOne($data);

            if ($result->getInsertedCount() === 1) {
                return $result->getInsertedId();
            }
        }

        return false;
    }

    /**
     * Delete object from collection
     *
     * @param array
     */
    public function delete(): bool
    {
        $collection = static::getCurrentCollection();
        if ($collection) {
            //delete collection
            $result = $collection->deleteOne(
                ['_id' => $this->id],
            );
            if ($result->getDeletedCount() > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Insert object in collection
     *
     * @param $id
     */
    public static function getObjectList(): array
    {
        $object_list = [];

        $collection = static::getCurrentCollection();
        if ($collection) {
            // Insert data using parameterized query
            $documents = $collection->find([]);
            foreach ($documents as $document) {
                $object_list[] = static::documentToObject($document);
            }
        }

        return $object_list;
    }

    /**
     * Insert object in collection
     *
     * @param $id
     */
    protected static function getObjectListByFilters(array $filters): array
    {
        if (empty($filters)) {
            return [];
        }

        $object_list = [];

        $collection = static::getCurrentCollection();
        if ($collection) {
            // Insert data using parameterized query
            $documents = $collection->find($filters);
            foreach ($documents as $document) {
                $object_list[] = static::documentToObject($document);
            }
        }

        return $object_list;
    }

    public static function documentToObject(?BSONDocument $document): ?static
    {
        if ($document != null && !empty($document) && count($document) > 0) {
            $instance = new static();

            foreach ($document as $key => $value) {
                // Check if key is _id then set id
                if ($key === '_id') {
                    $instance->id = $value;
                } elseif (property_exists($instance, $key) && static::validateKeyName($key)) {
                    $instance->$key = $value;
                }
            }

            return $instance;
        }

        return null;
    }

    public static function validateKeyName(string $key): bool
    {
        $pattern = "/^[a-zA-Z0-9_]+$/";

        if (preg_match($pattern, $key) === 1) {
            return true;
        }
        return false;
    }

    /**
     * Delete object from collection
     *
     * @param array
     */
    public function deleteByFilters(array $filters): bool
    {
        if (!$filters || empty($filters)) {
            return false;
        }

        $collection = static::getCurrentCollection();
        if ($collection) {
            $result = $collection->deleteMany(
                $filters
            );

            if ($result->getDeletedCount() > 0) {
                return true;
            }
        }

        return false;
    }

    public static function deleteById(string|ObjectId|null $id): bool
    {
        if (!$id) {
            return false;
        }

        if (is_string($id)) {
            try {
                $id = new \MongoDB\BSON\ObjectId($id);
            } catch (Exception $e) {
                return false;
            }
        }

        if ($id instanceof ObjectId) {
            $collection = static::getCurrentCollection();
            $delete_result = $collection->deleteOne(
                array(
                    "_id" => $id
                )
            );

            if ($delete_result->getDeletedCount()) {
                return true;
            }
        }
        return false;
    }

    public function getID(): ?ObjectId
    {
        return $this->id;
    }
}
