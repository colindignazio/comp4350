<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beer extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('beers');
    }

    public function searchById(){
        if(!$this->requireParams(['beverage_id'  => 'str'])) return;
        $params = $this->getParams();
        $id = $params['beverage_id'];
        
        $result = $this->beers->getById($id);
        
        if(count($result)==0){
                $this->sendResponse(200, ['details' => 'No matching beverage for ID: '.$id]);    
            } else {
                $this->sendResponse(200, ['results' => $result]);
            }
    }

    public function search(){
        if(!$this->requireParams(['searchToken'  => 'str'])) return;
        $params = $this->getParams();
        $token = $params['searchToken'];
        if(strlen($token)<3){
            $this->sendResponse(400, ['details' => 'Search token too short']);
        } else {
            
            $responseArray = [];
            $nameMatches = $this->beers->getByName($token);
            $breweryMatches = $this->beers->getByBrewery($token);
            $typeMatches = $this->beers->getByType($token);

            
            if (count($nameMatches)>0){
                $responseArray = array_merge($responseArray, ['nameMatches' => $nameMatches]);
            }
            if (count($breweryMatches)>0){
                $responseArray = array_merge($responseArray, ['breweryMatches' => $breweryMatches]);
            }
            if (count($typeMatches)>0){
                $responseArray = array_merge($responseArray, ['typeMatches' => $typeMatches]);
            }

            if(count($responseArray)==0){
                $this->sendResponse(200, ['details' => 'No matching results']);    
            } else {
                $this->sendResponse(200, $responseArray);  
            }

            
        }
    }

    public function All() {
        $this->sendResponse(200, ['results' => $this->beers->getAll()]);
    }

}
?>