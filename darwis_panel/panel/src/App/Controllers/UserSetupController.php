<?php

/**
 * Copyright (c) 2023 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

namespace App\Controllers;

use App\Config;
use App\Helpers\LicenseHelper;
use App\Helpers\LicenseApi;
use App\Models\User;
use App\Models\AppConfig;
use Core\Role;
use Core\Controller;
use Core\Security\Validator;
use Core\View;
use Exception;

class UserSetupController extends Controller
{
    /**
     * Display license check form
     * @throws Exception
     */
    public function licenseNonFilteredAction(): void
    {
        if (LicenseHelper::isLicenseKeyExists()) {
            if (LicenseHelper::isUserExists()) {
                static::redirect("/user/login");
            } else {
                static::redirect("/user-setup/create-user");
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            View::render('UserSetup/license.php');
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $required_params = array("license_key");
            if (!Validator::checkAllParamsExists($required_params)) {
                View::render('UserSetup/license.php', ['error_message' => 'Invalid Request - Please enter valid license key']);
                exit;
            }

            $value = trim($_POST['license_key']);

            $response = LicenseApi::activateLicense($value);
            if (!$response) {
                View::render(
                    'UserSetup/license.php',
                    [
                        'error_message' => 'Unable to reach the license checking server. Please check network connection'
                    ]
                );
                exit;
            }

            if (
                isset($response['success'])
                && $response['success'] == "true"
                && isset($response['api_full_key'])
                && !empty($response['api_full_key'])
            ) {
                $full_key = $response['api_full_key'];

                // Save the license key
                $app_config = new AppConfig();
                $app_config->setName(Config::LICENSE_KEY_SETTING_NAME)
                    ->setValue($full_key);

                if ($app_config->getErrors()) {
                    View::render('UserSetup/license.php', [
                        'error_message' => implode("\n", $app_config->getErrors())
                    ]);
                    exit;
                }

                if ($app_config->save()) {

                    $templateFile = $_ENV["WEB_CONFIG_DIR"] . "fc_config.conf.template";
                    $fcContent = file_get_contents($templateFile);

                    $fcContent = str_replace("{{API_KEY}}", $full_key, $fcContent);
                    $fcContent = str_replace("{{MAIN_DOMAIN}}", $_ENV["MAIN_DOMAIN"], $fcContent);

                    $fileCheckerDir = $_ENV["FILE_CHECKER_DIR"];
                    $fileCheckerConfig = $fileCheckerDir . "config/app.conf";

                    $licenseWritten = file_put_contents($fileCheckerConfig, $fcContent);
                    if (!$licenseWritten) {
                        View::render('UserSetup/license.php', [
                            'error_message' => 'Unable to write license config file'
                        ]);
                        exit;
                    }

                    static::redirect("/user-setup/create-user");
                } else {
                    View::render('UserSetup/license.php', [
                        'error_message' => 'Unable to register the license key'
                    ]);
                    exit;
                }
            } elseif (isset($response['messages'])) {
                $error_message = "";
                if (is_array($response['messages'])) {
                    $error_message = implode("\n", $response['messages']);
                }
                View::render('UserSetup/license.php', ['error_message' => $error_message]);
                exit;
            } else {
                View::render('UserSetup/license.php', ['error_message' => 'Invalid license key']);
                exit;
            }
        }
    }


    /**
     * Add new user
     * @throws AppError
     */
    protected function createUserNonFilteredAction(): void
    {
        if (!LicenseHelper::isLicenseKeyExists()) {
            static::redirect("/user-setup/license");
            exit;
        }

        if (LicenseHelper::isUserExists()) {
            View::throwAppError("User is already created");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            View::render('UserSetup/create-user.php');
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $required_params = array("username", "password", "confirm_password", "email");
            if (!Validator::checkAllParamsExists($required_params)) {
                View::render('UserSetup/create-user.php', ['error_message' => 'Please fill all the inputs']);
                exit;
            }

            $name = "Customer";
            $username = trim($_POST["username"]);
            $password = trim($_POST["password"]);
            $confirm_password = trim($_POST["confirm_password"]);
            $email = trim($_POST["email"]);

            if ($password !== $confirm_password) {
                View::render('UserSetup/create-user.php', ['error_message' => 'Passwords do not match. Please check the password confirmation once']);
                exit;
            }

            if (User::exists($username)) {
                View::render('UserSetup/create-user.php', ['error_message' => 'Username Already Exists']);
                exit;
            }

            $user = User::getInstance()
                ->setName($name)
                ->setUsername($username)
                ->setEmail($email)
                ->setPassword($password);

            if ($user->getErrors()) {
                View::render('UserSetup/create-user.php', ['error_message' => implode("\n", $user->getErrors())]);
                exit;
            }

            $roles = ["customer"];

            if ($user->save()) {
                foreach ($roles as $role_to_assign) {
                    $role = Role::findByKeyword($role_to_assign);
                    if (!$role || !$user->assignRole($role)) {
                        $user->delete();
                        View::render('UserSetup/create-user.php', ['error_message' => 'There was a problem with your registration, please contact support']);
                        exit;
                    }
                }
                static::redirect('/user/login');
            } else {
                View::render('UserSetup/create-user.php', ['error_message' => 'Unable to register the user']);
                exit;
            }
        }
    }
}
