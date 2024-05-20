<?php

/**
 * Copyright (c) 2017 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

namespace App\Controllers;

use App\Auth;
use App\Config;
use App\Helpers\Captcha;
use App\Helpers\LicenseApi;
use App\Helpers\LicenseHelper;
use App\Models\User;
use Core\AppError;
use Core\AppSession;
use Core\AuthController;
use Core\Flash;
use Core\Security\Validator;
use Core\View;
use Exception;

class UserController extends AuthController
{
    protected $login_url;
    protected $login_view;
    protected $login_success_page;

    public function __construct($route_params)
    {
        parent::__construct($route_params);
        $this->login_url = '/user/login';
        $this->login_view = 'User/login.php';
        $this->login_success_page = '/user/home';
    }

    protected function successAction(): void
    {
        View::render("User/success.php");
    }

    protected function loginNonFilteredAction(): void
    {
        if (!LicenseHelper::isSetupDone()) {
            static::redirect('/user-setup/license');
        } else {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                View::render('User/login.php');
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $required_params = array('username', 'password');
                if (!Validator::checkAllParamsExists($required_params)) {
                    $this->__displayLoginError(array('Please fill all the fields'));
                    exit;
                }

                if (!Captcha::validate()) {
                    $this->__displayLoginError(array('Invalid CAPTCHA'));
                    exit;
                }

                $username = trim($_POST["username"]);
                $password = trim($_POST["password"]);

                /** @var User $user */
                $user = User::authenticate($username, $password);

                if ($user && $user instanceof User) {
                    // $customer_name = "Customer";
                    // try {
                    //     $validationOutput = LicenseApi::validateLicense();
                    //     if (!$validationOutput || !isset($validationOutput["name"]) || empty($validationOutput["name"])) {
                    //         $this->__displayLoginError(array('License is invalid'));
                    //     }
                    //     $customer_name = $validationOutput["name"];
                    // } catch (AppError $e) {
                    //     $this->__displayLoginError([$e->getMessage()]);
                    // }
                    AppSession::regenerate();
                    $_SESSION['user_id'] = $user->getId();

                    // $user->updateName($customer_name);

                    // Flash::addMessage("Welcome, " . $customer_name, Flash::SUCCESS);
                    static::redirect('/user/dashboard');
                } else {
                    $this->__displayLoginError(array('Invalid username/password'));
                }
            }
        }
    }

    /**
     * Error Page
     * @param $error_messages
     * @throws Exception
     */
    private function __displayLoginError($error_messages): void
    {
        Flash::addMessageListAndGoBack($error_messages, Flash::DANGER);
        // View::render('User/login.php', [
        //     "error_messages" => $error_messages
        // ]);
        exit;
    }

    /**
     * Display Dashboard
     * @throws Exception
     */
    protected function dashboardAction(): void
    {
        Auth::throwErrorIfPermissionDenied("customer_basic_actions");

        if (Auth::user()->hasRole('super_admin')) {
            if (Config::debugEnabled()) {
                Flash::addMessage("Debug Mode is enabled(Disable it in Live Environment)", Flash::DANGER);
            }
            if (Config::captchaDisabled()) {
                Flash::addMessage("CAPTCHA Checking is disabled(Enable it in Live Environment)", Flash::DANGER);
            }
            $this->masterView('SuperAdmin/dashboard.php');
        } else {
            $this->redirect("/malware/dashboard/view");
        }
    }

    protected function profileAction(): void
    {
        $this->masterView("User/profile.php");
    }

    /**
     * Log out
     * Deletes session & redirects to the login page
     * Note: This function doesn't check whether the user is logged in or not
     */
    protected function logoutNonFilteredAction(): void
    {
        Auth::logout();
        static::redirect($this->login_url);
    }

    protected function forgotPasswordNonFilteredAction(): void
    {

        if (!LicenseHelper::isSetupDone()) {
            static::redirect('/user-setup/license');
        } else {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                View::render('User/forgot-password.php');
            }

            $requiredParams = array("username", "password", "license_key");
            if (!Validator::checkAllParamsExists($requiredParams)) {
                Flash::addAndGoBack("Please fill all the inputs", Flash::WARNING);
            }

            $username = trim($_POST["username"]);
            $password = trim($_POST["password"]);
            $license_key = trim($_POST["license_key"]);

            $user = User::findByUsername($username);
            if (!$user) {
                Flash::addAndGoBack("Invalid username", Flash::WARNING);
            }

            if (!LicenseHelper::localLicenseKeyMatch($license_key)) {
                Flash::addAndGoBack("Invalid License Key", Flash::WARNING);
            }

            if ($user->verifyPassword($password)) {
                Flash::addAndGoBack("New password same as old password", Flash::WARNING);
            }

            $user->setPassword($password);
            if ($user->updatePassword()) {
                // View::displayJsonSuccess("Successfully changed the Password");
                Flash::addMessage("Successfully changed the Password", Flash::SUCCESS);
                static::redirect($this->login_url);
            } elseif ($user->getErrors()) {
                Flash::addMessageListAndGoBack($user->getErrors(), Flash::WARNING);
            } else {
                Flash::addAndGoBack("Unable to change the password", Flash::WARNING);
            }
        }
    }

    protected function agreementAction(): void
    {
        $this->masterView("User/agreement.php");
    }

    /**
     * @throws AppError
     */
    public static function downloadTacAction(): void
    {
        $tac_path = Config::PUBLIC_RESOURCES_DIR . "/documents/terms-and-conditions.pdf";
        header('Content-Type: application/pdf');
        header('Content-Disposition: filename="terms-and-conditions.pdf"');
        readfile($tac_path);
        exit;
    }
}
