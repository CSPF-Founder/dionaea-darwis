<?php

/**
 * Copyright (c) 2023 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

namespace App\Models;

use App\Config;
use Core\DataModel;
use Core\Role;
use Core\Security\Validator;

class User extends DataModel
{
    public const TABLE_NAME = "users";

    private $roles;

    //Properties:
    protected $name;
    protected $username;
    protected $password;
    protected $email;

    /**
     * Error messages
     * @var array
     */
    protected $errors = [];

    public function __construct()
    {

        // To validate & convert, property is assigned using PDO::FETCH_CLASS,
        if ($this->id !== null) {
            $this->setId($this->id);
        }
    }

    public static function createTable()
    {
        $db = static::getDBInstance();
        $db->dbSchemaModify(
            "CREATE TABLE IF NOT EXISTS " . static::TABLE_NAME . " (
                `id` bigint(20) unsigned NOT NULL DEFAULT uuid_short(),
                `name` varchar(255) NOT NULL,
                `username` varchar(64) NOT NULL,
                `password` varchar(64) NULL,
                `email` varchar(255) NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE(`email`),
                    UNIQUE(`username`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        );
    }

    /**
     * Setter Function for the username:
     * @param $username
     * @return $this
     */
    public function setUsername($username)
    {
        if ($username && is_string($username)) {
            if (Validator::isValidUsername($username)) {
                $this->username = $username;
            } else {
                $this->errors[] = "Invalid characters found in username";
            }
        } else {
            $this->errors[] = "Invalid user name";
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }


    /**
     * Setter Function for the Email:
     * @param $username
     * @return $this
     */
    public function setEmail($email)
    {
        if ($email && is_string($email)) {
            if (Validator::isValidEmail($email)) {
                $this->email = $email;
            } else {
                $this->errors[] = "Invalid Email";
            }
        } else {
            $this->errors[] = "Invalid Email";
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Setter Function for the username:
     * @param $name
     * @return $this
     */
    public function setName($name)
    {

        if (Validator::isValidName($name)) {
            $this->name = $name;
        } else {
            $this->errors[] = "Invalid Name";
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    //Setter & Getter of Password:
    public function setPassword($password)
    {
        if ($password) {
            if (strlen($password) < Config::MIN_PASSWORD_LENGTH) {
                $this->errors[] = "Password must be at least " . Config::MIN_PASSWORD_LENGTH . " characters";
            } else {
                // Hash the password with Bcrypt:
                $options = [
                    "cost" => 12
                ];
                $hash = password_hash($password, PASSWORD_BCRYPT, $options);
                $this->password = $hash;
            }
        } else {
            $this->errors[] = "Password can not be empty";
        }
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public static function exists($username)
    {
        if (static::findByUsername($username)) {
            return true;
        }
        return false;
    }

    /**
     * Authenticate a user by username and password
     * @param $username
     * @param $password
     * @return User
     */
    public static function authenticate($username, $password)
    {
        $user = static::findByUsername($username);

        /** @var User $user */
        if ($user) {
            if ($user->verifyPassword($password)) {
                return $user;
            }
        }
    }

    public function verifyPassword($input_password)
    {
        if (!$input_password || !is_string($input_password)) {
            return false;
        }
        if (!$this->password) {
            return false;
        }
        return password_verify($input_password, $this->password);
    }

    /**
     * Add the user entry to DB
     * @return bool|int
     */
    public function save()
    {
        $db = static::getDBInstance();

        $query = "insert into " . self::TABLE_NAME
            . " (name, username, password, email) "
            . " values(:name, :username, :password, :email);";
        $updated = $db->modify(
            $query,
            array(
                "name" => $this->name,
                "username" => $this->username,
                "password" => $this->password, //can be null also (for email based user)
                "email" => $this->email, // can be null also (for superadmin)
            )
        );

        if ($updated > 0) {
            $user_db_id = User::findIdByUsername($this->username);
            if ($user_db_id) {
                $this->id = $user_db_id;
                return true;
            }
        }
        return false;
    }


    /**
     * Get User object with username
     * @param $username
     * @return User
     */
    public static function findByUsername($username)
    {
        $db = static::getDBInstance();
        $query = "select * from " . static::TABLE_NAME . " where username=:username;";
        $user = $db->fetchObject(get_called_class(), $query, array("username" => $username));
        if ($user and $user instanceof User) {
            $user->initRoles();
            return $user;
        }
        return null;
    }


    /**
     * Get User object with email
     * @param $email
     * @return User
     */
    public static function findByEmail(string $email)
    {
        if (!$email) {
            return null;
        }

        //Don't check isvalidemail here, use the controller
        // for example, this function can be called when checking
        //      if email exists or not then add user entry based on that

        $db = static::getDBInstance();
        $query = "select * from " . static::TABLE_NAME . " where email=:email;";
        $user = $db->fetchObject(get_called_class(), $query, array("email" => $email));
        if ($user and $user instanceof User) {
            $user->initRoles();
            return $user;
        }
        return null;
    }


    /**
     * Get id by username
     * @param $username
     * @return void|null
     */
    public static function findIdByUsername($username)
    {
        $db = static::getDBInstance();
        $query = "select id from " . static::TABLE_NAME . " where username=:username;";
        $row = $db->fetchOne($query, array("username" => $username));
        if ($row) {
            $user_id = filter_var($row["id"], FILTER_VALIDATE_INT);
            if ($user_id !== false) {
                return $user_id;
            }
        }
        return null;
    }

    /**
     * @param $id
     * @return User
     */
    public static function findById($id)
    {
        $user = parent::findById($id);
        if ($user and $user instanceof User) {
            $user->initRoles();
            return $user;
        }
        return null;
    }


    /**
     * @param $id
     * @return string
     */
    public static function findUsernameById($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        $db = static::getDBInstance();
        $query = "select username from " . static::TABLE_NAME . " where id=:id;";
        $row = $db->fetchOne($query, array("id" => $id));
        if ($row) {
            return $row["username"];
        }
        return null;
    }

    /**
     * Get roles associated with the user in Human readable format
     * @return array
     */
    public function getRolesDescription()
    {
        if (!$this->roles) {
            // If role is not loaded, get associated roles from DB
            $this->initRoles();
        }

        $roles_desc = array();
        if ($this->roles) {
            /** @var Role $role */
            foreach ($this->roles as $keyword => $role) {
                $role->keyword = $keyword;
                $roles_desc[] = $role->getDescription();
            }
        }
        return $roles_desc;
    }

    /**
     * load roles associated with the user
     */
    protected function initRoles()
    {
        $this->roles = array();
        $db = static::getDBInstance();
        $query = "select user_role.role_id, roles.keyword from user_role
                join roles on user_role.role_id = roles.id
                where user_role.user_id = :user_id
            ";
        $rows = $db->fetch($query, array("user_id" => $this->id));
        if ($rows) {
            foreach ($rows as $row) {
                $role = new Role();
                $role->id = $row["role_id"];
                $role->initPermissions();
                $this->roles[$row["keyword"]] = $role;
            }
        }
    }

    /**
     * Check if user has specified privilege
     * @param $required_permission
     * @return bool
     */
    public function can($required_permission)
    {
        if ($this->hasRole('super_admin')) {
            // super admin can do anything
            return true;
        } else {
            $hasPermission = false;
            /** @var Role $role */
            if ($this->roles) {
                foreach ($this->roles as $role) {
                    if ($role->hasPermission($required_permission)) {
                        $hasPermission = true;
                        break;
                    }
                }
            }

            if ($hasPermission === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check user has the role
     * @param $role_name
     * @return bool
     */
    public function hasRole($role_name)
    {
        if (!$this->roles) {
            // If role is not loaded, get associated roles from DB
            $this->initRoles();
        }

        return isset($this->roles[$role_name]);
    }

    /**
     * Add new role to user
     * @param Role $role
     * @return bool
     */
    public function assignRole($role)
    {
        if ($role) {
            $db = DataModel::getDBInstance();
            $query = "insert into user_role(user_id, role_id) values(:user_id,:role_id)";
            return $db->modify($query, array("user_id" => $this->id, "role_id" => $role->id));
        }
        return false;
    }

    public function delete()
    {
        if ($this->id) {
            $db = static::getDBInstance();
            $query = "delete from user_role where user_id=:id";
            $db->modify($query, array("id" => $this->id));
            $query = "delete from users where id=:id";
            $updated = $db->modify($query, array("id" => $this->id));
            return $updated;
        }
    }

    public function updatePassword()
    {
        if ($this->id) {
            $db = static::getDBInstance();
            $query = "update users set password = :password where id= :id";
            $updated = $db->modify($query, array(
                "password" => $this->password,
                "id" => $this->id
            ));
            return $updated;
        }
    }

    public function updateName(string $name): bool{
        if ($this->updateProperty("name", $name)) {
            $this->name = $name;
            return true;
        }
        
        return false;
    }
    
    public static function getNameFromId($id_val)
    {
        static $id_name_map = array();
        $id_val = filter_var($id_val, FILTER_VALIDATE_INT);

        if ($id_val === false) {
            return null;
        }

        $db = static::getDBInstance();

        $query = "select id,name from " . static::TABLE_NAME . " where id=:id";

        if (!array_key_exists($id_val, $id_name_map)) {
            $row = $db->fetchOne($query, array("id" => $id_val));
            if ($row) {
                $id_name_map[$row["id"]] = $row["name"];
            }
        }

        if (array_key_exists($id_val, $id_name_map)) {
            return $id_name_map[$id_val];
        }

        return null;
    }
}
