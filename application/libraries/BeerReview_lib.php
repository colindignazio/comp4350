<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BeerReview_lib {
	public function __construct($dbaccess = array(0 => 'Beer_review_model')) {
		$this->CI =& get_instance();
        $this->CI->load->model($dbaccess[0], 'beer_review_access');
    }

    public function getReviewById($id) {
        $query = $this->CI->beer_review_access->searchById($id);

        if(count($query) == 0) {
            $result = ['status' => 400, 'details' => 'No matching review for ID: ' . $id];
        } else {
            $result = ['status' => 200, 'details' => $query];
        }

        return $result;
    }

    public function getSearchResults($token) {
        $responseArray = [];
        $beerMatches = $this->CI->beer_review_access->searchByBeer($token);
        $userMatches = $this->CI->beer_review_access->searchByUser($token);
        $starMatches = $this->CI->beer_review_access->searchByStars($token);
        $idMatches = $this->CI->beer_review_access->searchById($token);

        if (count($idMatches)>0){
            $responseArray = array_merge($responseArray, ['beerMatches' => $idMatches]);
        }

        if (count($beerMatches)>0){
            $responseArray = array_merge($responseArray, ['beerMatches' => $beerMatches]);
        }
        if (count($userMatches)>0){
            $responseArray = array_merge($responseArray, ['userMatches' => $userMatches]);
        }
        if (count($starMatches)>0){
            $responseArray = array_merge($responseArray, ['starMatches' => $starMatches]);
        }

        if(count($responseArray)==0){
            return ['status' => 200, 'details' => 'No matching results'];
        } 
        else {
            return ['status' => 200, 'details' => $responseArray];
        }
    }

    public function createBeerReview($beerId, $userId, $storeId, $stars, $review, $price) {
        //For some reason price is being set to 0
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

        if(!$this->CI->beer_review_access->create($data)) {
            return ['status' => 500, 'details' => 'An unknown error occurred'];
        }
        else {
            return ['status' => 200, 'details' => $data];
        }
    }

    public function getAllReviews() {
        return ['status' => 200, 'details' => $this->CI->beer_review_access->All()];        
    }

    public function updateReview($reviewId, $beerId, $userId, $storeId, $stars, $review, $price) {
        $data = array(
            'beer_id'   => intval($beerId),
            'user_id'   => intval($userId),
            'store_id'  => intval($storeId),
            'stars'     => intval($stars),
            'review'    => $review,
            'price'     => floatval($price)
        );

        if(!$this->CI->beer_review_access->updateReview($data, $reviewId)) {
            return ['status' => 200, 'details' => 'An unknown error occurred'];
        }
        else {
            return ['status' => 200, 'details' => $data];
        }
    }
}