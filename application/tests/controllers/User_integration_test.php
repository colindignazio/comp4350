<?php

class User_integration_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->library('User_lib');
        $this->obj = $this->CI->user_lib;
    }

    //Sample integration tests for the create account user story.
    public function test_createAccount() {
        //Create account
        $output = $this->obj->createAccount('MitchellTest', 'testpass', 'mitchell@rocks.com', 'Winnipeg');
        //Verify Success
        $this->assertEquals('200', $output['status']);
        //Remove user
        $output = $this->obj->deleteAccount('MitchellTest', 'testpass');
        $this->assertEquals('200', $output['status']);
    }

    public function test_Login() {
        //Test login functionality
        $output = $this->obj->login('Chuffy', 'testpass');
        $this->assertEquals('200', $output['status']);
    }

    public function test_SetUsername() {
        //Call to login and get a sessionId
        $response = $this->obj->login('Chuffy', 'testpass');
        $this->assertEquals('200', $response['status']);
        //Valid setUsername call
        $output = $this->obj->setUsername($response['sessionId'], 'Chuffy2');
        $this->assertEquals('200', $output['status']);
        //"Rollback test"
        $output = $this->obj->setUsername($response['sessionId'], 'Chuffy');
        $this->assertEquals('200', $output['status']);
    }

    public function test_SetPassword() {
        //Call to login and get a sessionId
        $response = $this->obj->login('Chuffy', 'testpass');
        $this->assertEquals('200', $response['status']);
        //Valid setUsername call
        $output = $this->obj->setPassword($response['sessionId'], 'testpass', 'testpass2');
        $this->assertEquals('200', $output['status']);
        //"Rollback test"
        $output = $this->obj->setPassword($response['sessionId'], 'testpass2', 'testpass');
        $this->assertEquals('200', $output['status']);
    }

    public function test_SetLocation() {
        //Call to login and get a sessionId
        $response = $this->obj->login('Chuffy', 'testpass');
        $this->assertEquals('200', $response['status']);
        //Valid setUsername call
        $output = $this->obj->setLocation($response['sessionId'], 'The Moon');
        $this->assertEquals('200', $output['status']);
        //"Rollback test"
        $output = $this->obj->setLocation($response['sessionId'], 'Winnipeg');
        $this->assertEquals('200', $output['status']);
    }
}