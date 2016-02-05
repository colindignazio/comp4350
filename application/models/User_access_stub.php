<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_access_stub extends CI_Model {
    private $table;

    private function generateStubTable() {
        return array(
            0 => array('User_id'=>0, 'User_name'=>'testuser123', 'User_password'=> password_hash('testpass', PASSWORD_DEFAULT), 'User_email'=>'test0@email.com', 
                'User_email_verified'=>0, 'User_email_code'=>"", 'User_location'=>'Winnipeg'),
            1 => array('User_id'=>1, 'User_name'=>'Colin', 'User_password'=> password_hash('testpass', PASSWORD_DEFAULT), 'User_email'=>'test1@email.com', 
                'User_email_verified'=>0, 'User_email_code'=>"", 'User_location'=>'Winnipeg'),
            2 => array('User_id'=>2, 'User_name'=>'Mitchell', 'User_password'=> password_hash('testpass', PASSWORD_DEFAULT), 'User_email'=>'test2@email.com', 
                'User_email_verified'=>0, 'User_email_code'=>"", 'User_location'=>'Winnipeg'),
        );
    }

    public function __construct() {
        parent::__construct();  
        $this->table = $this->generateStubTable();      
    }

    public function getUserByName($username) {
        $arr = $this->table;

    	foreach($arr as $entry) {
            if($entry['User_name'] == $username) {
                return $entry;
            }
        }
    }

    public function getUserByNameRow($username) {
        foreach($this->table as $entry) {
            if($entry['User_name'] == $username) {
                return $entry;
            }
        }
    }

    public function getUserByEmail($email) {
        foreach($this->table as $entry) {
            if($entry['User_email'] == $email) {
                return $entry;
            }
        }
    }

    public function getUserById($userId) {
        foreach($this->table as $entry) {
            if($entry['User_id'] == $userId) {
                return $entry;
            }
        }
    }

    public function getUserByIdRow($userId) {
        foreach($this->table as $entry) {
            if($entry['User_id'] == $userId) {
                return $entry;
            }
        }
    }

    public function removeUser($userId) {
        return true;
    }

    public function createAccount($data) {
        return true;
    }

    public function login($username) {
        return true;
    }

    public function logout($sessionId) {
        return true;
    }   

    public function insert_id(){
        return true;
    }

    public function lastError(){
        return true;
    }

    public function update($userId, $data){
        return true;
    }

    public function testDatabase(){
        return true;
    }
}