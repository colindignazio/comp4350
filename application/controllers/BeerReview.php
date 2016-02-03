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
	
	public function replace($oldid, $newstars, $newreview){
		$data = array(
			'id' => $oldid,
			'stars' => $newstars,
			'review' => $newreview
		)
		
		$this->db->replace('Beer_reviews', $data);
	}
}
?>