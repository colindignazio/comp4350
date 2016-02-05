<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beer_access_stub extends CI_Model
{
    private $table = array(
        0 => array('Beer_id' => 1, 'Type' => "Ale", 'Name' => "Grasshopper", 'Alcohol_By_Volume' => '5', 'Brewery' => "Big Rock"),
        1 => array('Beer_id' => 2, 'Type' => "Lager", 'Name' => "Budweiser", 'Alcohol_By_Volume' => '5', 'Brewery' => "Ab Inbev"),
        2 => array('Beer_id' => 3, 'Type' => "Strong", 'Name' => "Moonshine", 'Alcohol_By_Volume' => '100', 'Brewery' => "Home Brew"),
    );
    public function __construct() {
        parent::__construct();
    }

    public function getAll(){
        return $this->table;
    }
    public function getById($id){
        foreach($this->table as $entry) {
            if ($entry['Beer_id'] == $id) {
                return array($entry);
            }
        }
    }
    public function getByName($name){
        foreach($this->table as $entry) {
            if ($entry['Name'] == $name) {
                return array($entry);
            }
        }
    }
    public function getByBrewery($brewery){
        foreach($this->table as $entry) {
            if ($entry['Brewery'] == $brewery) {
                return array($entry);
            }
        }
    }
    public function getByType($type){
        foreach($this->table as $entry) {
            if ($entry['Type'] == $type) {
                return array($entry);
            }
        }
    }


}