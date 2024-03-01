<?php

use App\Auth;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?php \Core\View::securePrint($_ENV["PRODUCT_TITLE"]); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="/img/favicon.png">

    <!-- Template CSS Files -->
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/magnific-popup.css" />
    <link rel="stylesheet" type="text/css" href="/css/style.css?v=1.0.4" />
    <link rel="stylesheet" type="text/css" href="/resources/css/custom.css?v=1.0.3" />
    <link rel="stylesheet" type="text/css" href="/css/skins/yellow.css" />

    <!-- Revolution Slider CSS Files -->
    <link rel="stylesheet" type="text/css" href="/js/plugins/revolution/css/settings.css" />
    <link rel="stylesheet" type="text/css" href="/js/plugins/revolution/css/layers.css" />
    <link rel="stylesheet" type="text/css" href="/js/plugins/revolution/css/navigation.css" />

    <!-- Template JS Files -->
    <script src="/js/modernizr.js"></script>

    <!-- Template JS Files -->
    <script src="/js/jquery-2.2.4.min.js"></script>
    <script src="/js/plugins/jquery.easing.1.3.js"></script>
    <script src="/js/plugins/bootstrap.min.js"></script>
    <script src="/js/plugins/jquery.bxslider.min.js"></script>
    <script src="/js/plugins/jquery.filterizr.js"></script>
    <script src="/js/plugins/jquery.magnific-popup.min.js"></script>

    <!-- Revolution Slider Main JS Files -->
    <script src="/js/plugins/revolution/js/jquery.themepunch.tools.min.js"></script>
    <script src="/js/plugins/revolution/js/jquery.themepunch.revolution.min.js"></script>

    <!-- Revolution Slider Extensions -->

    <script src="/js/plugins/revolution/js/extensions/revolution.extension.actions.min.js"></script>
    <script src="/js/plugins/revolution/js/extensions/revolution.extension.carousel.min.js"></script>
    <script src="/js/plugins/revolution/js/extensions/revolution.extension.kenburn.min.js"></script>
    <script src="/js/plugins/revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
    <script src="/js/plugins/revolution/js/extensions/revolution.extension.migration.min.js"></script>
    <script src="/js/plugins/revolution/js/extensions/revolution.extension.navigation.min.js"></script>
    <script src="/js/plugins/revolution/js/extensions/revolution.extension.parallax.min.js"></script>
    <script src="/js/plugins/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
    <script src="/js/plugins/revolution/js/extensions/revolution.extension.video.min.js"></script>

    <!-- Main JS Initialization File -->
    <script src="/js/custom.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var url = window.location;
            $('ul.nav a[href="' + url + '"]').parent().addClass('active');
            $('ul.nav a').filter(function() {
                return this.href == url;
            }).parent().addClass('active');

            $(window).scroll(function() {

                if ($(this).scrollTop() > 0) {
                    $('#main-logo').addClass("main-logo-80");
                } else {
                    $('#main-logo').removeClass("main-logo-80");
                }
            });

        });
    </script>
</head>

<body class="double-diagonal dark">
    <!-- Modal -->
    <div class="modal fade" id="app-msg-box" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
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
        <!-- Header Starts -->
        <header class="header">
            <div class="header-inner">
                <!-- Navbar Starts -->
                <nav class="navbar">
                    <!-- Logo Starts -->
                    <div class="logo" id="main-logo">
                        <a data-toggle="collapse" data-target=".navbar-collapse.show" class="navbar-brand" href="/welcome">
                            <!-- Logo White Starts -->
                            <img id="logo-light" class="logo-light" src="/img/logo.png" alt="logo-light" />
                            <!-- Logo White Ends -->
                            <!-- Logo Black Starts -->
                            <img id="logo-dark" class="logo-dark" src="/img/logo.png" alt="logo-dark" />
                            <!-- Logo Black Ends -->
                        </a>
                    </div>
                    <!-- Logo Ends -->
                    <!-- Toggle Icon for Mobile Starts -->
                    <button class="navbar-toggle navbar-toggler" type="button" data-toggle="collapse" data-target=".navbar-collapse" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span id="icon-toggler">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </button>
                    <!-- Toggle Icon for Mobile Ends -->
                    <div id="navbarSupportedContent" class="collapse navbar-collapse navbar-responsive-collapse">
                        <!-- Main Menu Starts -->
                        <ul class="nav navbar-nav" id="main-navigation">
                            <?php if (Auth::user() && Auth::user()->can('customer_basic_actions')) : ?>
                                <li><a href="/welcome">Home</a></li>
                                <li><a href="/user/dashboard"><i class="fa fa-home"></i> Dashboard</a></li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-building"></i> Company <i class="fa fa-angle-down icon-angle"></i>
                                    </a>
                                    <ul class="dropdown-menu l-40px" role="menu">
                                        <li><a href="/welcome#about"><i class="fa fa-info"></i> About Us</a></li>
                                        <li><a href="/contact"><i class="fa fa-envelope"></i> Contact Us</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-info"></i> Resources <i class="fa fa-angle-down icon-angle"></i>
                                    </a>
                                    <ul class="dropdown-menu l-40px" role="menu">
                                        <li><a href="/work/verify"><i class="fa fa-certificate"></i> Verify Work</a></li>
                                        <li><a href="/welcome#copyrightLaw"><i class="fa fa-legal"></i> Copyright Law</a></li>
                                        <li><a href="/faq"><i class="fa fa-question"></i> FAQs</a></li>
                                    </ul>
                                </li>
                                <li><a href="/support"><i class="fa fa-headphones"></i> Support</a></li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-user-circle"></i> Account <i class="fa fa-angle-down icon-angle"></i>
                                    </a>
                                    <ul class="dropdown-menu l-40px" role="menu">
                                        <li><a href="/user/change-password"><i class="fa fa-refresh"></i> Change Password</a></li>
                                        <li><a href="/user/logout"><i class="fa fa-sign-out"></i> Logout</a></li>
                                    </ul>
                                </li>
                            <?php else : ?>
                                <li><a href="/welcome"><i class="fa fa-home"></i> Home</a></li>

                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-building"></i> Company <i class="fa fa-angle-down icon-angle"></i>
                                    </a>
                                    <ul class="dropdown-menu l-40px" role="menu">
                                        <li><a href="/welcome#about"><i class="fa fa-info"></i> About Us</a></li>
                                        <li><a href="/contact"><i class="fa fa-envelope"></i> Contact Us</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-info"></i> Resources <i class="fa fa-angle-down icon-angle"></i>
                                    </a>
                                    <ul class="dropdown-menu l-40px" role="menu">
                                        <li><a href="/work/verify"><i class="fa fa-certificate"></i> Verify Work</a></li>
                                        <li><a href="/welcome#copyrightLaw"><i class="fa fa-legal"></i> Copyright Law</a></li>
                                        <li><a href="/faq"><i class="fa fa-question"></i> FAQs</a></li>
                                    </ul>
                                </li>
                                <li><a href="/support"><i class="fa fa-headphones"></i> Support</a></li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-user-circle"></i> Account <i class="fa fa-angle-down icon-angle"></i>
                                    </a>
                                    <ul class="dropdown-menu l-40px" role="menu">
                                        <li><a href="/user/register"><i class="fa fa-user-plus"></i> Register</a></li>
                                        <li><a href="/user/login"><i class="fa fa-user"></i> Login</a></li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <!-- Main Menu Ends -->
                    </div>
                    <!-- Navigation Menu Ends -->
                </nav>
                <!-- Navbar Ends -->
            </div>
        </header>
