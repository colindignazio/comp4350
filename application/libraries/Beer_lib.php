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
                $responseArray = array_merge($responseArray, ['nameMatches' => $nameMatches]);
            }
            if (count($breweryMatches)>0){
                $responseArray = array_merge($responseArray, ['breweryMatches' => $breweryMatches]);
            }
            if (count($typeMatches)>0){
                $responseArray = array_merge($responseArray, ['typeMatches' => $typeMatches]);
            }

            return $responseArray;
        }
    }

    public function getAllBeers() {
    	return $this->CI->beer_access->getAll();
    }
    public function getTopDrinks() {
        return $this->CI->beer_access->getTop();
    }
}