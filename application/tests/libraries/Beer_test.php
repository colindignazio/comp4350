<?php

class Beer_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->library('Beer_lib', array(0 => 'Beer_access_stub'));
        $this->obj = $this->CI->beer_lib;
    }