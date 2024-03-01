<?php

use Core\View;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>404 - File or directory not found.</title>
    <link rel="stylesheet" href="/resources/new-theme/vendor/simplebar/css/simplebar.css">
    <link rel="stylesheet" href="/resources/new-theme/css/vendors/simplebar.css">
    <!-- Main styles for this application-->
    <link href="/resources/new-theme/css/style.css" rel="stylesheet">
</head>

<body>

    <div id="content">

        <div class="bg-light min-vh-100 d-flex flex-row align-items-center dark:bg-transparent">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="clearfix">
                            <h1 class="display-3 me-4">403 - Access Denied</h1><br />
                            <h4 class="pt-4"> <?php if (isset($error_message) && $error_message) : ?>
                                    <p>Message : <?php \Core\View::securePrint($error_message); ?></p>
                                <?php else : ?>
                                    <p>You are not authorized.</p>
                                <?php endif; ?>
                            </h4>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<!-- CoreUI and necessary plugins-->
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

</html>