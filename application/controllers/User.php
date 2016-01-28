<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {
    public function __construct() {
        parent::__construct();
        //$this->load->model('users');
        //$this->load->model('workers');
        //$this->load->model('sessions');
    }

    public function tesst(){
        echo mysql_now();
    }

    public function testApi() {
        if(!$this->requireParams(['message'  => 'str'])) return;
        $params = $this->getParams();
        $this->sendResponse(200, ['message' => $params['message']]);
    }

    public function testDatabase() {
        $query = $this->db->where('testId', "1")
                          ->get('test');
        $this->sendResponse(200, ['results' => $query->result_array()]);
    }

    public function createAccount() {
        if(!$this->requireParams([
            'userName'  => 'str',
            'password'   => 'str',
            'email'      => 'str'
        ])) return;
        $params = $this->getParams();
        
        if(is_null($params['email']) || !isEmail($params['email'])) {
            $result['status'] = 400;
            $result['details'] = 'Invalid email address';
        }
        else if(is_null($params['password'])) {
            $result['status'] = 400;
            $result['details'] = 'Invalid password';
        }
        else if(is_null($params['userName'])) {
            $result['status'] = 400;
            $result['details'] = 'Invalid username';
        }
        else {
            $query = $this->db->where('User_name', $params['userName'])
                          ->get('Users');
            if(count($query->result_array()) > 0) {
                $this->sendResponse(400, ['details' => 'Username in already in use']);
                return;
            }

            $query = $this->db->where('User_email', $params['email'])
                          ->get('Users');
            if(count($query->result_array()) > 0) {
                $this->sendResponse(400, ['details' => 'Email in already in use']);
                return;
            }

            $emailCode = genVerificationCode();
            // Insert row into users table
            $data = [
                'User_name'            => $params['userName'],
                'User_password'        => password_hash($params['password'], PASSWORD_DEFAULT),
                'User_email'           => $params['email'],
                'User_email_code'      => $emailCode
            ];

            // Insert and check for unique key error
            $this->load->database();
            if(!$this->db->insert('Users', $data)) {
                $this->sendResponse(500, ['details' => 'An unknown error occurred']);
            }
            else {
                $this->load->library('emailer');
                $this->emailer->sendVerificationCode($params['email'], $emailCode);
                $this->sendResponse(200);
            }
        }
    }

    public function login() {
        if(!$this->requireParams(['userName' => 'str', 'password' => 'str'])) return;
        $params = $this->getParams();

        $query = $this->db->get_where('Users', ['User_name' => $params['userName']]);
        if(count($query->result_array()) > 0) {
            $user = $query->row_array();

            if(password_verify($params['password'], $user['User_password'])) {
                unset($user['User_password']);
                $this->sendResponse(200, ['user' => $user]);
            } else {
                $this->sendResponse(400, ['details' => 'Invalid username or password']);
            }
        } else {
            $this->sendResponse(400, ['details' => 'Invalid username or password']);
        }
    }
}
?>