<?php

class Follow_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->library('Follow_lib', array(0 => 'Follow_access_stub'));
        $this->obj = $this->CI->follow_lib;
    }

    public function test_getFolloweeCount() {
    	$this->assertEquals(3, $this->obj->getFolloweeCount(1)['details']);
    	$this->assertEquals(0, $this->obj->getFolloweeCount(0)['details']);
    	$this->assertEquals(0, $this->obj->getFolloweeCount(10)['details']);
    }

    public function test_followUser() {
    	$this->assertEquals('User followed', $this->obj->followUser(1, 2)['details']);   	
    }

    public function test_unfollowUser() {
    	$this->assertEquals('User unfollowed', $this->obj->unfollowUser(1, 2)['details']);   	  	
    }

    public function test_getRecentFollowweeReviews() {
    	$this->AssertEquals("Decent", $this->obj->getRecentFolloweeReviews(1)['details'][0]['review']);
    	$this->AssertEquals(0, count($this->obj->getRecentFolloweeReviews(0)['details']));
    }

    public function test_isUserFollowed() {
        $this->AssertTrue($this->obj->isUserFollowed(1, 3)['details']);
        $this->AssertFalse($this->obj->isUserFollowed(1, 4)['details']);
    }
}