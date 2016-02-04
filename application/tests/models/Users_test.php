<?php

class Users_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('users');
        $this->obj = $this->CI->users;
        $this->obj->loadDB("User_access_stub");
    }

    //Simple test that doesn't use the database
    public function test_validateUserInfo() {
        $output = $this->obj->validateUserInfo("TestUsr1", "testpass", "test@email.com", "Winnipeg");
        $this->assertEquals($output, null);

        $output = $this->obj->validateUserInfo("TestUsr1", "testpass", "testemail.com", "Winnipeg");
        $this->assertNotEquals($output, null);

        $output = $this->obj->validateUserInfo("TestUsr1", "", "test@email.com", "Winnipeg");
        $this->assertNotEquals($output, null);

        $output = $this->obj->validateUserInfo("", "testpass", "test@email.com", "Winnipeg");
        $this->assertNotEquals($output, null);

        $output = $this->obj->validateUserInfo("TestUsr1", "testpass", "test@email.com", "");
        $this->assertNotEquals($output, null);
    }

    //More complicated test that uses a stub database
    public function test_isUsernameInUse() {
        $output = $this->obj->isUsernameInUse('testuser123');
        $this->assertEquals(TRUE, $output);

        $output = $this->obj->isUsernameInUse('testuser1234');
        $this->assertEquals(FALSE, $output);

        $output = $this->obj->isEmailInUse('test0@email.com');
        $this->assertEquals(TRUE, $output);

        $output = $this->obj->isUsernameInUse('test0@yahoo.com');
        $this->assertEquals(FALSE, $output);
    }
}