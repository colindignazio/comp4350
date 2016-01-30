<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beer extends MY_Controller {
    public function __construct() {
        parent::__construct();
        //$this->load->model('users');
        //$this->load->model('workers');
        //$this->load->model('sessions');
    }

    public function keywordSearch(){
        if(!$this->requireParams(['searchToken'  => 'str'])) return;
        $params = $this->getParams();
        $searchToken = $params['searchToken'];
        if(strlen($searchToken)<3){
            $this->sendResponse(400, ['details' => 'Search token too short']);
        } else {
            $nameQuery = $this->db->query("SELECT * from Beers WHERE Name LIKE '%$searchToken%'"); 
            $breweryQuery = $this->db->query("SELECT * from Beers WHERE Brewery LIKE '%$searchToken%'"); 
            $typeQuery = $this->db->query("SELECT * from Beers WHERE Type LIKE '%$searchToken%'");

        
            $this->sendResponse(200, ['nameMatches' => $nameQuery->result_array(),
                                      'breweryMatches' => $breweryQuery->result_array(),
                                      'typeMatches' => $typeQuery->result_array() ]);
        }
    }

    public function All() {
        $query = $this->db->get('Beers');
                          
        $this->sendResponse(200, ['results' => $query->result_array()]);
    }

}
?>