<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Custom404 extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

	public function index()
	{
        $this->sendResponse(403);
	}
}
?>