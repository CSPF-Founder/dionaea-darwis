<?php

use App\Helpers\Captcha;
use Core\View;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php View::securePrint($_ENV["PRODUCT_TITLE"]); ?> - Create User</title>
    <link rel="icon" type="image/x-icon" href="/resources/images/favicon.ico">

    <!-- Bootstrap -->
    <link href="/resources/vendor/bootstrap/css/bootstrap.css?v=4.1.1" rel="stylesheet">
    <link href="/resources/css/new-style.css?v=1.0.1" rel="stylesheet">
    <!-- New theme files -->
    <!-- Vendors styles-->
    <link rel="stylesheet" href="/resources/new-theme/vendor/simplebar/css/simplebar.css">
    <link rel="stylesheet" href="/resources/new-theme/css/vendor/simplebar.css">
    <!-- Main styles for this application-->
    <link href="/resources/new-theme/css/style.css" rel="stylesheet">
</head>

<body class=" ">

    <!-- new theme -->
    <div class="bg-light min-vh-100 d-flex flex-column align-items-center dark:bg-transparent">
    <div class="container d-flex flex-row flex-grow-1 align-items-center justify-content-center">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <form action="create-user" id="create-user-form" method="POST" autocomplete="off">
                        <div class="card-group d-block d-md-flex row">
                            <div class="card text-white bg-primary col-md-7 p-4 mb-0">
                                <div class="card-body">
                                    <h3><?php View::securePrint($_ENV["PRODUCT_TITLE"]); ?> - Create User</h3>
                                    <p class=""></p>


                                    <div class="input-group mb-3"><span class="input-group-text">
                                            <svg class="icon">
                                                <use xlink:href="/resources/new-theme/vendor/@coreui/icons/svg/free.svg#cil-user">
                                                </use>
                                            </svg></span>
                                        <input type="text" class="form-control" name="username" id="username" placeholder="Username" minlength="2" required>
                                    </div>

                                    <div class="input-group mb-3"><span class="input-group-text">
                                            <svg class="icon">
                                                <use xlink:href="/resources/new-theme/vendor/@coreui/icons/svg/free.svg#cil-lock-locked">
                                                </use>
                                            </svg></span>
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Password (Minimum length: 10 characters)" minlength="10" required>
                                    </div>

                                    <div class="input-group mb-3"><span class="input-group-text">
                                            <svg class="icon">
                                                <use xlink:href="/resources/new-theme/vendor/@coreui/icons/svg/free.svg#cil-lock-locked">
                                                </use>
                                            </svg></span>
                                        <input type="password" class="form-control" name="confirm_password" id="confirm-password" placeholder="Confirm Password" title="Retype the password" minlength="10" required>
                                    </div>

                                    <div class="input-group mb-3"><span class="input-group-text">
                                            <svg class="icon">
                                                <use xlink:href="/resources/new-theme/vendor/@coreui/icons/svg/free.svg#cil-envelope-open">
                                                </use>
                                            </svg></span>
                                        <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
                                    </div>

                                    <p class=""></p>

                                    <div class="row">
                                        <div class="col-6">
                                            <?php if (Captcha::isCaptchaDisabled()) : ?>
                                                <button form="create-user-form" class="btn btn-secondary" type="submit" name="register" value="Register">Create</button>
                                            <?php else : ?>
                                                <button form="create-user-form" class="g-recaptcha btn btn-secondary" data-sitekey="<?php \Core\View::securePrint($_ENV['RECAPTCHA_SITE_KEY']); ?>" data-action='submit' data-callback='submitRegister' name="register" value="Register">Create</button>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-6 text-end">

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group wrapper justify-content-center mb-2">
                                    <h5 class="text-warning">
                                        <?php if (isset($flash_messages) && $flash_messages) : ?>
                                            Error:<br />
                                            <?php foreach ($flash_messages as $flash) : ?>
                                                <?php View::securePrint($flash->message) ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <?php if (isset($error_message) && $error_message) : ?>
                                            <?php Core\View::securePrint($error_message) ?>
                                        <?php endif; ?>
                                    </h5>
                                </div>
                            </div>
                            <div class="card col-md-5 d-flex flex-row py-5 align-items-center justify-content-center">
                                <div class="card-body text-center">
                                    <div>
                                        <img class="img-thumbnail img-center-100 border-0 center-block" src="/resources/images/secondary-logo.png">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="mx-auto block display-block flex-row">
            <footer class="mx-auto page-footer font-small blue pt-4">
            <p class="text-center">&copy; <?php View::securePrint(date("Y")); ?> <?php View::securePrint($_ENV["COPYRIGHT_FOOTER_COMPANY"]); ?>. All Rights Reserved. </p>
            </footer>
        </div>
    </div>

    <script src="/resources/new-theme/vendor/@coreui/coreui-pro/js/coreui.bundle.min.js"></script>
    <script src="/resources/new-theme/vendor/simplebar/js/simplebar.min.js"></script>
    <script>
        if (document.body.classList.contains('dark-theme')) {
            var element = document.getElementById('btn-dark-theme');
            if (typeof(element) != 'undefined' && element != null) {
                document.getElementById('btn-dark-theme').checked = true;
            }
        } else {
            var element = document.getElementById('btn-light-theme');
            if (typeof(element) != 'undefined' && element != null) {
                document.getElementById('btn-light-theme').checked = true;
            }
        }

        function handleThemeChange(src) {
            var event = document.createEvent('Event');
            event.initEvent('themeChange', true, true);

            if (src.value === 'light') {
                document.body.classList.remove('dark-theme');
            }
            if (src.value === 'dark') {
                document.body.classList.add('dark-theme');
            }
            document.body.dispatchEvent(event);
        }
    </script>


    <script>
        function submitRegister(token) {
            if (!document.getElementById("login_id").value || !document.getElementById("password").value) {
                document.getElementById("error-message").innerHTML = "Invalid username/password";
                document.getElementById("error-box").style.display = 'block';
                return;
            }

            document.getElementById("create-user-form").submit();

        }
    </script>
</body>

</html>

</html>

</html>
