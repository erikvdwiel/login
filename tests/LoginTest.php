<?php

use \PHPUnit\Framework\TestCase;
use LoginOpdracht\classes\User;

class LoginTest extends TestCase
{
    protected $user;


    protected function setUp(): void
    {
        $this->user = new User();
    }

    public function testSetAndGetPassword()
    {

        $password = "password123";
        $this->user->SetPassword($password);
        $this->assertEquals($password, $this->user->GetPassword());

    }

    public function testValidateUserWithShortName()
    {

        $this->user->username = "joh";
        $errors = $this->user->ValidateUser();
        $this->assertContains("Username moet > 3 en < 50 zijn.", $errors);

    }

    public function testIsLoggedin_notset()
    {

        $this->user->Logout();
        $this->assertFalse($this->user->IsLoggedin());

    }

    public function testLogout()
    {

        session_start();
        $this->user->Logout();

        $isDeleted = (session_status() == PHP_SESSION_NONE || empty(session_id()));
        $this->assertTrue($isDeleted);

    }
}

