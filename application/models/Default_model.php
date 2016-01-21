<?php
class Default_model extends CI_Model {
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
        $this->db->query("SET time_zone='America/Winnipeg'");
        date_default_timezone_set('America/Winnipeg');
    }
}
?>