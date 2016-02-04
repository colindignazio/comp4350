<?php

class User_test extends TestCase {

    //Simple test that doesn't use the database
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

    //More complicated test that uses a stub database
    public function test_isUsernameInUse() {/*
        $output = $this->obj->isUsernameInUse('testuser123');
        $this->assertEquals(TRUE, $output);

        $output = $this->obj->isUsernameInUse('testuser1234');
        $this->assertEquals(FALSE, $output);

        $output = $this->obj->isEmailInUse('test0@email.com');
        $this->assertEquals(TRUE, $output);

        $output = $this->obj->isUsernameInUse('test0@yahoo.com');
        $this->assertEquals(FALSE, $output);*/
    }
}