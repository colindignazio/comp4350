<?php

class Beer_integration_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->library('Beer_lib');
        $this->obj = $this->CI->user_lib;
    }

    //Sample integration tests for the search user story.
    public function test_getSearchResults() {
        $output = $this->obj->getSearchResults('Ale');
        $this->assertEquals('Grasshopper', $output['typeMatches'][0]['Name']);
        $this->assertEquals('5', $output['typeMatches'][0]['Alcohol_By_Volume']);
        $this->assertEquals('Big Rock', $output['typeMatches'][0]['Brewery']);

        $output = $this->obj->getSearchResults('Grasshopper');
        $this->assertEquals('Grasshopper', $output['nameMatches'][0]['Name']);
        $this->assertEquals('5', $output['nameMatches'][0]['Alcohol_By_Volume']);
        $this->assertEquals('Big Rock', $output['nameMatches'][0]['Brewery']);
    }
}