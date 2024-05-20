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
    <title>Setup</title>
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
    <div class="bg-light min-vh-100 d-flex flex-row align-items-center dark:bg-transparent">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <form action="/setup/index" id="setup-form" method="POST" autocomplete="off">
                        <div class="card-group d-block d-md-flex row">
                            <div class="card text-white bg-primary col-md-7 p-4 mb-0">
                                <div class="card-body">
                                    <h1>Setup</h1>
                                    <p class="">Note: First, Create the database
                                        "<?php View::securePrint($_ENV["DB_NAME"]); ?>"
                                        (just database not tables)</p>

                                    <div class="row">
                                        <div class="col-6">
                                            <?php if (Captcha::isCaptchaDisabled()) : ?>
                                            <button form="setup-form" class="btn btn-secondary" type="submit"
                                                name="setup" value="Setup">Setup</button>
                                            <?php else : ?>
                                            <button form="setup-form" class="g-recaptcha btn btn-secondary"
                                                data-sitekey="<?php \Core\View::securePrint($_ENV['RECAPTCHA_SITE_KEY']); ?>"
                                                data-action='submit' data-callback='submitSetup' name="setup"
                                                value="Setup">Setup</button>
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
                            <div class="card col-md-5  py-5">
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
    function submitSetup(token) {
        if (!document.getElementById("login_id").value || !document.getElementById("password").value) {
            document.getElementById("error-message").innerHTML = "Invalid username/password";
            document.getElementById("error-box").style.display = 'block';
            return;
        }

        document.getElementById("setup-form").submit();

    }
    </script>
</body>

</html>