<?php

class Follow_integration_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->library('follow_lib');
        $this->obj = $this->CI->follow_lib;
    }

    //Sample integration tests for the create account user story.
    public function test_follow() {
        $this->assertEquals('User followed', $this->obj->followUser('00cbfa4e1fd61dc7cd4c09ae363f6a7d8512d19c', 5)['details']);  
        $this->assertEquals('User unfollowed', $this->obj->unfollowUser('00cbfa4e1fd61dc7cd4c09ae363f6a7d8512d19c', 5)['details']); 
    }

    public function test_getFollowedReviews() {
        $this->AssertEquals("test", $this->obj->getRecentFolloweeReviews(71)['details'][0]['review']);

    }
}