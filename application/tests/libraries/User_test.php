<?php

class User_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->library('User_lib', array(0 => 'User_access_stub'));
        $this->obj = $this->CI->user_lib;
    }

    //Sample integration tests for the create account user story.
    public function test_createAccount() {
        $output = $this->obj->createAccount('Colin', 'test', 'colin@email.com', 'Winnipeg');
        $this->assertEquals('Username already in use', $output['details']);
        $this->assertEquals('400', $output['status']);

        $output = $this->obj->createAccount('unique', 'test', 'mitchellphamm@hotmail.com', 'Winnipeg');
        $this->assertEquals('200', $output['status']);

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

    public function test_Login() {
        $output = $this->obj->login('invalid', 'invalid');
        $this->assertEquals('400', $output['status']);
        $output = $this->obj->login('testuser123', 'invalid');
        $this->assertEquals('400', $output['status']);
        $output = $this->obj->login('invalid', 'testpass');
        $this->assertEquals('400', $output['status']);
        $output = $this->obj->login('testuser123', 'testpass');
        $this->assertEquals('200', $output['status']);
    }

// Commented out until we fix the orgranization of the Sessions logic
/*
    public function test_SetUsername() {
        //Calls that shouldn't allow user access
        $output = $this->obj->setUsername('453', 'Chuffy2');
        $this->assertEquals('401', $output['status']);

        //Valid setUsername call
        $output = $this->obj->setUsername('3', 'Chuffy2');
        $this->assertEquals('200', $output['status']);
        //"Rollback test"
        $output = $this->obj->setUsername('3', 'Chuffy');
        $this->assertEquals('200', $output['status']);
    }

    public function test_SetPassword() {
        //Calls that shouldn't allow user access
        $output = $this->obj->setPassword('453', 'testpass', 'testpass2');
        $this->assertEquals('401', $output['status']);

        //Valid setUsername call
        $output = $this->obj->setPassword('3', 'testpass', 'testpass2');
        $this->assertEquals('200', $output['status']);
        //"Rollback test"
        $output = $this->obj->setPassword('3', 'testpass2', 'testpass');
        $this->assertEquals('200', $output['status']);
    }

    public function test_SetLocation() {
        //Calls that shouldn't allow user access
        $output = $this->obj->setLocation('453', 'The Moon');
        $this->assertEquals('401', $output['status']);

        //Valid setUsername call
        $output = $this->obj->setLocation('3', 'The Moon');
        $this->assertEquals('200', $output['status']);
        //"Rollback test"
        $output = $this->obj->setLocation('3', 'Winnipeg');
        $this->assertEquals('200', $output['status']);
    }
*/
}