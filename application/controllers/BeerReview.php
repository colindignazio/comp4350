<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BeerReview extends MY_Controller {
    public function __construct() {
        parent::__construct();
        //$this->load->model('sessions');
        $this->load->model('Beer_review_model');
        $this->load->model('Beer_review_stub');
        $this->load->library('BeerReview_lib');
        $this->load->library('Beer_lib');
    }


    public function searchById(){
        if(!$this->requireParams(['review_id'  => 'str'])) return;
        $params = $this->getParams();
        $id = $params['review_id'];

        $result = $this->beerreview_lib->getReviewById($id);
        $this->sendResponse($result['status'], ['results' => $result['details']]);
    }
    private function getSpecificBeerReviews($beer_id){
        $results = $this->beerreview_lib->getSpecificBeerReviews($beer_id);
        return $results;
    }

    public function search(){
        if(!$this->requireParams(['searchToken'  => 'str'])) return;
        $params = $this->getParams();
        $token = $params['searchToken'];

        $result = $this->beerreview_lib->getSearchResults($token);
        if($result === null) {
            $this->sendResponse(400, ['details' => 'Search token too short']);
        }
        elseif(count($result) == 0) {
            $this->sendResponse(200, ['details' => 'No matching results']);
        } else {
            $this->sendResponse(200, $result);
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

        $result = $this->beerreview_lib->createBeerReview($beerId, $userId, $storeId, $stars, $review, $price);

        if($result['status'] == 200) {
            $reviews = $this->getSpecificBeerReviews($beerId);
            $this->beer_lib->updateRating($reviews, $beerId);
            $this->sendResponse($result['status'], ['review' => $result['details']]);
        }
        else {
            $this->sendResponse($result['status'], ['details' => $result['details']]);                 
        }
    }


    public function All() {
        $result = $this->beerreview_lib->getAllReviews();
        $this->sendResponse($result['status'], ['results' => $result['details']]);
    }

    //Used to overwrite the actual 'review' elements, ie stars and review
    public function updateReview(){

        if(!$this->requireParams([
            'review_id' => 'str',
            'user_id'   => 'str',
            'beer_id'   => 'str',
            'stars'     => 'str'
        ])) return;

        $params = $this->getParams();

        $reviewId = $params['review_id'];
        $beerId = $params['beer_id'];
        $userId = $params['user_id'];
        $storeId = $params['store_id'];
        $stars = $params['stars'];
        $review = $params['review'];
        $price = $params['price'];

        $result = $this->beerreview_lib->updateReview($reviewId, $beerId, $userId, $storeId, $stars, $review, $price);

        if($result['status'] == 200) {
            $reviews = $this->getSpecificBeerReviews($beerId);
            $this->beer_lib->updateRating($reviews, $beerId);
            $this->sendResponse($result['status'], ['review' => $result['details']]);
        }
        else {
            $this->sendResponse($result['status'], ['details' => $result['details']]);                 
        }
    }
}