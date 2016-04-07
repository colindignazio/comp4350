<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sessions_lib extends CI_Model {
    public function __construct($dbaccess = array(0 => 'User_access_object', 1 => 'Sessions_access_object')) {
        parent::__construct();
        $this->load->database();
        $this->load->model($dbaccess[0], 'user_access');
        $this->load->model($dbaccess[1], 'sessions_access');
    }

    public function createSession($userId) {
        $sessionId = $this->sessions_access->generateId();
        $query = $this->sessions_access->getBySession($sessionId);

        while(count($query) > 0) {
            $sessionId = $this->sessions_access->generateId();
            $query = $this->sessions_access->getBySession($sessionId);
        }
        
        if(!is_numeric($userId)) return false;

        $userQuery = $this->sessions_access->getByUser($userId);

        if(count($userQuery) > 0) {
            $userQuery = $this->sessions_access->getByUserRow($userId);
            if(!$this->sessions_access->updateSession($userQuery['Session_id'], $sessionId)) {
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
        if(count($query) > 0) {
            //A valid session exists
            $query = $this->sessions_access->getBySessionRow($sessionId);
            $userQuery = $this->user_access->getUserById($query['User_id']);
            if(count($userQuery) > 0) {
                return $this->user_access->getUserByIdRow($query['User_id']);
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }
}
?>