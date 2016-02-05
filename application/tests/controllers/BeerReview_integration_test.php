<?php

class Beer_review_integration_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->library('BeerReview_lib');
        $this->obj = $this->CI->beerreview_lib;
    }

    public function test_createReview() {
        $output = $this->obj->createBeerReview('9', '1', '1', '5', 'The best test ever', '9000.01');
        $this->assertEquals('200', $output['status']);
    }
}