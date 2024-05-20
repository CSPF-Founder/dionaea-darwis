<?php

/**
 * Copyright (c) 2019 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

namespace App\Controllers;

use App\Auth;
use App\Models\Setup;
use Core\Controller;
use Core\Flash;
use Core\View;
use Exception;

class CommonController extends Controller
{
    //Properties
    protected $login_url;
    protected $header_view;
    protected $footer_view;


    public function __construct($route_params)
    {
        parent::__construct($route_params);
        $this->login_url = '/welcome';
        $this->header_view = 'Common/header.php';
        $this->footer_view = 'Common/footer.php';
    }

    /**
     * Function to automatically include Header & Footer in  panel
     * @param $view
     * @param array $data
     * @throws Exception
     */
    public function masterView($view, $data = []): void
    {
        $flash_messages = Flash::getMessages();

        //Header:
        View::render($this->header_view, [
            'flash_messages' => $flash_messages
        ]);

        //Page view
        View::render($view, $data);

        //Footer:
        View::render($this->footer_view);
    }

    /**
     * @throws Exception
     */
    public function welcomeNonFilteredAction(): void
    {
        if (!Setup::isDatabaseAlreadyConfigured()) {
            Controller::redirect("/setup/index");
            exit;
        }

        // $this->masterView('Common/welcome.php');
        if (Auth::user()) {
            Controller::redirect("/user/dashboard");
            exit;
        }
        Controller::redirect("/user/login");
        exit;
    }

    /**
     * @throws Exception
     */
    protected function privacyNonFilteredAction(): void
    {
        //TODO: Privacy page
        // $this->masterView('Common/privacy.php');
    }

    /**
     * @throws Exception
     */
    protected function tocNonFilteredAction(): void
    {
        //TODO: Serve TOC
        // $this->masterView('Common/toc.php');
    }

    protected function successMessageNonFilteredAction(): void
    {
        View::render("Common/success-message.php");
    }
}
