<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function loadDB($dbo) {
        $this->load->model('persistence/' . $dbo, 'user_access');
    }

    public function validateUserInfo($username, $password, $email, $location) {
		$result = null;		

        if(is_null($username) || $username == "") {
            $result = 'Invalid username';
        } 
        else if($this->isUsernameInUse($username)) {
        	$result = 'Username already in use';
        }
		else if(is_null($email) || $email == "" || !isEmail($email)) {
            $result = 'Invalid email address';
        }
        else if($this->isEmailInUse($email)) {
        	$result = 'Email already in use';
        }
        else if(is_null($password) || $password == "") {
            $result = 'Invalid password';
        }
        else if(is_null($location) || $location == "") {
            $result = 'Invalid location';
        }

        return $result;
    }

    public function isUsernameInUse($username) {
    	$result = false;

		$query = $this->user_access->getUserByName($username);
        
        if(count($query) > 0) {
            $result = true;
        }

        return $result;
    }

    public function isEmailInUse($email) {
    	$result = false;

		$query = $this->user_access->getUserByEmail($email);
            
        if(count($query) > 0) {
            $result = true;
        }

        return $result;
    }
}