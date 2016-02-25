<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Follow extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('follow_lib');
    }

    public function followUser() {
        if(!$this->requireParams(['followerId'  => 'str', 'followeeId'   => 'str'])) return;
        $params = $this->getParams();
        $result = $this->follow_lib->followUser($params['followerId'], $params['followeeId']);

        $this->sendResponse($result['status'], ['details' => $result['details']]);
    }

    public function unfollowUser() {
        if(!$this->requireParams(['followerId'  => 'str', 'followeeId'   => 'str'])) return;
        $params = $this->getParams();
        $result = $this->follow_lib->unfollowUser($params['followerId'], $params['followeeId']);

        $this->sendResponse($result['status'], ['details' => $result['details']]);
    }

    public function isUserFollowed() {
        if(!$this->requireParams(['followerId'  => 'str', 'followeeId'   => 'str'])) return;
        $params = $this->getParams();
        $result = $this->follow_lib->isUserFollowed($params['followerId'], $params['followeeId']);

        $this->sendResponse($result['status'], ['details' => $result['details']]);  	
    }

    public function getRecentReviews() {
        if(!$this->requireParams(['userId'  => 'str'])) return;
        $params = $this->getParams();
        $result = $this->follow_lib->getRecentFolloweeReviews($params['userId']);

        $this->sendResponse($result['status'], ['details' => $result['details']]);
    }

    public function getTotalFollows() {
        if(!$this->requireParams(['userId'  => 'str'])) return;
        $params = $this->getParams();
        $result = $this->follow_lib->getFolloweeCount($params['userId']);

        $this->sendResponse($result['status'], ['details' => $result['details']]);
    }
}