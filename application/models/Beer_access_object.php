<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beer_access_object extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function getAll(){
    	$query = $this->db->get('Beers');
    	return $query->result_array();
    }

	public function newBeer($Type, $Name, $Alcohol_By_Volume, $Brewery, $Rating, $AvgPrice){
		$data = array(
			'Type' => $Type,
			'Name' => $Name,
			'Alcohol_By_Volume' => $Alcohol_By_Volume,
			'Brewery' => $Brewery,
			'Rating' => $Rating,
			'AvgPrice' => $AvgPrice
		);
		$this->db->insert('Beers', $data);
	}

    public function getById($id){
    	$query = $this->db->where('Beer_id', $id)
    					  ->get('Beers');
    	return $query->result_array();
    }

    public function getByName($name){
		$query = $this->db->like('Name', $name)
    					  ->get('Beers');
    	return $query->result_array();
    }

    public function getByBrewery($brewery){
    	$query = $this->db->like('Brewery', $brewery)
    					  ->get('Beers');
    	return $query->result_array();
    }

    public function getByType($type){
    	$query = $this->db->like('Type', $type)
    					  ->get('Beers');
    	return $query->result_array();
    }
	public function getTop(){
		$sql = "select * from Beers ORDER BY Rating DESC LIMIT 10";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	public function updateRating($newRating, $beer_id){
		$this->db->set('Rating', $newRating, FALSE);
		$this->db->where('Beer_id', $beer_id);
		$this->db->update('Beers');
	}

	public function getPriceOver($minPrice){
		$query = $this->db->where('AvgPrice >=',$minPrice)->get('Beers');
		return $query->result_array();
	}

	public function getPriceUnder($maxPrice){
		$query = $this->db->where('AvgPrice <=',$maxPrice)->get('Beers');
		return $query->result_array();
	}
	public function getRatingOver($minRating){
		$query = $this->db->where('Rating >=',$minRating)->get('Beers');
		return $query->result_array();
	}
	public function getRatingUnder($maxRating){
		$query = $this->db->where('Rating <=',$maxRating)->get('Beers');
		return $query->result_array();
	}

	public function getByAlcoholVol($beerContent){
		$query = $this->db->like('Alcohol_By_Volume',$beerContent)->get('Beers');
		return $query->result_array();
	}


}