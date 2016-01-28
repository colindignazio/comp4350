<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Emailer {
    // Function: sendEmail
    // Description: Sends an email according to the given parameters
    private function sendEmail($to, $subject, $msg, $headers) {
        mail($to, $subject, $msg, $headers);
    }

    public function sendVerificationCode($email, $code) {
        $msg = "Thank you for registering with Boozr!\nPlease verify your email address using the following code:\n\n$code";
        $headers = 'From: Boozr <mitchellphamm@hotmail.com> \r\n';

        $this->sendEmail($email, "Welcome to Boozr!", $msg, $headers);
    }
}
?>