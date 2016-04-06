<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Follow_lib {
	public function __construct($dbaccess = array(0 => 'Follow_access_object')) {
		$this->CI =& get_instance();
        $this->CI->load->model($dbaccess[0], 'follow_access');
        $this->CI->load->model('sessions');
    }

    public function getFolloweeCount($userId) {
    	$count = $this->CI->follow_access->getFolloweeCount($userId);

        if($count >= 0) {
            $result = ['status' => 200, 'details' => $count];            
        }
        else {
            $result = ['status' => 400, 'details' => 'Unknown Error.'];                
        }

        return $result;
    }

    public function getRecentFolloweeReviews($userId) {
    	$reviews = $this->CI->follow_access->getRecentFolloweeReviews($userId);
        $result = ['status' => 200, 'details' => $reviews];  

        return $result;
    }

    public function getRecentFolloweeReviewsSession($sessionId) {
        $user = $this->CI->sessions->getUser($sessionId);
        $reviews = $this->CI->follow_access->getRecentFolloweeReviews($user['User_id']);
        $result = ['status' => 200, 'details' => $reviews];  

        return $result;
    }

    public function followUser($sessionId, $followeeId) {
        $followerId = $this->CI->sessions->getUser($sessionId)['User_id'];

        if($followerId != null) {
    	   $success = $this->CI->follow_access->followUser($followerId, $followeeId);    
        } else {
            $success = false;
        }

        if($success) {
            $result = ['status' => 200, 'details' => 'User followed'];            
        }
        else {
            $result = ['status' => 404, 'details' => 'User not found.'];                
        }

        return $result;	
    }

    public function unfollowUser($sessionId, $followeeId) {
        $followerId = $this->CI->sessions->getUser($sessionId)['User_id'];

    	if($followerId != null) {
           $success = $this->CI->follow_access->unfollowUser($followerId, $followeeId);    
        } else {
            $success = false;
        }

        if($success) {
            $result = ['status' => 200, 'details' => 'User unfollowed'];            
        }
        else {
            $result = ['status' => 404, 'details' => 'User not found.'];                
        }

        return $result; 	
    }

    public function isUserFollowed($sessionId, $followeeId) {
        $followerId = $this->CI->sessions->getUser($sessionId)['User_id'];
        $followed = $this->CI->follow_access->isUserFollowed($followerId, $followeeId); 
        $result = ['status' => 200, 'details' => $followed];  

        return $result;       
    }
}