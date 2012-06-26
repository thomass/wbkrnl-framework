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
			['email,password', 'required'],
			['email', 'regex', 'pattern' => '/.*@.*/'],
			['email', 'unique'],
			['createDate,modifyDate', 'class', 'class' => 'MongoDate', 'nullable' => true],
			['password', 'regex', 'pattern' => '/^[0-f]+$/'],
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
