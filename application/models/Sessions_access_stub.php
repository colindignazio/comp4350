<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sessions_access_stub extends CI_Model {
    private $table = array(
        0 => array('Session_id'=>'0', 'User_id'=>0),
        1 => array('Session_id'=>'1', 'User_id'=>1),
        2 => array('Session_id'=>'2', 'User_id'=>2)
    );

    private $newSessions = ['3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20'];
    private $newSessionIndex = 0;

    public function __construct() {
        parent::__construct();        
    }

    public function getBySession($sessionId) {
        foreach($this->table as $entry) {
            if($entry['Session_id'] == $sessionId) {
                return $entry;
            }
        }
    }

    public function getByUser($userId) {
        foreach($this->table as $entry) {
            if($entry['User_id'] == $userId) {
                return $entry;
            }
        }
    }

    public function updateSession($oldSession, $newSession) {
        foreach($this->table as $entry) {
            if($entry['Session_id'] == $oldSession) {
                $entry['Session_id'] = $newSession;
                return true;
            }
        }
    }

    public function insertSession($sessionId, $userId) {
        return true;
    }

    public function generateId() {
        $newSession = $this->newSessions[$this->newSessionIndex];
        $this->newSessionIndex++;
        return $newSession;
    }
}