<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sessions extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('sessions_access_object', 'sessions_access');
        $this->load->model('user_access_object', 'user_access');
    }

    public function createSession($userId) {
        $sessionId = $this->sessions_access->generateId();
        $query = $this->sessions_access->getBySession($sessionId);

        while(count($query->result_array()) > 0) {
            $sessionId = $this->sessions_access->generateId();
            $query = $this->sessions_access->getBySession($sessionId);
        }
        
        if(!is_numeric($userId)) return false;

        $userQuery = $this->sessions_access->getByUser($userId);

        if(count($userQuery->result_array()) > 0) {
            if(!$this->sessions_access->updateSession($userQuery->row_array()['Session_id'], $sessionId)) {
                return false;
            }
        } else {
            if(!$this->sessions_access->insertSession($sessionId, $userId)) {
                return false;
            }
        }
        
        return $sessionId;
    }

    public function getUser($sessionId) {
        $query = $this->sessions_access->getBySession($sessionId);
        if(count($query->result_array()) > 0) {
            //A valid session exists
            $userQuery = $this->user_access->getUserById($query->row_array()['User_id']);
            if(count($userQuery->result_array()) > 0) {
                return $userQuery->row_array();
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }
}
?>