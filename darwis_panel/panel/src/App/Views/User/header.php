<?php

/**
 * Copyright (c) 2019 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

use Core\Security\CSRF;
use Core\View;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php View::securePrint($_ENV["PRODUCT_TITLE"]); ?></title>
    <link rel="icon" type="image/x-icon" href="/resources/images/favicon.ico">

    <script src="/resources/vendor/other/js/popper.min.js"></script>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!-- <script src="/resources/vendor/jquery/js/jquery-3.3.1.min.js"></script> -->
    <script src="/resources/new-theme/vendor/jquery/js/jquery.min.js"></script>

    <script type="text/javascript" src="/resources/js/custom-app.js?v=1.0.1"></script>


    <!-- New theme files -->

    <link rel="stylesheet" href="/resources/new-theme/vendor/simplebar/css/simplebar.css">
    <link rel="stylesheet" href="/resources/new-theme/css/vendors/simplebar.css">
    <!-- Main styles for this application-->
    <link href="/resources/new-theme/css/style.css" rel="stylesheet">
    <link href="/resources/new-theme/vendor/@coreui/icons/css/free.min.css" rel="stylesheet">
    <link href="/resources/new-theme/css/custom.css" rel="stylesheet">

    <!-- We use those styles to show code examples, you should remove them in your application.-->
    <!-- <link href="/resources/new-theme/c/ss/examples.css" rel="stylesheet"> -->
    <link href="/resources/new-theme/vendor/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">

    <!-- font awesome icons -->
    <link href="/resources/vendor/fontawesome/6.2.0/css/fontawesome.css" rel="stylesheet">
    <link href="/resources/vendor/fontawesome/6.2.0/css/brands.css" rel="stylesheet">
    <link href="/resources/vendor/fontawesome/6.2.0/css/all.css" rel="stylesheet">


    <!-- Over -->
    <script type="text/javascript">
        $(document).ready(function() {
            // $(".flash-alert-message").prependTo("#content");

            //Auto include CSRF token
            $('form').append('<?php CSRF::addInputField(); ?>');
            $.ajaxPrefilter(function(options, originalOptions, jqXHR) {
                if (typeof(options.data) != 'object' &&
                    options.type.toUpperCase() === "POST" &&
                    options.data.indexOf("<?php View::securePrint(CSRF::TOKEN_NAME) ?>") < 0) {
                    // initialize `data` to empty string if it does not exist
                    options.data = options.data || "";
                    // add leading ampersand if `data` is non-empty
                    options.data += options.data ? "&" : "";
                    options.data +=
                        "<?php View::securePrint(CSRF::TOKEN_NAME) ?>=<?php View::securePrint(CSRF::get()) ?>";
                }
            });
        });
    </script>

    <script>
        let csrf_token = "<?php View::securePrint(CSRF::get()); ?>";
        let csrf_token_name = "<?php View::securePrint(CSRF::TOKEN_NAME); ?>";
        let baseAjaxData = {
            [csrf_token_name]: csrf_token
        };
    </script>
    <style>
        /** tooltip **/

        .sub-tooltip {
            position: relative;
            display: inline-block;
            border-bottom: 1px black;
        }

        .sub-tooltip .sub-tooltiptext {
            visibility: hidden;
            width: 400px;
            background-color: black;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px 0;
            position: absolute;
            z-index: 1;
            left: 100%;
        }

        .sub-tooltip .sub-tooltiptext::after {
            content: "";
            position: absolute;
            top: 50%;
            right: 100%;
            margin-top: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: transparent black transparent transparent;
        }

        .sub-tooltip:hover .sub-tooltiptext {
            visibility: visible;
        }

        .w-30 {
            width: 26.5%;
        }

        @media (max-width: 767.98px) {
            .w-sm-50 {
                width: 50%;
            }
        }
    </style>
</head>

