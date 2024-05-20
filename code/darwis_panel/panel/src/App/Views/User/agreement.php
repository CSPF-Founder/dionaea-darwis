<?php

/**
 * Copyright (c) 2020 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

use App\Auth;

?>
<style>
.modal-header {
    background-color: #0f3a89;
    color: white;
}

.modal-footer {

    border: none;
}

.btn-group {
    z-index: 1051;
}

.modal-body {
    height: 97%;
    overflow: auto;
}

.modal {
    height: 97%;
    overflow: auto;
}

ol {
    counter-reset: item;
}

li {
    display: block;
}

li:before {
    content: counters(item, ".") " ";
    counter-increment: item;
}

li p {
    margin: 0;
    display: inline;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
    $("#sidebar").hide();
    $("header").hide();
    $("#customer-agreement").show();
    $("#customer-agreement-text").scrollTop(0);
    $('#customer-agreement-text').on('scroll', function() {
        if ($(this).scrollTop() +
            $(this).innerHeight() >=
            $(this)[0].scrollHeight) {
            $('#closeBtn').prop('disabled', false);
        }
    });
});
</script>

<div class="row pr-3">
    <div class="col-12 p-0 ">
        <div class="modal " id="customer-agreement" role="dialog">
            <div class="modal-dialog modal-lg ">
                <form action="agreement.php" method="POST">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Terms and Conditions</h4>
                        </div>
                        <div class="modal-body" id="customer-agreement-text" style="height:650px;">
                            <embed src="/user/download-tac" style="width: 100%;height: 90%;border: none;"
                                type="application/pdf">
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>