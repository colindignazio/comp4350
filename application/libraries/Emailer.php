<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Emailer {
    // Function: sendEmail
    // Description: Sends an email according to the given parameters
    private function sendEmail($to, $subject, $msg, $headers) {
        mail($to, $subject, $msg, implode("\r\n", $headers));
    }
}
?>