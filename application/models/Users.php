<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function validateUserInfo($username, $password, $email, $location) {
		$result = null;		

		if(is_null($email) || $email == "" || !isEmail($email)) {
            $result = 'Invalid email address';
        }
        else if(is_null($password) || $password == "") {
            $result = 'Invalid password';
        }
        else if(is_null($username) || $username == "") {
            $result = 'Invalid username';
        }
        else if(is_null($location) || $location == "") {
            $result = 'Invalid location';
        }

        return $result;
    }
}