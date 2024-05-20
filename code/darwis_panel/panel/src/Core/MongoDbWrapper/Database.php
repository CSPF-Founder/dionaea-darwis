<?php

namespace Core\MongoDbWrapper;

use Core\Security\Validator;
use MongoDB\Client;

class Database
{
    /** @var Client $db */
    protected $db_client;
    protected $db;

    private $host;
    private $username;
    private $password;
    private $db_name;
    private $db_uri;

    /**
     * @var bool
     */
    private $throw_exception;


    public function __construct(
        $host = null,
        $username = null,
        $password = null,
        $db_name = null,
        $throw_exception = false
    ) {   //Constructor:

        if ($host && $username && $password && $db_name) {
            $this->host = $host;
            $this->username = $username;
            $this->password = $password;
            $this->db_name = $db_name;
            $this->throw_exception = $throw_exception;
            $this->connect();
        }
    }

    public static function getConnectionFromUri($db_uri, $db_name): ?Database
    {
        try {
            $obj = new Database();
            $obj->db_name = $db_name;
            $obj->db_uri = $db_uri;
            $obj->db_client = new Client($db_uri);
            $obj->db = $obj->db_client->selectDatabase($db_name);
            return $obj;
        } catch (\Exception $exception) {
            AppLogger::error($exception);
        }
        return null;
    }


    protected function connect()
    {
        try {
            $this->db_client = new Client("mongodb://" . $this->host . "/" . $this->db_name, array("username" => $this->username, "password" => $this->password));
            $this->db = $this->db_client->selectDatabase($this->db_name);
        } catch (\Exception $exception) {
        }
    }

    /**
     * @param $collection_name
     * @return \MongoDB\Collection
     */
    public function getCollectionInstance($collection_name)
    {
        if (Validator::isValidTableName($collection_name)) {
            return $this->db->selectCollection($collection_name);
        }
    }
}
