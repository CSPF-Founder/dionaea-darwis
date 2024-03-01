<?php

/**
 * Copyright (c) 2020 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

use App\Config;
use Core\PageMessage;
use Core\View;

?>
<script src='https://www.google.com/recaptcha/api.js' async defer></script>

<!-- Contact Form Section Starts -->
<section class="contactform">
    <div class="section-overlay">
        <div class="container">

            <!-- Main Heading Starts -->
            <div class="text-center top-text padding-top-15percent">
                <h1><span>Support</span> Page</h1>
            </div>

            <?php if (isset($page_messages) && $page_messages) : ?>
                <?php /** @var PageMessage $message */ ?>
                <?php foreach ($page_messages as $page_message) : ?>
                    <div class="col-sm-12 output_message_holder display-block plr200 ">
                        <p class="output_message message-p <?php View::securePrint($page_message->type); ?>">
                            <?php View::securePrint($page_message->message); ?>
                            <br />
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <!-- Main Heading Ends -->

            <div class="form-container">
                <!-- Support Form Starts -->
                <form method="post" id="support-form" autocomplete="off" action="/support">
                    <div class="row form-inputs">

                        <!-- First Name Field Starts -->
                        <div class="col-sm-6 form-group custom-form-group">
                            <span class="input custom-input">
                                <input placeholder="First Name" class="input-field custom-input-field" id="firstname" name="first_name" value="<?php if (isset($customer_info) && $customer_info) {
                                    View::securePrint($customer_info->getFirstName());
                                } ?>" type="text" required data-error="NEW ERROR MESSAGE">
                                <label class="input-label custom-input-label">
                                    <i class="fa fa-user icon icon-field"></i>
                                </label>
                            </span>
                        </div>
                        <!-- First Name Field Ends -->

                        <!-- Last Name Field Starts -->
                        <div class="col-sm-6 form-group custom-form-group">
                            <span class="input custom-input">
                                <input placeholder="Last Name" class="input-field custom-input-field" id="lastname" name="last_name" value="<?php if (isset($customer_info) && $customer_info) {
                                    View::securePrint($customer_info->getLastName());
                                } ?>" type="text" required>
                                <label class="input-label custom-input-label">
                                    <i class="fa fa-user-o icon icon-field"></i>
                                </label>
                            </span>
                        </div>
                        <!-- Last Name Field Ends -->

                        <!-- Message Field Starts -->
                        <div class="form-group custom-form-group col-sm-12">
                            <?php if (isset($work) && $work) : ?>
                                <textarea placeholder="Message" id="message" name="message" cols="45" rows="7" required>Reference Number : <?php if (isset($work) && $work) {
                                    View::securePrint($work->getReferenceNumber());
                                } ?>
                                &#013;Work Title : <?php if (isset($work) && $work) {
                                    View::securePrint($work->getTitle());
                                } ?></textarea>
                            <?php else : ?>
                                <textarea placeholder="Message" id="message" name="message" cols="45" rows="7" required></textarea>
                            <?php endif; ?>
                        </div>
                        <!-- Message Field Ends -->

                        <!-- Email Field Starts -->
                        <div class="col-sm-6 form-group custom-form-group">
                            <span class="input custom-input">
                                <input placeholder="Email" class="input-field custom-input-field" id="email" name="email" value="<?php if (isset($customer_info) && $customer_info) {
                                    View::securePrint($customer_info->getEmail());
                                } ?>" type="email" required>
                                <label class="input-label custom-input-label">
                                    <i class="fa fa-envelope icon icon-field"></i>
                                </label>
                            </span>
                        </div>
                        <!-- Email Field Ends -->

                        <!-- CAPTCHA Field Starts -->
                        <div class="col-sm-6 form-group custom-form-group">
                            <div class="g-recaptcha" data-sitekey="<?php View::securePrint($_ENV["RECAPTCHA_SITE_KEY"]); ?>"></div>
                        </div>
                        <!-- CAPTCHA Field Ends -->

                        <!-- Submit Button Starts -->
                        <div class="col-sm-6 submit-form">
                            <button id="form-submit" type="submit" class="custom-button" title="Send">Send Message</button>
                        </div>
                        <!-- Submit Button Ends -->

                        <!-- Form Submit Message Starts -->
                        <div class="col-sm-12 text-center output_message_holder">
                            <p class="output_message"></p>
                        </div>
                        <!-- Form Submit Message Ends -->

                    </div>
                </form>
                <!-- Contact Form Ends -->
            </div>

        </div>
    </div>

</section>
<!-- Contact Form Section Ends -->
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
<script>
    $(document).ready(function() {
        <?php if (isset($response) && $response) : ?>
            showInfo("Mail sent successfully", "INFO");
        <?php endif; ?>
    });
</script>
