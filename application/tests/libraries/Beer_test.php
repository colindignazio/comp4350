<?php

class Beer_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->library('Beer_lib', array(0 => 'Beer_access_stub'));
        $this->obj = $this->CI->beer_lib;
    }

    public function test_getBeerById() {
        $output = $this->obj->getBeerById(1);
        $this->assertEquals('Ale', $output[0]['Type']);
        $this->assertEquals('Grasshopper', $output[0]['Name']);
        $this->assertEquals('5', $output[0]['Alcohol_By_Volume']);
        $this->assertEquals('Big Rock', $output[0]['Brewery']);

        $output = $this->obj->getBeerById(100);
        $this->assertEquals(null, $output);
    }

    public function test_getSearchResults() {
        $output = $this->obj->getSearchResults('Sto');
        $this->assertEquals(0, count($output));

        $output = $this->obj->getSearchResults('Ale');
        $this->assertEquals(1, count($output));
        $this->assertEquals('Grasshopper', $output[0]['Name']);
    }

    public function test_getAllBeers() {
        $output = $this->obj->getAllBeers();
        $this->assertEquals(3, count($output));
    }
}