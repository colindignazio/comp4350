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
    public function newBeer($Type, $Name, $Alcohol_By_Volume, $Brewery, $Rating, $AvgPrice){
        return $this->CI->beer_access->newBeer($Type, $Name, $Alcohol_By_Volume, $Brewery, $Rating, $AvgPrice);
    }

    public function getAdvancedSearchResults($beerName, $beerType, $brewery, $minPrice, $maxPrice, $minRating, $maxRating, $beerContent)
    {
        $responseArray = [];
        $count =0;
        $tempArray = [];
        if($beerName != "") {
            $nameMatches = $this->CI->beer_access->getByName($beerName);
            $responseArray = $nameMatches;
            $count++;
        }
        if($beerType != "") {
            $typeMatches = $this->CI->beer_access->getByType($beerType);
            if(count($responseArray) == 0)
            {
                $responseArray = $typeMatches;
            }
            else{
                $responseArray = array_merge($responseArray, $typeMatches);
            }
            $count++;
        }
        if($brewery != "") {
            $breweryMatches = $this->CI->beer_access->getByBrewery($brewery);
            if(count($responseArray) == 0)
            {
                $responseArray = $breweryMatches;
            }
            else{
                $responseArray = array_merge($responseArray, $breweryMatches);
            }
            $count++;
        }
        if($minPrice){
            $lowPriceMatches = $this->CI->beer_access->getPriceOver($minPrice);
            if(count($responseArray) == 0)
            {
                $responseArray = $lowPriceMatches;
            }
            else{
                $responseArray = array_merge($responseArray, $lowPriceMatches);
            }
            $count++;
        }
        if($maxPrice){
            $highPriceMatches = $this->CI->beer_access->getPriceUnder($minPrice);
            if(count($responseArray) == 0)
            {
                $responseArray = $highPriceMatches;
            }
            else{
                $responseArray = array_merge($responseArray, $highPriceMatches);
            }
            $count++;
        }
        if($minRating){
            $lowRatingMatches = $this->CI->beer_access->getRatingOver($minRating);
            if(count($responseArray) == 0)
            {
                $responseArray = $lowRatingMatches;
            }
            else{
                $responseArray = array_merge($responseArray, $lowRatingMatches);
            }
            $count++;
        }
        if($maxRating){
            $highRatingMatches = $this->CI->beer_access->getRatingUnder($maxRating);
            if(count($responseArray) == 0)
            {
                $responseArray = $highRatingMatches;
            }
            else{
                $responseArray = array_merge($responseArray, $highRatingMatches);
            }
            $count++;
        }
        if($beerContent){
            $aclVolMatches = $this->CI->beer_access->getByAlcoholVol($beerContent);
            if(count($responseArray) == 0)
            {
                $responseArray = $aclVolMatches;
            }
            else{
                $responseArray = array_merge($responseArray, $aclVolMatches);
            }
            $count++;
        }

        if($count > 1) {
            for ($i = 0; $i < count($responseArray); $i++) {
                $temp = 1;
                $added = 0;
                for ($j = $i + 1; $j < count($responseArray); $j++) {
                    if ($responseArray[$i] === $responseArray[$j]) {
                        $temp++;
                    }
                    if ($temp === $count && $added != 1) {
                        array_push($tempArray, $responseArray[$i]);
                        $added = 1;
                    }
                }
            }
        }
        else
        {
            $tempArray = $responseArray;
        }
        return $tempArray;
    }

    private function compareBeers($beer1, $beer2)
    {
        return !strcmp($beer1['Beer_id'],$beer2['Beer_id']);
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

            $temp_array = []; 
            $i = 0; 
            $key_array = array(); 
            $key='Beer_id';
            
            foreach($responseArray as $val) { 
                if (!in_array($val[$key], $key_array)) { 
                    $key_array[$i] = $val[$key]; 
                    array_push($temp_array, $val);
                } 
                //$i++; 
            } 
            return $temp_array;
        }
    }
    public function updateRating($reviews, $beer_id){
        $i = 0;
        $totalScore = 0;
        foreach($reviews as $val){
            $totalScore += $val['stars'];
            $i++;
        }
        $newRating = $totalScore/$i;
        return $this->CI->beer_access->updateRating($newRating, $beer_id);
    }

    public function getAllBeers() {
    	return $this->CI->beer_access->getAll();
    }
    public function getTopDrinks() {
        return $this->CI->beer_access->getTop();
    }
}