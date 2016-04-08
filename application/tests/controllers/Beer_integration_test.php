<?php

class Beer_integration_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->library('Beer_lib');
        $this->obj = $this->CI->beer_lib;
    }

    //Sample integration tests for the search user story.
    public function test_getSearchResults() {
        $output = $this->obj->getSearchResults('Ale');
        $this->assertEquals('Anderson Valley Poleeko Pale Ale', $output[0]['Name']);
        $this->assertEquals('5.5', $output[0]['Alcohol_By_Volume']);
        $this->assertEquals('Anderson Valley Brewing ', $output[0]['Brewery']);

        $output = $this->obj->getSearchResults('Grasshopper');
        $this->assertEquals('Grasshopper', $output[0]['Name']);
        $this->assertEquals('5', $output[0]['Alcohol_By_Volume']);
        $this->assertEquals('Big Rock', $output[0]['Brewery']);
    }
    public function test_getAdvancedSearchResults() {
        $output = $this->obj->getAdvancedSearchResults('Grasshopper', 'ale', 'Big Rock', null, null, null, null, null);
        $this->assertEquals('Grasshopper', $output[0]['Name']);
        $this->assertEquals('5', $output[0]['Alcohol_By_Volume']);
        $this->assertEquals('Big Rock', $output[0]['Brewery']);
    }

}