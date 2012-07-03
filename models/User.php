<?php
/**
 * @property string    email
 * @property Password  password
 * @property MongoDate createDate
 * @property MongoDate modifyDate
 */
class User extends NoSqlModel
{

	public function onBeforeSave()
	{
		if ($this->getIsNew()) {
			$this->_data['createDate'] = new MongoDate();
		}
		$this->_data['modifyDate'] = new MongoDate();
	}

	/**
	 * @return array
	 */
	public function rules()
	{
		return [
			['required', ['email', 'password']],
			['regex', ['email'], 'pattern' => '/.*@.*/'],
			['unique', ['email']],
			['class', ['createDate', 'modifyDate'], 'class' => 'MongoDate', 'nullable' => true],
			['regex', ['password'], 'pattern' => '/^[0-f]+$/'],
		];
	}

	/**
	 * @param $value
	 */
	public function setPassword($value)
	{
		$this->_data['password'] = Password::hash($value);
	}
}
