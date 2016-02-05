<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_lib {
	public function __construct($dbaccess = array(0 => 'User_access_object')) {
		$this->CI =& get_instance();
        $this->CI->load->model($dbaccess[0], 'user_access');
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

        $query = $this->CI->user_access->getUserByName($username);
        
        if(count($query) > 0) {
            $result = true;
        }

        return $result;
    }

    public function isEmailInUse($email) {
        $result = false;

        $query = $this->CI->user_access->getUserByEmail($email);
            
        if(count($query) > 0) {
            $result = true;
        }

        return $result;
    }
}