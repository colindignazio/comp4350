<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BeerReview extends MY_Controller {
    public function __construct() {
        parent::__construct();
        //$this->load->model('sessions');
        $this->load->model('Beer_review_model');
        $this->load->model('Beer_review_stub');
    }


    public function searchById(){
        if(!$this->requireParams(['review_id'  => 'str'])) return;
        $params = $this->getParams();
        $id = $params['review_id'];
        $query = $this->Beer_review_model->searchById($id);

        if(count($query->result_array())==0){
            $this->sendResponse(200, ['details' => 'No matching review for ID: '.$id]);
        } else {
            $this->sendResponse(200, ['results' => $query->result_array()]);
        }
    }

    public function search(){
        if(!$this->requireParams(['searchToken'  => 'str'])) return;
        $params = $this->getParams();
        $token = $params['searchToken'];

        $responseArray = [];
        $beerMatches = $this->beers->searchByBeer($token);
        $userMatches = $this->beers->searchByUser($token);
        $starMatches = $this->beers->searchByStars($token);


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
            $this->sendResponse(200, ['details' => 'No matching results']);
        } else {
            $this->sendResponse(200, $responseArray);
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

        if(!$this->Beer_review_model->create($data)) {
            $this->sendResponse(500, ['details' => 'An unknown error occurred']);
        }
        else {
            $this->sendResponse(200, ['review' => $data]);
        }
    }


    public function All() {
        $query = $this->Beer_review_model->All();
        $this->sendResponse(200, ['results' => $query]);
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


        $data = array(
            'beer_id'   => intval($beerId),
            'user_id'   => intval($userId),
            'store_id'  => intval($storeId),
            'stars'     => intval($stars),
            'review'    => $review,
            'price'     => floatval($price)
        );


        if(!$this->Beer_review_model->updateReview($data, $reviewId)) {
            $this->sendResponse(500, ['details' => 'An unknown error occurred']);
        }
        else {
            $this->sendResponse(200, ['review' => $data]);
        }

    }
}
?>