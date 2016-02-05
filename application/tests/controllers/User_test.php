<?php

class User_test extends TestCase {

    //Sample integration tests for the create account user story.
    public function test_createAccount() {
        $output = $this->request('POST', 'user/createAccount', ['userName' => 'Colin', 'password' => 'testpass', 'email' => 'colin@email.com', 'location' => 'Winnipeg']);
        $this->assertContains('Username already in use', $output);

        $output = $this->request('POST', 'user/createAccount', ['userName' => 'uniqueusername1324343', 'password' => 'testpass', 'email' => 'mitchellphamm@hotmail.com', 'location' => 'Winnipeg']);
        $this->assertContains('Email already in use', $output);
/*
        $output = $this->obj->validateUserInfo("TestUsr1", "testpass", "test@email.com", "Winnipeg");
        $this->assertEquals($output, null);

        $output = $this->obj->validateUserInfo("TestUsr1", "testpass", "testemail.com", "Winnipeg");
        $this->assertNotEquals($output, null);

        $output = $this->obj->validateUserInfo("TestUsr1", "", "test@email.com", "Winnipeg");
        $this->assertNotEquals($output, null);

        $output = $this->obj->validateUserInfo("", "testpass", "test@email.com", "Winnipeg");
        $this->assertNotEquals($output, null);

        $output = $this->obj->validateUserInfo("TestUsr1", "testpass", "test@email.com", "");
        $this->assertNotEquals($output, null);*/
    }

    /*
    public function test_isUsernameInUse() {
        $output = $this->obj->isUsernameInUse('testuser123');
        $this->assertEquals(TRUE, $output);

        $output = $this->obj->isUsernameInUse('testuser1234');
        $this->assertEquals(FALSE, $output);

        $output = $this->obj->isEmailInUse('test0@email.com');
        $this->assertEquals(TRUE, $output);

        $output = $this->obj->isUsernameInUse('test0@yahoo.com');
        $this->assertEquals(FALSE, $output);
    }*/

    public function test_Login() {
        $output = $this->request('POST', 'user/login', ['userName' => 'invalid', 'password' => 'invalid']);
        $this->assertContains('Invalid username or password', $output);
        $output = $this->request('POST', 'user/login', ['userName' => 'testuser123', 'password' => 'invalid']);
        $this->assertContains('Invalid username or password', $output);
        $output = $this->request('POST', 'user/login', ['userName' => 'invalid', 'password' => 'testpass']);
        $this->assertContains('Invalid username or password', $output);
        $output = $this->request('POST', 'user/login', ['userName' => 'Chuffy', 'password' => 'testpass']);
        $this->assertContains('OK', $output);
    }

    public function test_SetUsername() {
        //Calls that shouldn't allow user access
        $output = $this->request('POST', 'user/setUsername', []);
        $this->assertContains('Parameter(s) missing', $output);
        $output = $this->request('POST', 'user/setUsername', ['userName' => 'too few params']);
        $this->assertContains('Parameter(s) missing', $output);
        $output = $this->request('POST', 'user/setUsername', ['sessionId' => '0']);
        $this->assertContains('Parameter(s) missing', $output);
        $output = $this->request('POST', 'user/setUsername', ['sessionId' => '0', 'userName' => 'Chuffy2']);
        $this->assertContains('Unauthorized', $output);

        //Call to login and get a sessionId
        $sessionId = $this->request('POST', 'user/login', ['userName' => 'Chuffy', 'password' => 'testpass']);
        $this->assertNotEquals($output, null);
        //Valid setUsername call
        $output = $this->request('POST', 'user/setUsername', ['sessionId' => $sessionId, 'userName' => 'Chuffy2']);
        $this->assertContains('OK', $output);
        //"Rollback test"
        $output = $this->request('POST', 'user/setUsername', ['sessionId' => $sessionId, 'userName' => 'Chuffy']);
        $this->assertContains('OK', $output);
    }

    public function test_SetPassword() {
        //Calls that shouldn't allow user access
        $output = $this->request('POST', 'user/setPassword', []);
        $this->assertContains('Parameter(s) missing', $output);
        $output = $this->request('POST', 'user/setPassword', ['password' => 'too few params']);
        $this->assertContains('Parameter(s) missing', $output);
        $output = $this->request('POST', 'user/setPassword', ['sessionId' => '0']);
        $this->assertContains('Parameter(s) missing', $output);
        $output = $this->request('POST', 'user/setPassword', ['sessionId' => '0', 'oldPass' => 'testpass', 'newPass' => 'testpass2']);
        $this->assertContains('Unauthorized', $output);

        //Call to login and get a sessionId
        $sessionId = $this->request('POST', 'user/login', ['userName' => 'Chuffy', 'password' => 'testpass']);
        $this->assertNotEquals($output, null);
        //Valid setUsername call
        $output = $this->request('POST', 'user/setPassword', ['sessionId' => $sessionId, 'oldPass' => 'testpass', 'newPass' => 'testpass2']);
        $this->assertContains('OK', $output);
        //"Rollback test"
        $output = $this->request('POST', 'user/setPassword', ['sessionId' => $sessionId, 'oldPass' => 'testpass2', 'newPass' => 'testpass']);
        $this->assertContains('OK', $output);
    }

    public function test_SetLocation() {
        //Calls that shouldn't allow user access
        $output = $this->request('POST', 'user/setLocation', []);
        $this->assertContains('Parameter(s) missing', $output);
        $output = $this->request('POST', 'user/setLocation', ['location' => 'too few params']);
        $this->assertContains('Parameter(s) missing', $output);
        $output = $this->request('POST', 'user/setLocation', ['sessionId' => '0']);
        $this->assertContains('Parameter(s) missing', $output);
        $output = $this->request('POST', 'user/setLocation', ['sessionId' => '0', 'location' => 'The Moon']);
        $this->assertContains('Unauthorized', $output);

        //Call to login and get a sessionId
        $sessionId = $this->request('POST', 'user/login', ['userName' => 'Chuffy', 'password' => 'testpass']);;
        $this->assertNotEquals($output, null);
        //Valid setUsername call
        $output = $this->request('POST', 'user/setLocation', ['sessionId' => $sessionId, 'location' => 'The Moon']);
        $this->assertContains('OK', $output);
        //"Rollback test"
        $output = $this->request('POST', 'user/setLocation', ['sessionId' => $sessionId, 'location' => 'Winnipeg']);
        $this->assertContains('OK', $output);
    }
}