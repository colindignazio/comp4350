<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('sessions');
        $this->load->model('user_access_object', 'user_access');
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
        $query = $this->user_access->testDatabase();
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
        
        $userInfoError = $this->validateUserInfo($params['userName'], $params['password'], $params['email'], $params['location']);

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
            if(!$this->user_access->createAccount($data)) {
                $this->sendResponse(500, ['details' => 'An unknown error occurred']);
            }
            else {
                $this->load->library('emailer');
                $this->emailer->sendVerificationCode($params['email'], $emailCode);
                $userId = $this->user_access->insert_id();
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

    private function validateUserInfo($username, $password, $email, $location) {
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

    private function isUsernameInUse($username) {
        $result = false;

        $query = $this->user_access->getUserByName($username)->result_array();
        
        if(count($query) > 0) {
            $result = true;
        }

        return $result;
    }

    private function isEmailInUse($email) {
        $result = false;

        $query = $this->user_access->getUserByEmail($email)->result_array();
            
        if(count($query) > 0) {
            $result = true;
        }

        return $result;
    }

    public function login() {
        if(!$this->requireParams(['userName' => 'str', 'password' => 'str'])) return;
        $params = $this->getParams();

        $query = $this->user_access->login($params['userName']);
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
        if(! $this->user_access->logout($params['sessionId'])) {
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

            if(!$this->user_access->update($user['User_id'], $data)) {
                $error = $this->user_access->lastError();

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
                if(!$this->user_access->update($user['User_id'], $data)) {
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
            if(!$this->user_access->update($user['User_id'], $data)) {
                $this->sendResponse(500, ['details' => 'An unknown error occurred']);
            } else {
                $this->sendResponse(200);
            }
        } else {
            $this->sendResponse(401);
        }
    }
}