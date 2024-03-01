<?php

/**
 * Copyright (c) 2017 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

use Core\View;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Error ! </title>
    <link rel="stylesheet" href="/resources/new-theme/vendor/simplebar/css/simplebar.css">
    <link rel="stylesheet" href="/resources/new-theme/css/vendors/simplebar.css">
    <!-- Main styles for this application-->
    <link href="/resources/new-theme/css/style.css" rel="stylesheet">

</head>

<body class="nav-md">



    <div class="bg-light min-vh-100 d-flex flex-row align-items-center dark:bg-transparent">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="clearfix">
                        <div class="alert alert-danger message_box">
                            <strong>Debug Mode is On!</strong> If you are running in production, turn it off
                        </div>
                        <h1 class="display-3 me-4">500 - Internal Server Error</h1>

                        <?php if (isset($exception)) : ?>
                            <h4 class="pt-3">Uncaught Exception - "<?php View::securePrint(get_class($exception)) ?>" </h4>
                            <h4>Message <?php View::securePrint($exception->getMessage()) ?> </h4>
                            <h4>Stack Trace :
                                <pre><?php View::securePrint($exception->getTraceAsString()) ?> </pre>
                            </h4>
                            <h4>Throws in : <?php View::securePrint($exception->getFile()) ?> on line
                                <?php View::securePrint($exception->getLine()) ?></h4>
                        <?php else : ?>
                            <h4>Sorry, an error has occurred - Please contact the admin </h4>
                        <?php endif; ?>
                        <h4 class="pt-3">If the problem persists feel free to contact us</a>
                        </h4>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
<script src="/resources/new-theme/vendor/@coreui/coreui-pro/js/coreui.bundle.min.js"></script>
<script src="/resources/new-theme/vendor/simplebar/js/simplebar.min.js"></script>

</html>