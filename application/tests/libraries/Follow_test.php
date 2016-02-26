<?php

class Follow_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->library('Follow_lib', array(0 => 'Follow_access_stub'));
        $this->obj = $this->CI->follow_lib;
    }

    public function test_getFolloweeCount() {
    	$this->assertEquals(3, $this->obj->getFolloweeCount(71)['details']);
    	$this->assertEquals(0, $this->obj->getFolloweeCount(0)['details']);
    	$this->assertEquals(0, $this->obj->getFolloweeCount(10)['details']);
    }

    public function test_followUser() {
    	$this->assertEquals(200, $this->obj->followUser('00cbfa4e1fd61dc7cd4c09ae363f6a7d8512d19c', 2)['status']);   
        $this->assertEquals(404, $this->obj->followUser('00cbfa4e1fnotasessionidf6a7d8512d19c', 2)['status']);   	
    }

    public function test_unfollowUser() {
    	$this->assertEquals(200, $this->obj->unfollowUser('00cbfa4e1fd61dc7cd4c09ae363f6a7d8512d19c', 2)['status']);   
        $this->assertEquals(404, $this->obj->unfollowUser('00cbfa4e1fd6notasessionid9ae363f6a7d8512d19c', 2)['status']);   	  	
    }

    public function test_getRecentFolloweeReviews() {
    	$this->AssertEquals("Decent", $this->obj->getRecentFolloweeReviews(71)['details'][0]['review']);
    	$this->AssertEquals(0, count($this->obj->getRecentFolloweeReviews(0)['details']));
    }

    public function test_isUserFollowed() {
        $this->AssertTrue($this->obj->isUserFollowed('00cbfa4e1fd61dc7cd4c09ae363f6a7d8512d19c', 3)['details']);
        $this->AssertFalse($this->obj->isUserFollowed('00cbfa4e1fd61dc7cd4c09ae363f6a7d8512d19c', 4)['details']);
    }
}