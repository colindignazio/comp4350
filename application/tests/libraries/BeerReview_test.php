<?php

class Beer_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->library('BeerReview_lib', array(0 => 'Beer_review_stub'));
        $this->obj = $this->CI->beerreview_lib;
    }

    public function test_getReviewById() {
        $output = $this->obj->getReviewById(1);
        $this->assertEquals('9', $output['details'][0]['beer_id']);
        $this->assertEquals('1', $output['details'][0]['user_id']);
        $this->assertEquals('1', $output['details'][0]['store_id']);
        $this->assertEquals('2', $output['details'][0]['stars']);
        $this->assertEquals('Nothing Special', $output['details'][0]['review']);
        $this->assertEquals('2.14', $output['details'][0]['price']);

        $output = $this->obj->getReviewById(100);
        $this->assertEquals('No matching review for ID: 100', $output['details']);
    }

    public function test_getAllReviews() {
        $this->assertEquals(3, count($this->obj->getAllReviews()['details']));    	
    }
}