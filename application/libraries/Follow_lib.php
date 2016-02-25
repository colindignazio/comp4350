<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Follow_lib {
	public function __construct($dbaccess = array(0 => 'Follow_access_object')) {
		$this->CI =& get_instance();
        $this->CI->load->model($dbaccess[0], 'follow_access');
    }

    public function getFolloweeCount($userId) {
    	return $this->CI->follow_access->getFolloweeCount($userId);
    }

    public function getFolloweeNames($userId) {
    	return $this->CI->follow_access->getFolloweeNames($userId);
    }

    public function getRecentFolloweeReviews($userId) {
    	return $this->CI->follow_access->getRecentFolloweeReviews($userId);
    }

    public function followUser($followerId, $followeeId) {
    	return $this->CI->follow_access->followUser($followerId, $followeeId);    	
    }

    public function unfollowUser($followerId, $followeeId) {
    	return $this->CI->follow_access->unfollowUser($followerId, $followeeId);    	
    }
}