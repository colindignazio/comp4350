<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_access_object extends CI_Model {
    public function __construct() {
        parent::__construct();
    	$this->load->database();        
    }

    public function getUserByName($username) {
    	return $this->db->where('User_name', $username)->get('Users');
    }

    public function getUserByEmail($email) {
		return $this->db->where('User_email', $email)->get('Users');
    }

    public function getUserById($userId) {
        return $this->db->where('User_id', $userId)->get('Users');
    }
}