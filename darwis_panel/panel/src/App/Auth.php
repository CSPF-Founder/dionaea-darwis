<?php

/**
 * Copyright (c) 2017 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

namespace App;

use App\Models\User;
use Core\AppError;
use Core\AppSession;
use Core\AuthController;
use Core\Controller;
use Core\View;

/**
 * Authentication class
 * Class Auth
 * @package App
 */
class Auth
{
    protected static $current_user;

    /**
     * Get user id from session & get the details from the database
     * @return User
     */
    public static function user()
    {
        if (!isset(static::$current_user) && isset($_SESSION['user_id'])) {
            static::$current_user = User::findById($_SESSION['user_id']);
        }
        return static::$current_user;
    }


    /**
     * Throw error if the current user does not have permission
     * @param String $required_permission
     * @return bool
     * @throws AppError
     */
    public static function throwErrorIfPermissionDenied(string $required_permission)
    {
        if (Auth::user() && Auth::user()->can($required_permission)) {
            return true;
        }
        View::throwAppError("You are not authorized", 403);
    }
    /**
     * Throw error if the current user does not have permission
     * @param String $required_permission
     * @return bool
     * @throws AppError
     */
    public static function throwIfRoleMismatch(string $required_role)
    {
        if (Auth::user() && Auth::user()->hasRole($required_role)) {
            return true;
        }
        View::throwAppError("You are not authorized", 403);
    }

    /**
     * Login the user
     * Generate session & set session variables
     */
    public static function login()
    {
        AppSession::regenerate();
        $_SESSION['user_id'] = static::user()->getId();
    }

    public static function attempt($username, $password)
    {
        $user = User::authenticate($username, $password);
        if ($user && $user instanceof User) {
            static::$current_user = $user;
            static::login();
            return true;
        }
        return false;
    }

    public static function emailLogin(User $user)
    {
        if ($user && $user instanceof User) {
            static::$current_user = $user;
            static::login();
            return true;
        }
        return false;
    }

    /**
     * Logout the user
     */
    public static function logout()
    {
        // Unset all of the session variables.
        $_SESSION = array();

        // Finally, destroy the session.
        session_destroy();
    }


    /**
     * Remember the originally requested page in the session
     */
    public static function rememberRequestedPage()
    {
        $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
    }

    /**
     * Get the originally requested page or home page
     */
    public static function getReturnPage()
    {
        if (isset($_SESSION['return_to'])) {
            return $_SESSION['return_to'];
        }
    }
}
