<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class System extends MY_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

	public function ping() {
        $this->sendResponse(200);
	}
}
?>