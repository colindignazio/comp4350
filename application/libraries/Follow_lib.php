<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Follow_lib {
	public function __construct($dbaccess = array(0 => 'Follow_access_object')) {
		$this->CI =& get_instance();
        $this->CI->load->model($dbaccess[0], 'follow_access');

        if($dbaccess[0] == 'Follow_access_stub') {
            $this->CI->load->library('sessions_lib', array(0 => 'User_access_stub', 1 => 'Sessions_access_stub'));  
        } else {
            $this->CI->load->library('sessions_lib');            
        }
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
        if(count($reviews) > 0) {
            $result = ['status' => 200, 'details' => $reviews]; 
        } else {
            $result = ['status' => 400, 'details' => 'No Reviews Found']; 
        }
        return $result;
    }

    public function getRecentFolloweeReviewsSession($sessionId) {
        $user = $this->CI->sessions_lib->getUser($sessionId);
        if(FALSE !== $user = $this->CI->sessions_lib->getUser($sessionId)) {
            $reviews = $this->CI->follow_access->getRecentFolloweeReviews($user['User_id']);
            if(count($reviews) > 0) {
                $result = ['status' => 200, 'details' => $reviews]; 
            } else {
                $result = ['status' => 400, 'details' => 'No Reviews Found']; 
            }  
        } else {
            $result['status'] = 401;
            $result['details'] = ['Unauthorized'];
        }
        return $result;
    }

    public function followUser($sessionId, $followeeId) {
        $followerId = $this->CI->sessions_lib->getUser($sessionId)['User_id'];

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
        $followerId = $this->CI->sessions_lib->getUser($sessionId)['User_id'];

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
        $followerId = $this->CI->sessions_lib->getUser($sessionId)['User_id'];
        $followed = $this->CI->follow_access->isUserFollowed($followerId, $followeeId); 
        $result = ['status' => 200, 'details' => $followed];  

        return $result;       
    }
}