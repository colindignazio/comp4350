<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Search_m extends CI_Model {

    /**
     * Define the table name
     */
    private $_table = 'Beers';

    /**
     * Read all news items
     */
    public function read()
    {

        // Return an associative array of all news items
        return $this->db->where('Beer_Id', 11)
            ->get($this->_table)
            ->result_array();

    }
    public function search(){
        if(!$this->requireParams(['searchToken'  => 'str'])) return;
        $params = $this->getParams();
        $searchToken = $params['searchToken'];
        if(strlen($searchToken)<3){
            $this->sendResponse(400, ['details' => 'Search token too short']);
        } else {
            $nameQuery = $this->db->query("SELECT * from Beers WHERE Name LIKE '%$searchToken%'"); 
            $breweryQuery = $this->db->query("SELECT * from Beers WHERE Brewery LIKE '%$searchToken%'"); 
            $typeQuery = $this->db->query("SELECT * from Beers WHERE Type LIKE '%$searchToken%'");

            $responseArray = [];
            if (count($nameQuery->result_array())>0){
                $responseArray = array_merge($responseArray, ['nameMatches' => $nameQuery->result_array()]);
            }
            if (count($breweryQuery->result_array())>0){
                $responseArray = array_merge($responseArray, ['breweryMatches' => $breweryQuery->result_array()]);
            }
            if (count($typeQuery->result_array())>0){
                $responseArray = array_merge($responseArray, ['typeMatches' => $typeQuery->result_array()]);
            }

            if(count($responseArray)==0){
                $this->sendResponse(200, ['details' => 'No matching results']);    
            } else {
                $this->sendResponse(200, $responseArray);  
            }
            
        }
    }

}
?>