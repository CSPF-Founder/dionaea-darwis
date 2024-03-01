<?php

namespace Core;

/**
 * Copyright (c) 2017 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

use App\Config;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Custom PHPMailer class for sending mails
 * Class AppMailer
 */
class AppMailer
{
    public $mail;
    public $errors = array();

    public function __construct($exceptions = null)
    {
        // $this->mail = new PHPMailer();
        $this->mail = new PHPMailer(true); // Throw exception
        $this->mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $this->mail->isSMTP();
        $this->mail->Host = $_ENV["MAIL_HOST"];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $_ENV["MAIL_USER"];
        $this->mail->Password = $_ENV["MAIL_PASS"];
        $this->mail->SMTPSecure = $_ENV["MAIL_MODE"];
        $this->mail->Port = $_ENV["MAIL_PORT"];
        $this->mail->From = $_ENV["MAIL_USER"];
        $this->mail->FromName = $_ENV["MAIL_FROM_NAME"];
        $this->mail->isHTML(true);
        $this->mail->XMailer = ' ';
        $this->mail->Timeout = 300;
    }

    public function sendSupportEmail($subject, $message, $text_body)
    {
        $this->mail->addAddress($_ENV["SUPPORT_RECEIVER_MAIL"]);
        $this->mail->Subject = $subject;
        $this->mail->Body = $message;
        $this->mail->AltBody = $text_body;

        try {
            if ($this->mail->send()) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            AppLogger::error($e->getMessage());
            $this->errors[] = "Error in sending mail";
        }
        return false;
    }

    public function sendContactUsEmail($subject, $message, $text_body)
    {
        $this->mail->addAddress($_ENV["CONTACT_RECEIVER_MAIL"]);
        $this->mail->Subject = $subject;
        $this->mail->Body = $message;
        $this->mail->AltBody = $text_body;

        try {
            if ($this->mail->send()) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            AppLogger::error($e->getMessage());
            $this->errors[] = "Error in sending mail";
        }
        return false;
    }

    public function sendMailToCustomer($recipient, $subject, $message, $text_body)
    {
        $this->mail->addAddress($recipient);
        $this->mail->Subject = $subject;
        $this->mail->Body = $message;
        $this->mail->AltBody = $text_body;

        try {
            if ($this->mail->send()) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            AppLogger::error($e->getMessage());
            $this->errors[] = "Error in sending mail";
        }
        return false;
    }

    public function sendNoticeMail($recipient, $cc, $subject, $message, $text_body)
    {
        $this->mail->addAddress($recipient);
        $this->mail->AddCC($cc);
        $this->mail->Subject = $subject;
        $this->mail->Body = $message;
        $this->mail->AltBody = $text_body;

        try {
            if ($this->mail->send()) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            AppLogger::error($e->getMessage());
            $this->errors[] = "Error in sending mail";
        }
        return false;
    }
}
