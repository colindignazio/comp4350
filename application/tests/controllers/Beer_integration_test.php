<?php

class Beer_integration_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->library('Beer_lib');
        $this->obj = $this->CI->user_lib;
    }

    //Sample integration tests for the create account user story.
    public function test_getBeerById() {
        $output = $this->obj->getBeerById(1);
        $this->assertEquals('Ale', $output[0]['Type']);
        $this->assertEquals('Ale', $output[0]['Type']);
        $this->assertEquals('Grasshopper', $output[0]['Name']);
        $this->assertEquals('5', $output[0]['Alcohol_By_Volume']);
        $this->assertEquals('Big Rock', $output[0]['Brewery']);
    }

    public function test_getSearchResults() {
        $output = $this->obj->getSearchResults('Ale');
        $this->assertEquals(1, count($output));
        $this->assertEquals('Grasshopper', $output['typeMatches'][0]['Name']);
    }

    public function test_getAllBeers() {
        $output = $this->obj->getAllBeers();
        $this->assertEquals(3, count($output));
    }
}