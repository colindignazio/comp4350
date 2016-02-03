<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BeerReview extends MY_Controller {
    public function __construct() {
        parent::__construct();
        //$this->load->model('users');
        //$this->load->model('workers');
        //$this->load->model('sessions');
    }

    public function All() {
	
        $query = $this->db->get('Beer_reviews');

        $this->sendResponse(200, ['results' => $query->result_array()]);
    }
	
	//Used to overwrite the actually 'review' elements, ie stars and review
	public function updateReview($id, $curruserid, $newstars, $newreview){
		$this->db->where('id', $id);
		$query = $this->db->get('Beer_reviews');
		$row = $query->row();
		
		if($row->user_id != $curruserid){
			echo "You cannot edit other users reviews!";
			//TODO: Return a proper error later on
			//Alternatively, we can remove this whole thing and put the check elsewhere
		} else {
			$data = array(
				'stars' => $newstars,
				'review' => str_replace("%20"," ",$newreview),
			);
			$this->db->where('id', $id);
			$this->db->update('Beer_reviews', $data);
		}
		
	}
	
	public function createReview($id, $beerid, $userid, $storeid, $stars, $review, $price){
		
		$data = array(
			'id' => $id,
			'beer_id' => $beerid,
			'user_id' => $userid,
			'store_id' => $storeid,
			'price' => $price
		);
		$this->db->insert('Beer_reviews', $data);
		$this->updateReview($id, $userid, $stars, $review);
	}
}
?>