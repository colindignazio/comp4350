<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Follow_access_stub extends CI_Model {
    private $followTable = array(
        0 => array('Follow_id' => 0, 'Follower_id' => "1", 'Followee_id' => 0),
        1 => array('Follow_id' => 2, 'Follower_id' => "1", 'Followee_id' => 3),
        2 => array('Follow_id' => 3, 'Follower_id' => "1", 'Followee_id' => 2),
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

    public function getFolloweeIds($userId) {
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

    public function getFolloweeNames($userId) {
        $followees = $this->getFolloweeIds($userId);
        $names = array();

        foreach($followees as $followee) {
            array_push($names, $this->getUser($followee));
        }

        return $names;
    }

    public function followUser($followerId, $followeeId) {
        return true;     
    }

    public function unfollowUser($followerId, $followeeId) {
        return true;      
    }

    public function getRecentFolloweeReviews($userId) {

    }
}