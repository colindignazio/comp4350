<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sessions_access_object extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();        
    }

    public function getBySession($sessionId) {
        return $this->db->where('Session_id', $sessionId)->get('Sessions');
    }

    public function getByUser($userId) {
        return $this->db->where('User_id', $userId)->get('Sessions');
    }

    public function updateSession($oldSession, $newSession) {
        $this->db->where(['Session_id' => $oldSession]);
        if(!$this->db->update('Sessions', ['Session_id' => $newSession])) {
            return false;
        } else {
            return true;
        }
    }

    public function insertSession($sessionId, $userId) {
        return $this->db->insert('Sessions', ['Session_id' => $sessionId, 'User_id' => $userId]);
    }

    public function generateId() {
        $bytes = openssl_random_pseudo_bytes(20, $cstrong);
        return bin2hex($bytes);
    }
}