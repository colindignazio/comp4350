<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beer extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('Beer_lib');
    }

    public function searchById(){
        if(!$this->requireParams(['beverage_id'  => 'str'])) return;
        $params = $this->getParams();
        $id = $params['beverage_id'];
        
        $beer = $this->beer_lib->getBeerById($id);

        if($beer === null) {
            $this->sendResponse(400, ['details' => 'No matching beverage for ID: ' . $id]);    
        }
        else {
            $this->sendResponse(200, ['results' => $beer]);
        }        
    }

    public function search() {
        if(!$this->requireParams(['searchToken'  => 'str'])) return;
        $params = $this->getParams();
        $token = $params['searchToken'];

        $results = $this->beer_lib->getSearchResults($token);

        if($results === null) {
            $this->sendResponse(400, ['details' => 'Search token too short']);
        } 
        elseif(count($results) == 0) {
            $this->sendResponse(200, ['details' => 'No matching results']);    
        } else {
            $this->sendResponse(200, $results);  
        }
    }

    public function getAllBeers() {
        $this->sendResponse(200, ['results' => $this->beer_lib->getAllBeers()]);
    }

}
?>