<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sessions extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function generateId() {
        $bytes = openssl_random_pseudo_bytes(20, $cstrong);
        return bin2hex($bytes);
    }

    public function createSession($userId) {
        $sessionId = $this->generateId();
        $query = $this->db->query(' SELECT *
                                    FROM Sessions 
                                    WHERE Session_id="' . $sessionId . '"');

        while(count($query->result_array()) > 0) {
            $sessionId = $this->generateId();
            $query = $this->db->query(' SELECT *
                                        FROM Sessions 
                                        WHERE Session_id="' . $sessionId . '"');
        }
        
        if(!is_numeric($userId)) return false;
        $data = [
                'Session_id'     => $sessionId,
                'User_id'        => $userId
            ];

        $this->load->database();
        if(!$this->db->insert('Sessions', $data)) {
            return false;
        }
        
        return $sessionId;
    }

    public function getUser($sessionId) {
        $query = $this->db->get_where('Sessions', ['Session_id' => $sessionId]);
        if(count($query->result_array()) > 0) {
            //A valid session exists
            $userQuery = $this->db->get_where('Users', ['User_id' => $query->row_array()['User_id']]);
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