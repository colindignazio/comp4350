<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beer_review_model extends CI_Model {

    public $reviewId;
    public $beerId;
    public $userId;
    public $storeId;
    public $stars;
    public $review;
    public $price;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function searchById($id){
        return $this->db->where('id', $id)->get('Beer_reviews')->result_array();
    }
    public function getSpecificBeerReviews($id){
        return $this->db->where('beer_id', $id)->get('Beer_reviews')->result_array();
    }

    public function getStoreId($storeName, $storeAddress) {
        $query = $this->db->where('name', $storeName)
                          ->where('address', $storeAddress) 
                          ->get('store');
        if(count($query->result_array()) > 0) {
            //store already exists
            return $query->row_array()['id'];
        } else {
            //add store and return the new id
            $this->db->insert('store', ['name' => $storeName, 'address' => $storeAddress]);
            return $this->db->insert_id();
        }
    }

    public function searchByBeer($id){
       $query = $this->db->where('beer_id', $id)->get('Beer_reviews');
        return $query->result_array();
    }

    public function searchByUser($id){
        $query = $this->db->where('user_id', $id)->get('Beer_reviews');
        return $query->result_array();
    }

    public function searchByStars($id){
        $query = $this->db->where('stars', $id)->get('Beer_reviews');
        return $query->result_array();
    }

    public function All(){
        $query = $this->db->get('Beer_reviews');
        return $query->result_array();
    }

    public function create($data){
        //Get total reviews
        $query = $this->db->query("SELECT COUNT(beer_id) FROM Beer_reviews WHERE beer_id='" . $data['beer_id'] . "';");
        //Get total review value
        $query2 = $this->db->query("SELECT SUM(price) FROM Beer_reviews WHERE beer_id='" . $data['beer_id'] . "';");
        //Update to reflect new review
        $reviewCount = $query->result_array()[0]['COUNT(beer_id)'];
        $reviewCount++;
        $reviewTotal = $query2->result_array()[0]['SUM(price)'];
        $reviewTotal = $reviewTotal + $data['price'];
        $newAverage = $reviewTotal / $reviewCount;
        $this->db->where('Beer_id', $data['beer_id'])
                 ->update('Beers', ['AvgPrice' => $newAverage]);
        return $this->db->insert('Beer_reviews', $data);
    }

    public function updateReview($data, $reviewId){

        //Basically if any fields aren't filled in, interpret as null, and send it down the chain.
        //Any null values should not replace existing values in the database
        $this->db->where('id', $reviewId);
        $query = $this->db->get('Beer_reviews')->row();

        if(isset($query)){
            $oldprice = $query->price;
            $oldstoreid = $query->store_id;
            $oldreview = $query->review;
        }

        if($data['price'] == null){
            $data['price'] = $oldprice;
        }

        if($data['store_id'] == null){
            $data['store_id'] = $oldstoreid;
        }
        if($data['review'] == null){
            $data['review'] = $oldreview;
        }

        //Insert the updated data into the database
        $this->db->where('id', $reviewId);
        return $this->db->update('Beer_reviews', $data);

    }


}