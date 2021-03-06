<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_lib {
	public function __construct($dbaccess = array(0 => 'User_access_object')) {
		$this->CI =& get_instance();
        $this->CI->load->model($dbaccess[0], 'user_access');
        $this->CI->load->library('sessions_lib');
    }

    public function createAccount($username, $password, $email, $location) {
        $userInfoError = $this->validateUserInfo($username, $password, $email, $location);

        if(is_null($userInfoError)) {
            $emailCode = genVerificationCode();
            // Insert row into users table
            $data = [
                'User_name'            => $username,
                'User_password'        => password_hash($password, PASSWORD_DEFAULT),
                'User_email'           => $email,
                'User_location'        => $location,
                'User_email_code'      => $emailCode
            ];

            // Insert and check for unique key error
            if(!$this->CI->user_access->createAccount($data)) {
                $result = ['status' => 500, 'details' => 'An unknown error occurred'];
            }
            else {
                //$this->CI->load->library('emailer');
                //$this->CI->emailer->sendVerificationCode($params['email'], $emailCode);
                $userId = $this->CI->user_access->insert_id();
                $sessionId = $this->CI->sessions_lib->createSession($userId);
                unset($data['User_password']);
                unset($data['User_email_code']);
                $result = ['status' => 200, 'user' => $data, 'sessionId' => $sessionId];
            }
        } 
        else {
            $result = ['status' => 400, 'details' => $userInfoError];
        }
        return $result;
    }

    public function deleteAccount($username, $password) {
        $query = $this->CI->user_access->getUserByName($username);
        if(count($query) > 0) {
            $user = $this->CI->user_access->getUserByNameRow($username);

            if(password_verify($password, $user['User_password'])) {
                if(!$this->CI->user_access->removeUser($user['User_id'])) {
                    $result['status'] = 500;
                    $result['details'] = 'An unknown error occurred';
                } else {
                    $result['status'] = 200;
                }
            } else {
                $result['status'] = 400;
                $result['details'] = 'Invalid username or password';
            }
        } else {
            $result['status'] = 400;
            $result['details'] = 'Invalid username or password';
        }
        return $result;
    }

    public function login($username, $password) {
        $query = $this->CI->user_access->getUserByName($username);
        if(count($query) > 0) {
            $user = $this->CI->user_access->getUserByNameRow($username);

            if(password_verify($password, $user['User_password'])) {
                unset($user['User_password']);
                $sessionId = $this->CI->sessions_lib->createSession($user['User_id']);
                $result['status'] = 200;
                $result['user'] = $user;
                $result['sessionId'] = $sessionId;
            } else {
                $result['status'] = 400;
                $result['details'] = 'Invalid username or password';
            }
        } else {
            $result['status'] = 400;
            $result['details'] = 'Invalid username or password';
        }
        return $result;
    }

    public function getUser($sessionId) {
        $query = $this->CI->user_access->getUserBySession($sessionId);
        if(count($query) > 0) {
            $user = $this->CI->user_access->getUserBySessionRow($sessionId);

            unset($user['User_password']);
            unset($user['Session_id']);
            $result['status'] = 200;
            $result['user'] = $user;
        } else {
            $result['status'] = 400;
            $result['details'] = 'Invalid user';
        }
        return $result;
    }

    public function getUserById($userId) {
        $query = $this->CI->user_access->getUserById($userId);
        if(count($query) > 0) {
            $user = $this->CI->user_access->getUserByIdRow($userId);

            unset($user['User_password']);
            unset($user['Session_id']);
            $result['status'] = 200;
            $result['user'] = $user;
        } else {
            $result['status'] = 400;
            $result['details'] = 'No user found';
        }
        return $result;
    }

    public function logout($sessionId) {
        if(! $this->CI->user_access->logout($sessionId)) {
            $result = ['status' => 400, 'details' => 'Error logging out'];
        } else {
            $result['status'] = 200;
        }
        return $result;
    }

    public function getSearchResults($token) {
        if(strlen($token) < 3) {
            return null;
        } 
        else {
            $responseArray = [];
            $nameMatches = $this->CI->user_access->searchByName($token);
            $locationMatches = $this->CI->user_access->searchByLocation($token);
            $emailMatches = $this->CI->user_access->searchByEmail($token);
            
            if (count($nameMatches)>0){
                $responseArray = array_merge($responseArray, $nameMatches);
            }
            if (count($locationMatches)>0){
                $responseArray = array_merge($responseArray, $locationMatches);
            }
            if (count($emailMatches)>0){
                $responseArray = array_merge($responseArray, $emailMatches);
            }

            $temp_array = array(); 
            $i = 0; 
            $key_array = array(); 
            $key='User_id';
            
            foreach($responseArray as $val) { 
                if (!in_array($val[$key], $key_array)) { 
                    array_push($key_array, $val[$key]);
                    array_push($temp_array, $val); 
                }  
            } 
            return $temp_array; 
        }
    }

    public function setUsername($sessionId, $userName) {
        $data = ['User_name'    => $userName];

        if(FALSE !== $user = $this->CI->sessions_lib->getUser($sessionId)) {

            if(!$this->CI->user_access->update($user['User_id'], $data)) {
                $error = $this->CI->user_access->lastError();

                if(1062 == $error['code']) {
                    $result = ['status' => 400, 'details' => 'Username address in use'];
                }
                else {
                    $result = ['status' => 500, 'details' => 'An unknown error occurred'];
                }
            } else {
                $result['status'] = 200;
            }
        } else {
            $result['status'] = 401;
        }
        return $result;
    }

    public function setPassword($sessionId, $oldPass, $newPass) {
        if(FALSE !== $user = $this->CI->sessions_lib->getUser($sessionId)) {
            $data = ['User_password'    => password_hash($newPass, PASSWORD_DEFAULT)];

            if(password_verify($oldPass, $user['User_password'])) {
                if(!$this->CI->user_access->update($user['User_id'], $data)) {
                    $result = ['status' => 500, 'details' => 'An unknown error occurred'];
                } else {
                    $result['status'] = 200;
                }
            } else {
                $result = ['status' => 400, 'details' => 'Passwords do not match'];
            }
        } else {
            $result['status'] = 401;
        }
        return $result;
    }

    public function setLocation($sessionId, $location) {
        if(FALSE !== $user = $this->CI->sessions_lib->getUser($sessionId)) {
            $data = ['User_location'    => $location];
            if(!$this->CI->user_access->update($user['User_id'], $data)) {
                $result = ['status' => 500, 'details' => 'An unknown error occurred'];
            } else {
                $result = ['status' => 200];
            }
        } else {
            $result = ['status' => 401];
        }
        return $result;
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