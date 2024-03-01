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
    <title><?php View::securePrint($_ENV["PRODUCT_TITLE"]); ?> - Forgot Password</title>
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
                    <form action="forgot-password" id="forgot-password-form" method="POST" autocomplete="off">
                        <div class="card-group d-block d-md-flex row">
                            <div class="card text-white bg-primary col-md-7 p-4 mb-0">
                                <div class="card-body">
                                <h3><?php View::securePrint($_ENV["PRODUCT_TITLE"]); ?> - Forgot Password</h3>
                                    <p class=""></p>

                                    <div class="input-group mb-3"><span class="input-group-text">
                                            <svg class="icon">
                                                <use
                                                    xlink:href="/resources/new-theme/vendor/@coreui/icons/svg/free.svg#cil-user">
                                                </use>
                                            </svg></span>
                                        <input type="text" class="form-control" name="username" id="username"
                                            placeholder="Username Given when installing the OVA" required>
                                    </div>

                                    <div class="input-group mb-3"><span class="input-group-text">
                                            <svg class="icon">
                                                <use
                                                    xlink:href="/resources/new-theme/vendor/@coreui/icons/svg/free.svg#cil-lock-locked">
                                                </use>
                                            </svg></span>
                                        <input type="text" class="form-control" name="license_key" id="license_key"
                                            placeholder="License Key" required>
                                    </div>

                                    <div class="input-group mb-3"><span class="input-group-text">
                                            <svg class="icon">
                                                <use
                                                    xlink:href="/resources/new-theme/vendor/@coreui/icons/svg/free.svg#cil-lock-locked">
                                                </use>
                                            </svg></span>
                                        <input type="password" class="form-control" name="password" id="password"
                                            placeholder="New Password" required>
                                    </div>

                                    <p class=""></p>

                                    <div class="row">
                                        <div class="col-6">
                                            <button form="forgot-password-form" class="btn btn-secondary" type="submit"
                                                name="Submit" value="Submit" id="fogot-password-button">Submit</button>
                                        </div>
                                        <div class="col-6 text-end">

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group wrapper justify-content-center mb-2">
                                    <h5 class="text-warning">
                                        <?php if (isset($flash_messages) && $flash_messages) : ?>
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
                                        <img class="img-thumbnail img-center-100 border-0 center-block"
                                            src="/resources/images/secondary-logo.png">
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
    $(document).ready(function() {
        $(document).on("click", "#forgot-password-button", function(e) {
            e.preventDefault();

            // let confirmPassword = document.getElementById("confirm-password");

            // if ($('#password').val() !== $('#confirm-password').val()) {
            //     confirmPassword.setCustomValidity(
            //         "Passwords Don't Match. Please check the password confirmation once.");
            //     confirmPassword.reportValidity();
            //     return false;
            // }

            loadingBox();
            $.post("forgot-password", $("#forgot-password-form").serialize(),
                function(res) {
                    if (res.error) {
                        $("#error-messages").html('<h5 class="text-danger">' + res.error + "</h5>");
                    } else if (res.success) {
                        document.location = '/user/login';
                    }

                }, "json").fail(function(response) {
                $("#error-messages").html('Error occurred');
            }).always(function() {
                hideLoadingBox();
            });
        });


        $(document).on("click", ".view-password-button", function(e) {
            e.preventDefault();
            let parentInputGroup = $(this).closest('.input-group');
            let passwordInput = parentInputGroup.find(".password-input-box")[0];
            if (passwordInput.type === "password") {
                $(this).find('i').toggleClass('fa-eye fa-eye-slash');
                passwordInput.type = "text";
            } else {
                $(this).find('i').toggleClass('fa-eye-slash fa-eye');
                passwordInput.type = "password";
            }
        });

    });
    </script>
</body>

</html>
