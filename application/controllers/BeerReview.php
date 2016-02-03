<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BeerReview extends MY_Controller {
    public function __construct() {
        parent::__construct();
        //$this->load->model('users');
        //$this->load->model('workers');
        //$this->load->model('sessions');
    }

    public function seachById(){
        if(!$this->requireParams(['review_id'  => 'str'])) return;
        $params = $this->getParams();
        $id = $params['review_id'];
        $query = $this->db->where('id', $id)
            ->get('Beer_reviews');

        if(count($query->result_array())==0){
            $this->sendResponse(200, ['details' => 'No matching review for ID: '.$id]);
        } else {
            $this->sendResponse(200, ['results' => $query->result_array()]);
        }
    }

    public function create()
    {
        if(!$this->requireParams([
            'user_id'   => 'str',
            'beer_id'   => 'str',
            'stars'     => 'str'
        ])) return;

        $params = $this->getParams();

        $beerId = $params['beer_id'];
        $userId = $params['user_id'];
        $storeId = $params['store_id'];
        $stars = $params['stars'];
        $review = $params['review'];
        $price = $params['price'];

        if($storeId == null)
        {
            $data = [
                'beer_id'   => intval($beerId),
                'user_id'   => intval($userId),
                'store_id'  => null,
                'stars'     => intval($stars),
                'review'    => $review,
                'price'     => floatval($price)
            ];
        }
        else
        {
            $data = [
                'beer_id'   => intval($beerId),
                'user_id'   => intval($userId),
                'store_id'  => intval($storeId),
                'stars'     => intval($stars),
                'review'    => $review,
                'price'     => floatval($price)
            ];
        }

        // check for user reviewing this beer alrady
        //$this->db->where('user_id', $userId);
        //$this->db->where('beer_id', $beerId);

        // Insert row into table

        // currently no verification
        $this->load->database();
        if(!$this->db->insert('Beer_reviews', $data)) {
            $this->sendResponse(500, ['details' => 'An unknown error occurred']);
        }
        else {
            $this->sendResponse(200, ['review' => $data]);
        }
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
		);
		
		$this->db->replace('Beer_reviews', $data);
	}
}
?>