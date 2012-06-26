<?php
class PasswordTest extends TestCase
{

	public function testPassword()
	{
		$password = Password::hash("foo");
		$this->assertTrue(Password::match($password, "foo"));
		$this->assertFalse(Password::match($password, "bar"));
	}
}
