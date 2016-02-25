<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Follow_access_stub extends CI_Model {
    private $followTable = array(
        0 => array('Follow_id' => 0, 'Follower_id' => "1", 'Followee_id' => 0),
        1 => array('Follow_id' => 2, 'Follower_id' => "1", 'Followee_id' => 3),
        2 => array('Follow_id' => 3, 'Follower_id' => "1", 'Followee_id' => 2),
    );

    private $reviewTable = array(
        0 => array('id'=>1, 'beer_id'=>'9', 'user_id'=>'1', 'store_id'=>'1',
            'stars'=>'2', 'review'=>"Nothing Special", 'price'=>'2.14'),
        1 => array('id'=>2, 'beer_id'=>'11', 'user_id'=>'3', 'store_id'=>'1',
            'stars'=>'5', 'review'=>"Decent", 'price'=>'2.21'),
        2 => array('id'=>3, 'beer_id'=>'9', 'user_id'=>'4', 'store_id'=>'1',
            'stars'=>'1', 'review'=>"Tastes like chicken", 'price'=>'5.33'),
    );

    private $userTable;

    private function generateStubTable() {
        return array(
            0 => array('User_id'=>0, 'User_name'=>'testuser123', 'User_password'=> password_hash('testpass', PASSWORD_DEFAULT), 'User_email'=>'test0@email.com', 
                'User_email_verified'=>0, 'User_email_code'=>"", 'User_location'=>'Winnipeg'),
            1 => array('User_id'=>1, 'User_name'=>'Colin', 'User_password'=> password_hash('testpass', PASSWORD_DEFAULT), 'User_email'=>'test1@email.com', 
                'User_email_verified'=>0, 'User_email_code'=>"", 'User_location'=>'Winnipeg'),
            2 => array('User_id'=>2, 'User_name'=>'Mitchell', 'User_password'=> password_hash('testpass', PASSWORD_DEFAULT), 'User_email'=>'test2@email.com', 
                'User_email_verified'=>0, 'User_email_code'=>"", 'User_location'=>'Winnipeg'),
            3 => array('User_id'=>3, 'User_name'=>'Chuffy', 'User_password'=> password_hash('testpass', PASSWORD_DEFAULT), 'User_email'=>'test2@email.com', 
            'User_email_verified'=>0, 'User_email_code'=>"", 'User_location'=>'Winnipeg')
        );
    }

    public function __construct() {
        parent::__construct(); 
        $this->userTable = $this->generateStubTable();  
    }

    private function getFolloweeIds($userId) {
        $arr = $this->followTable;
        $followees = array();

    	foreach($arr as $entry) {
            if($entry['Follower_id'] == $userId) {
                array_push($followees, $entry['Followee_id']);
            }
        }

        return $followees;
    }

    public function getFolloweeCount($userId) {
        return count($this->getFolloweeIds($userId));
    }

    private function getUser($userId) {
        return $this->userTable[$userId];
    }

    public function followUser($followerId, $followeeId) {
        return true;     
    }

    public function unfollowUser($followerId, $followeeId) {
        return true;      
    }

    private function getReviews($userId) {
        $reviews = array();

        foreach($this->reviewTable as $review) {
            if($review['user_id'] == $userId) {
                array_push($reviews, $review);
            }
        }

        return $reviews;
    }

    public function getRecentFolloweeReviews($userId) {
        $followees = $this->getFolloweeIds($userId);
        $reviews = array();

        foreach($followees as $followee) {
            $reviewsForFollowee = $this->getReviews($followee);
            
            foreach($reviewsForFollowee as $review) {
                array_push($reviews, $review);                
            }
        }

        return $reviews;
    }

    public function isUserFollowed($followerId, $followeeId) {
        $followees = $this->getFolloweeIds($followerId);

        if(in_array($followeeId, $followees)) {
            return true;
        } else {
            return false;
        }
    }
}