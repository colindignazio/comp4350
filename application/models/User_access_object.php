<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_access_object extends CI_Model {
    public function __construct() {
        parent::__construct();
    	$this->load->database();        
    }

    public function getUserByName($username) {
    	return $this->db->where('User_name', $username)->get('Users')->result_array();
    }

    public function getUserBySession($sessionId) {
        return $this->db->query('SELECT * FROM Sessions 
                                 JOIN Users ON Sessions.User_id = Users.User_id 
                                 WHERE Session_id="' . $sessionId . '"')->result_array();
    }

    public function getUserBySessionRow($sessionId) {
        return $this->db->query('SELECT * FROM Sessions 
                                 JOIN Users ON Sessions.User_id = Users.User_id 
                                 WHERE Session_id="' . $sessionId . '"')->row_array();
    }

    public function getUserByNameRow($username) {
        return $this->db->where('User_name', $username)->get('Users')->row_array();
    }

    public function getUserByEmail($email) {
		return $this->db->where('User_email', $email)->get('Users')->result_array();
    }

    public function removeUser($userId) {
        return $this->db->where('User_id', $userId)->delete('Users');
    }

    public function getUserById($userId) {
        return $this->db->where('User_id', $userId)->get('Users')->result_array();
    }
    public function getUserByIdRow($userId) {
        return $this->db->where('User_id', $userId)->get('Users')->row_array();
    }
    public function createAccount($data){
        return $this->db->insert('Users', $data);
    }
    public function login($userName){
        return $query =  $this->db->get_where('Users', ['User_name' => $userName])->result_array();
    }
    public function logout($sessionId){
        return $this->db->where(['Session_id' => $sessionId])->delete('Sessions');
    }
    public function insert_id(){
       return $this->db->insert_id();
    }
    public function lastError(){
        return $this->db->error();
    }
    public function update($userId, $data){
        $this->db->where(['User_id' => $userId]);
        return $this->db->update('Users', $data);
    }
    public function testDatabase(){
        return $this->db->where('testId', "1")->get('test')->result_array();
    }
}