<body>

    <div class="sidebar sidebar-light sidebar-fixed" id="sidebar">
        <a href="/user/dashboard">
            <div class="sidebar-brand d-none d-md-flex" style="padding: 6.8px;">
                <img src="/resources/images/main-logo.png?v=1.01" class="sidebar-brand-full mx-auto " style="max-height: 50px;min-height: 50px;">

                <svg class="sidebar-brand-narrow text-white mx-auto" width="35" height="35" alt="Menu">
                    <use xlink:href='/resources/new-theme/vendor/@coreui/icons/svg/free.svg#cil-menu'></use>
                </svg>

                <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
            </div>
        </a>

        <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
            <li class=" nav-item">
                <a class="nav-link" href="/user/dashboard">
                    <svg class='nav-icon'>
                        <use xlink:href='/resources/new-theme/vendor/@coreui/icons/svg/free.svg#cil-home'></use>
                    </svg>
                    Home
                </a>
                <a class="nav-link" href="/malware/log/list">
                    <svg class='nav-icon'>
                        <use xlink:href='/resources/new-theme/vendor/@coreui/icons/svg/free.svg#cil-list'></use>
                    </svg>
                    View Logs
                </a>
                <a class="nav-link" href="/user/profile">
                    <svg class='nav-icon'>
                        <use xlink:href='/resources/new-theme/vendor/@coreui/icons/svg/free.svg#cil-user'></use>
                    </svg>
                    Profile
                </a>
                <a class="nav-link" href="/user/logout">
                    <svg class='nav-icon'>
                        <use xlink:href='/resources/new-theme/vendor/@coreui/icons/svg/free.svg#cil-account-logout'>
                        </use>
                    </svg>
                    Logout
                </a>
            </li>
        </ul>
    </div>
    <div class="wrapper d-flex flex-column min-vh-100 bg-light bg-opacity-50 dark:bg-transparent">
        <header class="header header-light bg-primary header-sticky mb-4">
            <div class="container-fluid">
                <h4 class="mx-auto text-white"><?php View::securePrint($_ENV["PRODUCT_TITLE"]); ?></h4>
                <button class="header-toggler px-md-0 me-md-3 d-md-none" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
                    <svg class="icon icon-lg">
                        <use xlink:href="/resources/new-theme/vendor/@coreui/icons/svg/free.svg#cil-menu"></use>
                    </svg>
                </button>
                <form class="d-flex w-sm-50" role="search">
                    <!-- <div class="input-group"><span class="input-group-text bg-light border-0 px-1" id="search-addon">
                            <svg class="icon icon-lg my-1 mx-2 text-disabled">
                                <use xlink:href="/resources/new-theme/vendor/@coreui/icons/svg/free.svg#cil-search"></use>
                            </svg></span>
                        <input class="form-control bg-light border-0" type="text" placeholder="Search..." aria-label="Search" aria-describedby="search-addon">
                    </div> -->
                </form>



                <ul class="header-nav me-4">
                    <li class="nav-item dropdown d-flex align-items-center">
                        <a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <div class="avatar avatar-md"> <svg class=" avatar-img icon-xxl icon me-2">
                                    <use xlink:href="/resources/new-theme/vendor/@coreui/icons/svg/free.svg#cil-user">
                                    </use>
                                </svg><span class="avatar-status bg-success"></span></div>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end pt-0">

                            <!-- <a class="dropdown-item" href="/user/edit-personal-details">
                                <svg class="icon me-2">
                                    <use xlink:href="/resources/new-theme/vendor/@coreui/icons/svg/free.svg#cil-settings"></use>
                                </svg> My Profile
                            </a> -->

                            <a class="dropdown-item" href="/user/logout">
                                <svg class="icon me-2">
                                    <use xlink:href="/resources/new-theme/vendor/@coreui/icons/svg/free.svg#cil-account-logout">
                                    </use>
                                </svg> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </header>

        <!-- App Message Box -->
        <div id="app-msg-box" class="modal mt-5 pt-5" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p></p>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>

        <div id="content" class="body flex-grow-1 px-3">
            <?php if (isset($flash_messages)) : ?>
                <?php foreach ($flash_messages as $flash) : ?>
                    <div class="flash-alert-message alert alert-<?php View::securePrint($flash->type); ?> <?php if ($flash->closable) : ?> alert-dismissible <?php endif; ?>">
                        <?php if ($flash->closable) : ?>
                            <button class="btn-close" type="button" data-coreui-dismiss="alert" aria-label="Close"></button>
                        <?php endif; ?>
                        <?php View::securePrint($flash->message); ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>


            <script type="text/javascript">
                $(document).ready(function() {
                    var url = window.location;
                    $('#sidebar a[href="' + url + '"]').parent().addClass('active');
                    $('#sidebar a').filter(function() {
                        return this.href == url;
                    }).parent().addClass('active');
                });
            </script>
