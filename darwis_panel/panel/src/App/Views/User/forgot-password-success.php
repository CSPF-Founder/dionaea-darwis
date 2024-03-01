<?php
use Core\View;
use App\Config;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?php View::securePrint($_ENV["PRODUCT_TITLE"]);?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="/img/favicon.png">

    <!-- Template CSS Files -->
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/css/skins/yellow.css" />

    <!-- Template JS Files -->
    <script src="/js/modernizr.js"></script>

</head>

<body class="double-diagonal dark error-page">
<!-- Preloader Starts -->
<div class="preloader" id="preloader">
    <div class="logopreloader">
        <img src="/img/logo.png" alt="logo">
    </div>
    <div class="loader" id="loader"></div>
</div>
<!-- Preloader Ends -->
<!-- Page Wrapper Starts -->
<div class="wrapper">
    <div class="container-fluid error">
        <div>
            <div class="text-center">
                <!-- Logo Starts -->
                <a class="logo" href="/welcome">
                    <img class="img-responsive" src="/img/logo.png" alt="logo">
                </a>
                <!-- Logo Ends -->
                <!-- Error 404 Content Starts -->
                <div class="fs-100-pb-30"></div>
                <h2>Password reset successful</h2>
                <h4>You can now login to your account.</h4>
                <a class="custom-button" href="/user/login">Login</a>
                <!-- Error 404 Content Starts -->
            </div>
        </div>
    </div>
</div>
<!-- Wrapper Ends -->

<!-- Template JS Files -->
<script src="/js/jquery-2.2.4.min.js"></script>
<script src="/js/plugins/jquery.easing.1.3.js"></script>
<script src="/js/plugins/bootstrap.min.js"></script>

<!-- Main JS Initialization File -->
<script src="/js/custom.js"></script>

</body>

</html>
