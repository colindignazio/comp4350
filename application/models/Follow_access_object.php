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

    public function followUser($followerId, $followeeId) {
        return $this->db->insert('Follows', ['Follower_id' => $followerId, 'Followee_id' => $followeeId]);
    }

    public function unfollowUser($followerId, $followeeId) {
        return $this->db->where('Follower_id', $followerId)
        				->where('Followee_id', $followeeId)
        				->delete('Follows');
    }

    public function getRecentFolloweeReviews($userId) {
    	$this->db->select('*');
    	$this->db->from('Follows');
    	$this->db->join('Beer_reviews', 'Beer_reviews.user_id=Follows.Followee_id');
    	$this->db->join('Users', 'Users.User_id=Follows.Followee_id');
        $this->db->join('Beers', 'Beer_reviews.beer_id=Beers.Beer_id');
        $this->db->join('store', 'Beer_reviews.store_id=store.id');
    	$this->db->where('Follows.Follower_id', $userId);
    	$query = $this->db->get();

        $retArray = [];
        $i = 0;
        $results = $query->result_array();

        foreach($results as $val) { 
            unset($val['User_password']);
            unset($val['User_email_verified']);
            unset($val['User_email_code']);
            unset($val['id']);
            unset($val['Follower_id']);
            unset($val['Followee_id']);
            unset($val['Follow_id']);
            $retArray[$i] = $val;
            $i++;
        }

        return $retArray;
    }

    public function isUserFollowed($followerId, $followeeId) {
        $query = $this->db->where('Follower_id', $followerId)
                          ->where('Followee_id', $followeeId)
                          ->get('Follows');

        return count($query->result_array()) > 0 ? true : false;
    }
}