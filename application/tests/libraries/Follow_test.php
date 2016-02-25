<?php

class Follow_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->library('Follow_lib', array(0 => 'Follow_access_stub'));
        $this->obj = $this->CI->follow_lib;
    }

    public function test_getFolloweeCount() {
    	$this->assertEquals(3, $this->obj->getFolloweeCount(1));
    	$this->assertEquals(0, $this->obj->getFolloweeCount(0));
    	$this->assertEquals(0, $this->obj->getFolloweeCount(10));
    }

    public function test_getFolloweeNames() {
    	$names = $this->obj->getFolloweeNames(1);

    	$this->assertEquals("testuser123", $names[0]['User_name']);
    	$this->assertEquals("Chuffy", $names[1]['User_name']);
    	$this->assertEquals("Mitchell", $names[2]['User_name']);

    	$names = $this->obj->getFolloweeNames(0);
    	$this->assertEquals(0, count($names));
    }

    public function test_followUser() {
    	$this->assertTrue($this->obj->followUser(1, 2));   	
    }

    public function test_unfollowUser() {
    	$this->assertTrue($this->obj->unfollowUser(1, 2));   	  	
    }
}