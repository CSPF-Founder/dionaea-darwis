<?php

/**
 * Copyright (c) 2017 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

namespace App\Controllers;

use App\Config;
use App\Models\Setup;
use Core\AppError;
use Core\Controller;
use Core\Flash;
use Core\View;

class SetupController extends Controller
{
    /**
     * SetupController constructor.
     * @param $route_params
     * @throws AppError
     */
    public function __construct($route_params)
    {
        parent::__construct($route_params);
        //If table exists already return false;
        try {
            if (Config::allowSetup()) {
                if (Setup::isDatabaseAlreadyConfigured()) {
                    View::throwAppError("Database is already configured, Please delete the db & create db(not the tables) to reconfigure", 403);
                }
            } else {
                View::throwAppError("Setup is disabled", 403);
            }
        } catch (\Core\AppError $e) {
            throw $e;
        } catch (\PDOException $e) {
            View::throwAppError("Database connection error");
        } catch (\Exception $e) {
            View::throwAppError("Setup error");
        }
    }

    /**
     * Default page
     * @throws \Exception
     */
    public function indexNonFilteredAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            View::render("Setup/index.php");
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (Setup::install()) {
                Flash::addMessage("Successfully finished base setup", Flash::SUCCESS);
                Controller::redirect("/user-setup/license");
            } else {
                $this->__displaySetupError(["Setup Unsuccessful"]);
            }
        }
    }

    /**
     * Error Page
     * @param array $error_messages
     */
    public function __displaySetupError(array $error_messages): void
    {
        Flash::addMessageListAndGoBack($error_messages, Flash::WARNING);
        exit;
    }
}
