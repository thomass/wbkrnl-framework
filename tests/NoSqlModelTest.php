<?php
class NoSqlModelTest extends TestCase
{

	public function testUser()
	{
		// clean user collection, we want a clean test
		User::collection()->drop();
		// add user
		$user = new User;
		$user->email = 'john@doe.com';
		$this->assertFalse($user->save(), "User should NOT be saved");
		$user->password = "foo";
		$this->assertNull($user->id, "User should not have an id yet");
		$this->assertTrue($user->validate(), "User should have validated:\n" . json_encode($user->errors()));
		$this->assertTrue($user->save(), "User should have been saved");
		// add another user
		$user = new User;
		$user->email = 'jane@doe.com';
		$user->password = "bar";
		$this->assertNull($user->id, "User should NOT be saved");
		$this->assertTrue($user->validate(), "User should have validated\n" . json_encode($user->errors()));
		$this->assertTrue($user->save(), "User should have been saved");
		// check if there are 2 users in the database
		$userCursor = User::find();
		$this->assertCount(2, $userCursor, "There should be 2 items in the cursor");
		// check if the cursor can be iterated
		foreach ($userCursor as $user) {
			$this->assertInstanceOf(
				"User", $user, "Class should be User, it is: " . ($user === null ? gettype($user) : get_class($user))
			);
		}
		$userCursor->rewind();
		// check the contents of the cursor
		foreach ($userCursor as $user) {
			$this->assertInstanceOf(
				"MongoId", $user->id,
				"Class should be MongoId, it is: " . ($user->id === null ? gettype($user->id) : get_class($user->id))
			);
			$this->assertTrue(is_string($user->email), "Email should be a string: " . gettype($user->email));
			$this->assertTrue(is_string($user->password), "Password should be a string " . gettype($user->password));
			$this->assertInstanceOf(
				"MongoDate", $user->createDate, "Class should be MongoDate, it is: " . (
			$user->createDate === null ? gettype($user->createDate) : get_class($user->createDate))
			);
			$this->assertInstanceOf(
				"MongoDate", $user->modifyDate, "Class should be MongoDate, it is: " . (
			$user->modifyDate === null ? gettype($user->modifyDate) : get_class($user->modifyDate))
			);
		}
	}
}
