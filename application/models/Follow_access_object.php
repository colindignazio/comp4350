<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Follow_access_object extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getFolloweeCount($userId) {
        $query = $this->db->where('Follower_id', $userId)
                          ->get('Follows');

        return count($query->result_array());
    }

    public function getFolloweeNames($userId) {
        return null;
    }

    public function followUser($followerId, $followeeId) {
        return $this->db->insert('Follows', ['Follower_id' => $followerId, 'Followee_id' => $followeeId]);
    }

    public function unfollowUser($followerId, $followeeId) {
        return $this->db->where('Follower_id', $followerId)
        				->where('Followee_id', $followeeId)
        				->delete('Follows');
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
        $query = $this->db->where('Follower_id', $followerId)
                          ->where('Followee_id', $followeeId)
                          ->get('Follows');

        return count($query->result_array()) > 0 ? true : false;
    }
}