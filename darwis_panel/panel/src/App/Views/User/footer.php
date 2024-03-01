<?php

/**
 * Copyright (c) 2019 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

use Core\View;

?>

</div> <!-- div body Content -->


<!-- Footer -->
<div class="mx-auto block display-block flex-row">
    <footer class="page-footer font-small blue pt-4">
        <p class="text-center">&copy; <?php View::securePrint(date("Y")); ?> <?php View::securePrint($_ENV["COPYRIGHT_FOOTER_COMPANY"]); ?>. All Rights Reserved. </p>
    </footer>
</div>

</div>


<script src="/resources/new-theme/vendor/@coreui/coreui-pro/js/coreui.bundle.min.js"></script>
<script src="/resources/new-theme/vendor/simplebar/js/simplebar.min.js"></script>


<script src="/resources/new-theme/vendor/@coreui/utils/js/coreui-utils.js"></script>
<script src="/resources/new-theme/js/tooltips.js"></script>

<!-- <script src="/resources/new-theme/js/main.js"></script> -->
<!-- Footer -->
</body>

</html>
