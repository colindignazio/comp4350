<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('sessions');
        $this->load->model('users');
        $this->users->loadDB("User_access_object");
    }

/**************************************************
THESE GOT TO GO PROBABLY
***************************************************/
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

    public function testEmail() {
        if(!$this->requireParams([
            'email'      => 'str'
        ])) return;
        $params = $this->getParams();
        $email = $params['email'];
        mail($params['email'], "test", "Hello World", "From: Boozr <$email> \r\n");
        $this->sendResponse(200);
    }

    public function createAccount() {
        if(!$this->requireParams([
            'userName'  => 'str',
            'password'   => 'str',
            'email'      => 'str',
            'location'  => 'str'
        ])) return;
        $params = $this->getParams();
        
        $userInfoError = $this->users->validateUserInfo($params['userName'], $params['password'], $params['email'], $params['location']);

        if(is_null($userInfoError)) {
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
                $userId = $this->db->insert_id();
                $sessionId = $this->sessions->createSession($userId);
                unset($data['User_password']);
                unset($data['User_email_code']);
                $this->sendResponse(200, ['user' => $data, 'sessionId' => $sessionId]);
            }
        } 
        else {
            $this->sendResponse(400, ['details' => $userInfoError]);
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
                $sessionId = $this->sessions->createSession($user['User_id']);
                $this->sendResponse(200, ['user' => $user, 'sessionId' => $sessionId]);
            } else {
                $this->sendResponse(400, ['details' => 'Invalid username or password']);
            }
        } else {
            $this->sendResponse(400, ['details' => 'Invalid username or password']);
        }
    }

    public function logout() {
        if(!$this->requireParams(['sessionId' => 'str'])) return;
        $params = $this->getParams();
        $this->db->where(['Session_id' => $params['sessionId']]);
        if(!$this->db->delete('Sessions')) {
            $this->sendResponse(400, ['details' => 'Error logging out']);
        } else {
            $this->sendResponse(200);
        }
    }

    public function setUsername() {
        if(!$this->requireParams(['sessionId' => 'str', 'userName' => 'str'])) return;
        $params = $this->getParams();

        $data = ['User_name'    => $params['userName']];

        if(FALSE !== $user = $this->sessions->getUser($params['sessionId'])) {
            $this->db->where(['User_id' => $user['User_id']]);
            if(!$this->db->update('Users', $data)) {
                $error = $this->db->error();

                if(1062 == $error['code']) {
                    $this->sendResponse(400, ['details' => 'Username address in use']);
                }
                else {
                    $this->sendResponse(500, ['details' => 'An unknown error occurred']);
                }
            } else {
                $this->sendResponse(200);
            }
        } else {
            $this->sendResponse(401);
        }
    }

    public function setPassword() {
        if(!$this->requireParams([
            'sessionId' => 'str',
            'oldPass'   => 'str', 
            'newPass'   => 'str'])) return;
        $params = $this->getParams();

        if(FALSE !== $user = $this->sessions->getUser($params['sessionId'])) {
            $data = ['User_password'    => password_hash($params['newPass'], PASSWORD_DEFAULT)];

            if(password_verify($params['oldPass'], $user['User_password'])) {
                $this->db->where(['User_id' => $user['User_id']]);
                if(!$this->db->update('Users', $data)) {
                    $this->sendResponse(500, ['details' => 'An unknown error occurred']);
                } else {
                    $this->sendResponse(200);
                }
            } else {
                $this->sendResponse(400, ['details' => 'Passwords do not match']);
            }
        } else {
            $this->sendResponse(401);
        }
    }

    public function setLocation() {
        if(!$this->requireParams([
            'sessionId' => 'str', 
            'location'   => 'str'])) return;
        $params = $this->getParams();

        if(FALSE !== $user = $this->sessions->getUser($params['sessionId'])) {
            $data = ['User_location'    => $params['location']];
            $this->db->where(['User_id' => $user['User_id']]);
            if(!$this->db->update('Users', $data)) {
                $this->sendResponse(500, ['details' => 'An unknown error occurred']);
            } else {
                $this->sendResponse(200);
            }
        } else {
            $this->sendResponse(401);
        }
    }
}