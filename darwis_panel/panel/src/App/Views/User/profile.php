<?php

/**
 * Copyright (c) 2023 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

use App\Auth;
use Core\View;

?>

<div class="row col-lg-11 col-sm-12 mx-auto min-vh-100">
   
    <div class="col-lg-10 mx-auto">
        <!-- Account details card-->
        <div class="card " style="min-height:47.5vh">
            <div class="card-header bg-primary text-white">Account Details</div>
            <div class="card-body">
                <form id="update-profile-form">

                    <div class="mb-3">
                        <label class="small mb-1">Name</label>
                        <input readonly class="form-control readonly" type="text" placeholder="Name" value="<?php View::securePrint(Auth::user()->getName()); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="small mb-1">Username</label>
                        <input readonly class="form-control readonly" type="text" placeholder="Username" value="<?php View::securePrint(Auth::user()->getUsername()); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="small mb-1">Email</label>
                        <input readonly class="form-control readonly" type="text" placeholder="Email" value="<?php View::securePrint(Auth::user()->getEmail()); ?>">
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
