<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beer extends MY_Controller {
    public function __construct() {
        parent::__construct();
        //$this->load->model('users');
        //$this->load->model('workers');
        //$this->load->model('sessions');
    }

    public function All() {
        $query = $this->db->get('Beers');
                          
        $this->sendResponse(200, ['results' => $query->result_array()]);
    }
    public function ExampleBeer() {
        $query = $this->db->where('Beer_id', "12")
                          ->get('Beers');
        $this->sendResponse(200, ['results' => $query->result_array()]);
    }
    public function Ale() {
        $query = $this->db->where('Type', "Ale")
                          ->get('Beers');         
        $this->sendResponse(200, ['results' => $query->result_array()]);
    }
    public function Stout() {
        $query = $this->db->where('Type', "Stout")
                          ->get('Beers');                 
        $this->sendResponse(200, ['results' => $query->result_array()]);
    }
    public function Lager() {
        $query = $this->db->where('Type', "Lager")
                          ->get('Beers');
        $this->sendResponse(200, ['results' => $query->result_array()]);
    }
}
?>