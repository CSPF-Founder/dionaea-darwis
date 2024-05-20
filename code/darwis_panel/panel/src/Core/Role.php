<?php

/**
 * Copyright (c) 2017 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

namespace Core;

class Role
{
    protected $permissions;
    #properties
    public $keyword;
    public $id;

    public function __construct()
    {
        $this->permissions = array();
    }


    /**
     * Get default roles
     * @return array
     */
    public static function getDefaultList()
    {
        return array(
            "super_admin",
            "customer",
            "software_organization",
            "law_firm_organization",
            "other_organization"
        );
    }

    /**
     * Human readable text
     * @return string
     */
    public function getDescription()
    {
        return ucwords(str_replace('_', ' ', $this->keyword));
    }

    /**
     * Get permissions array
     * @return array
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * Get object using keyword
     * @param $keyword
     * @return $this
     */
    public static function findByKeyword($keyword)
    {
        $db = DataModel::getDBInstance();
        $query = "select * from roles where keyword=:keyword;";
        return $db->fetchObject(get_called_class(), $query, array("keyword" => $keyword));
    }

    /**
     * load permissions of the role from database
     */
    public function initPermissions()
    {
        $db = DataModel::getDBInstance();
        $query = "select permissions.keyword from role_permission 
                  join permissions on role_permission.permission_id = permissions.id
                  where role_permission.role_id = :role_id
                  ";
        $rows = $db->fetch($query, array("role_id" => $this->id));
        if ($rows) {
            foreach ($rows as $row) {
                $this->permissions[$row["keyword"]] = true;
            }
        }
    }

    /**
     * Check the role has the permission
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        return isset($this->permissions[$permission]);
    }

    /**
     * add new role
     * @param $role_key
     * @return Role
     */
    public static function addNew($role_key)
    {
        if (!static::findBykeyword($role_key)) {
            $db = DataModel::getDBInstance();
            $query = "INSERT INTO roles(keyword) VALUES (:keyword)";
            if ($db->modify($query, array("keyword" => $role_key))) {
                $role = new Role();
                $role->keyword = $role_key;
                $role->id = $db->getLastInsertid();
                return $role;
            }
        }
        return null;
    }

    /**
     * Add list of roles
     * @param $roles_list
     */
    public static function addList($roles_list)
    {
        if ($roles_list) {
            foreach ($roles_list as $role_key) {
                static::addNew($role_key);
            }
        }
    }

    /**
     * Add default entries to the database
     */
    public static function setupDefault()
    {
        static::addList(static::getDefaultList());

        /** @var Role $role */
        $customer_role = Role::findBykeyword("customer");
        if ($customer_role) {
            $allowed_permissions = array(
                "customer_basic_actions"
            );
            $permission_ids = array();
            foreach ($allowed_permissions as $permission_keyword) {
                $permission = Permission::findBykeyword($permission_keyword);
                if ($permission) {
                    $permission_ids[] = $permission->id;
                }
            }
            $customer_role->syncPermissions($permission_ids);
        }

        /** @var Role $role */
        $software_organization_role = Role::findBykeyword("software_organization");
        if ($software_organization_role) {
            $allowed_permissions = array(
                "org_basic_actions"
            );
            $permission_ids = array();
            foreach ($allowed_permissions as $permission_keyword) {
                $permission = Permission::findBykeyword($permission_keyword);
                if ($permission) {
                    $permission_ids[] = $permission->id;
                }
            }
            $software_organization_role->syncPermissions($permission_ids);
        }

        /** @var Role $role */
        $law_firm_organization_role = Role::findBykeyword("law_firm_organization");
        if ($law_firm_organization_role) {
            $allowed_permissions = array(
                "org_basic_actions",
                "upload_for_others"
            );
            $permission_ids = array();
            foreach ($allowed_permissions as $permission_keyword) {
                $permission = Permission::findBykeyword($permission_keyword);
                if ($permission) {
                    $permission_ids[] = $permission->id;
                }
            }
            $law_firm_organization_role->syncPermissions($permission_ids);
        }

        /** @var Role $role */
        $other_organization_role = Role::findBykeyword("other_organization");
        if ($other_organization_role) {
            $allowed_permissions = array(
                "org_basic_actions",
                "upload_for_others"
            );
            $permission_ids = array();
            foreach ($allowed_permissions as $permission_keyword) {
                $permission = Permission::findBykeyword($permission_keyword);
                if ($permission) {
                    $permission_ids[] = $permission->id;
                }
            }
            $other_organization_role->syncPermissions($permission_ids);
        }
    }


    /**
     * Delete the roles and all associations
     * @param $roles
     */
    public static function deleteRoles($roles)
    {
        $db = DataModel::getDBInstance();
        $query = "delete t1,t2,t3 from roles as t1
                  join user_role as t2 on t1.id = t2.role_id
                  join role_permission as t3  on t1.role_id = t3.role_id
                  where t1.role_id = :role_id
        ";
        foreach ($roles as $role_id) {
            $db->modify($query, array("role_id" => $role_id));
        }
    }

    /**
     * Remove all roles of a user
     * @param $user_id
     * @return bool
     */
    public static function emptyUserRoles($user_id)
    {
        $db = DataModel::getDBInstance();
        $query = "delete from user_role where user_id = :user_id";
        return $db->modify($query, array("user_id" => $user_id));
    }

    /**
     * Grant new permission to a role
     * @param $role_id
     * @param $permission_id
     * @return bool
     */
    public static function grantRolePermission($role_id, $permission_id)
    {
        $db = DataModel::getDBInstance();
        $query = "insert into role_permission(role_id, permission_id) values(:role_id, :permission_id)";
        return $db->modify($query, array("role_id" => $role_id, "permission_id" => $permission_id));
    }

    /**
     * Grant Permission
     * @param $permission_id
     * @return bool
     */
    public function grantPermission($permission_id)
    {
        $db = DataModel::getDBInstance();
        $query = "insert into role_permission(role_id, permission_id) values(:role_id, :permission_id)";
        return $db->modify($query, array("role_id" => $this->id, "permission_id" => $permission_id));
    }

    /**
     * delete all records in the role_permissions table
     * @return bool
     */
    public static function truncateRolePermissions()
    {
        $db = DataModel::getDBInstance();
        $query = "TRUNCATE role_permissions";
        return $db->modify($query);
    }

    public static function createTables()
    {
        $db = DataModel::getDBInstance();
        $db->dbSchemaModify("CREATE TABLE IF NOT EXISTS roles (
                  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `keyword` varchar(64) NOT NULL,
                  PRIMARY KEY (`id`) 
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $db->dbSchemaModify("CREATE TABLE role_permission (
                        role_id int(11) UNSIGNED NOT NULL,
                        permission_id int(11) UNSIGNED NOT NULL,
                        FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
                        FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $db->dbSchemaModify("CREATE TABLE user_role (
                        user_id bigint(20) UNSIGNED NOT NULL,
                        role_id int(11) UNSIGNED NOT NULL,
                        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                        FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $db->dbSchemaModify("CREATE TABLE user_permission (
                        user_id bigint(20) UNSIGNED NOT NULL,
                        permission_id int(11) UNSIGNED NOT NULL,
                        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                        FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    /**
     * return all roles
     */
    public static function all($load_permissions = false)
    {
        $db = DataModel::getDBInstance();
        $query = "select * from roles where keyword<>'super_admin'";
        $objects = $db->fetchObjectList(get_called_class(), $query);
        if ($objects) {
            if ($load_permissions) {
                $new_array = array();
                /** @var Role $role */
                foreach ($objects as $role) {
                    $role->initPermissions();
                    $new_array[] = $role;
                }
                return $new_array;
            } else {
                return $objects;
            }
        }
        return [];
    }

    /*
     * Synchronize the role permissions
     * Revoke any permissions which are not supplied
     */
    public function syncPermissions($permission_ids)
    {
        if ($permission_ids and is_array($permission_ids)) {
            $db = DataModel::getDBInstance();
            $query = "delete from role_permission where role_id=:role_id";
            $db->modify($query, array("role_id" => $this->id));
            foreach ($permission_ids as $permission_id) {
                $this->grantPermission($permission_id);
            }
        }
    }
}
