<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('sessions');
        $this->load->model('user_access_object', 'user_access');
        $this->load->library('user_lib');
    }

    public function createAccount() {
        if(!$this->requireParams(['userName'  => 'str', 'password'   => 'str', 'email'      => 'str', 'location'  => 'str'])) return;
        $params = $this->getParams();
        $result = $this->user_lib->createAccount($params['userName'], $params['password'], $params['email'], $params['location']);
        if($result['status'] == 200) {
            $this->sendResponse(200, ['user' => $result['user'], 'sessionId' => $result['sessionId']]);
        } else {
            $this->sendResponse($result['status'], ['details' => $result['details']]);
        }
    }

    public function login() {
        if(!$this->requireParams(['userName' => 'str', 'password' => 'str'])) return;
        $params = $this->getParams();
        $result = $this->user_lib->login($params['userName'], $params['password']);
        if($result['status'] == 200) {
            $this->sendResponse(200, ['user' => $result['user'], 'sessionId' => $result['sessionId']]);
        } else {
            $this->sendResponse($result['status'], ['details' => $result['details']]);
        }
    }

    public function getUserDetails() {
        if(!$this->requireParams(['sessionId' => 'str'])) return;
        $params = $this->getParams();
        $result = $this->user_lib->getUser($params['sessionId']);
        if($result['status'] == 200) {
            $this->sendResponse(200, ['user' => $result['user']]);
        } else {
            $this->sendResponse($result['status'], ['details' => $result['details']]);
        }
    }

    public function logout() {
        if(!$this->requireParams(['sessionId' => 'str'])) return;
        $params = $this->getParams();
        $result = $this->user_lib->logout($params['sessionId']);
        if($result['status'] == 200) {
            $this->sendResponse(200);
        } else {
            $this->sendResponse($result['status'], ['details' => $result['details']]);
        }
    }

    public function setUsername() {
        if(!$this->requireParams(['sessionId' => 'str', 'userName' => 'str'])) return;
        $params = $this->getParams();
        $result = $this->user_lib->setUsername($params['sessionId'], $params['userName']);
        if($result['status'] == 401) {
            $this->sendResponse(401);
        } else if($result['status'] == 200) {
            $this->sendResponse(200);
        } else {
            $this->sendResponse($result['status'], $result['details']);
        }
    }

    public function setPassword() {
        if(!$this->requireParams(['sessionId' => 'str', 'oldPass'   => 'str',  'newPass'   => 'str'])) return;
        $params = $this->getParams();
        $result = $this->user_lib->setPassword($params['sessionId'], $params['oldPass'], $params['newPass']);
        if($result['status'] == 401) {
            $this->sendResponse(401);
        } else if($result['status'] == 200) {
            $this->sendResponse(200);
        } else {
            $this->sendResponse($result['status'], $result['details']);
        }
    }

    public function setLocation() {
        if(!$this->requireParams(['sessionId' => 'str',  'location'   => 'str'])) return;
        $params = $this->getParams();
        $result = $this->user_lib->setLocation($params['sessionId'], $params['location']);
        if($result['status'] == 401) {
            $this->sendResponse(401);
        } else if($result['status'] == 200) {
            $this->sendResponse(200);
        } else {
            $this->sendResponse($result['status'], $result['details']);
        }
    }
}