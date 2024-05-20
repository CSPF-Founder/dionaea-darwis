<?php

/**
 * Copyright (c) 2019 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

namespace App\Controllers;

use App\Auth;
use Core\AuthController;
use Core\Controller;

class HomeController extends AuthController
{
    /**
     * Home page
     * @throws \Exception
     */
    protected function indexAction(): void
    {
        // $this->masterView($this->home_page);
        if (Auth::user()) {
            Controller::redirect("/user/dashboard");
            exit;
        }
        Controller::redirect("/user/login");
        exit;
    }
}
