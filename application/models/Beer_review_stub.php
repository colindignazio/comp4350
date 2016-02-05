<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beer_review_stub extends CI_Model {
    private $table = array(
        0 => array('id'=>1, 'beer_id'=>'9', 'user_id'=>'1', 'store_id'=>'1',
            'stars'=>'2', 'review'=>"Nothing Special", 'price'=>'2.14'),
        1 => array('id'=>2, 'beer_id'=>'11', 'user_id'=>'3', 'store_id'=>'1',
            'stars'=>'5', 'review'=>"Decent", 'price'=>'2.21'),
        2 => array('id'=>3, 'beer_id'=>'9', 'user_id'=>'4', 'store_id'=>'1',
            'stars'=>'1', 'review'=>"Tastes like chicken", 'price'=>'5.33'),
    );
    public function __construct() {
        parent::__construct();
    }
    public function searchById($id){
        foreach($this->table as $entry){
            if($entry['id'] ==  $id){
                return array($entry);
            }
        }
    }

    public function searchByStars($id){
        foreach($this->table as $entry){
            if($entry['id'] ==  $id){
                return array($entry);
            }
        }
    }

    public function searchByBeer($id){
        foreach($this->table as $entry){
            if($entry['id'] ==  $id){
                return array($entry);
            }
        }
    }

    public function searchByUser($id){
        foreach($this->table as $entry){
            if($entry['id'] ==  $id){
                return array($entry);
            }
        }
    }

    public function All(){
        return $this->table;
    }

    public function create($data){
        return true;
    }

    public function updateReview($data, $reviewId){
        return true;
    }
}