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
        $query = $this->db->where('Beer_id', "9")
                          ->get('Beers');
        $this->sendResponse(200, ['results' => $query->result_array()]);
    }
}
?>