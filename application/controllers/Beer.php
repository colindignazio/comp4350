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

    public function advancedSearch() {
        $params = $this->getParams();
        $beerName =$params['beerName'];
        $beerType =$params['beerType'];
        $brewery =$params['brewery'];
        $minPrice =$params['minPrice'];
        $maxPrice =$params['maxPrice'];
        $minRating =$params['minRating'];
        $maxRating =$params['maxRating'];
        $beerContent =$params['beerContent'];

        $results = $this->beer_lib->getAdvancedSearchResults($beerName, $beerType, $brewery, $minPrice, $maxPrice, $minRating, $maxRating, $beerContent);

        if(count($results) == 0) {
            $this->sendResponse(200, ['details' => 'No matching results']);
        } else {
            $this->sendResponse(200, ['searchResults' => $results]);
        }
    }
    public function newBeer(){
        if(!$this->requireParams(['Type'  => 'str', 'Name'  => 'str', 'Alcohol_By_Volume'  => 'str','Brewery'  => 'str','Rating'  => 'str','AvgPrice'  => 'str'])){
            $this->sendResponse(400, ['details' => 'Missing Parameters']);
            return;
        }
        $params = $this->getParams();
        $Type = $params['Type'];
        $Name = $params['Name'];
        $Alcohol_By_Volume = $params['Alcohol_By_Volume'];
        $Brewery = $params['Brewery'];
        $Rating = $params['Rating'];
        $AvgPrice = $params['AvgPrice'];
        $this->beer_lib->newBeer($Type, $Name, $Alcohol_By_Volume, $Brewery, $Rating, $AvgPrice);
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
            $this->sendResponse(400, ['details' => 'No matching results']);
        } else {
            $this->sendResponse(200, ['searchResults' => $results]);
        }
    }
    public function getAllBeers() {
        $this->sendResponse(200, ['results' => $this->beer_lib->getAllBeers()]);
    }
    public function getTopDrinks(){
        $this->sendResponse(200, ['results' => $this->beer_lib->getTopDrinks()]);
    }

}
?>