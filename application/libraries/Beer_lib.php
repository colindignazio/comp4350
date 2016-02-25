<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beer_lib {
	public function __construct($dbaccess = array(0 => 'Beer_access_object')) {
		$this->CI =& get_instance();
        $this->CI->load->model($dbaccess[0], 'beer_access');
    }

    public function getBeerById($id) {
        $beer = $this->CI->beer_access->getById($id);

    	if(count($beer) == 0) {
    		return null;
        } else {
        	return $beer;
        }
    }

    public function getSearchResults($token) {
    	if(strlen($token) < 3) {
    		return null;
        } 
        else {
            $responseArray = [];
            $nameMatches = $this->CI->beer_access->getByName($token);
            $breweryMatches = $this->CI->beer_access->getByBrewery($token);
            $typeMatches = $this->CI->beer_access->getByType($token);
            
            if (count($nameMatches)>0){
                $responseArray = array_merge($responseArray, $nameMatches);
            }
            if (count($breweryMatches)>0){
                $responseArray = array_merge($responseArray, $breweryMatches);
            }
            if (count($typeMatches)>0){
                $responseArray = array_merge($responseArray, $typeMatches);
            }

            $temp_array = array(); 
            $i = 0; 
            $key_array = array(); 
            $key='Beer_id';
            
            foreach($responseArray as $val) { 
                if (!in_array($val[$key], $key_array)) { 
                    $key_array[$i] = $val[$key]; 
                    $temp_array[$i] = $val; 
                } 
                $i++; 
            } 
            return $temp_array;
        }
    }

    public function getAllBeers() {
    	return $this->CI->beer_access->getAll();
    }
}