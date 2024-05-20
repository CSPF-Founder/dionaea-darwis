<?php

/**
 * Copyright (c) 2017 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

/*
 * Front Controller
 */

use App\Config;

/**
 * PHP Composer autoload
 */
require '../vendor/autoload.php';

/**
 * Load config from environment variable:
 */
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__) . '/../config/', "web_config.env");
$dotenv->load();
$_ENV['WEB_CONFIG_DIR'] = dirname(__DIR__) . '/../config/';

/**
 * Error & Exception Handlers
 */
if (\App\Config::debugEnabled()) {
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Set Timezone
 */
if(isset($_SESSION['tz'])) {
    date_default_timezone_set($_SESSION['tz']);
} else {
    date_default_timezone_set(Config::TIME_ZONE);
}


/**
 * Sessions
 */
\Core\AppSession::start('/');
\Core\Utils::setSecurityHeaders();

//Routing
$router = new \Core\Router();
//Add the Routes
/**
 * Note: Don't change the order of routing table (otherwise, fixed paths will return 404 not error
 */
//static paths:
$router->add('', ['controller' => 'home', 'action' => 'index']);  // Home path
$router->add('{action}', ['controller' => 'common', 'action' => 'welcome']);  // common controller & action

//Only controller specified, set action to -> index; example: /admin/ => Admin->index()
$router->add('{controller:[a-z-]+}/', ['action' => 'index']);

//Other Routes:
$router->add('{namespace}/{controller}/{action}');

$router->add('{controller}/{id:\d+}/{action}');
$router->add('{namespace}/{controller}/{id:\d+}/{action}');
$router->add('{namespace}/{id:\d+}/{controller}/{action}');

//Wild card controller, matches /controller/action pattern
$router->add('{controller}/{action}');

//Match the requested route
$url = $_SERVER['QUERY_STRING'];

$router->dispatch($url);
