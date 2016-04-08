<?php

class Follow_integration_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->library('follow_lib');
        $this->obj = $this->CI->follow_lib;
    }

    //Sample integration tests for the create account user story.
    public function test_follow() {
        $this->assertEquals('User followed', $this->obj->followUser('d9ca485647ef13c1d3250fa4504689a705850c12', 5)['details']);  
        $this->assertEquals('User unfollowed', $this->obj->unfollowUser('d9ca485647ef13c1d3250fa4504689a705850c12', 5)['details']); 
    }

    public function test_getFollowedReviews() {
        $this->AssertEquals("test", $this->obj->getRecentFolloweeReviews(71)['details'][0]['review']);

    }
}