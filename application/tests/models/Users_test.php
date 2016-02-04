<?php

class Users_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('users');
        $this->obj = $this->CI->users;
    }

    public function test_validateUserInfo() {
        $username = "TestUsr1";
        $password = "testpass";
        $email = "test@email.com";
        $location = "Winnipeg";

        $output = $this->obj->validateUserInfo($username, $password, $email, $location);
        $this->assertEquals(null, $output);
    }
}