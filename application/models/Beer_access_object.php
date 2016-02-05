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

}