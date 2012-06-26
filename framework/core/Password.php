<?php
class Password extends Component
{
	const SALT_LENGTH = 32;

	/**
	 * @static
	 *
	 * @param string $password
	 *
	 * @return Password
	 */
	public static function hash($password)
	{
		$salt = substr(hash('sha512', uniqid(rand(), true)), 0, self::SALT_LENGTH);
		return $salt . hash('sha512', $salt . $password);
	}

	/**
	 * @param string $hash
	 * @param string $password
	 *
	 * @return bool
	 */
	public static function match($hash, $password)
	{
		return hash('sha512', substr($hash, 0, self::SALT_LENGTH) . $password) === substr($hash, self::SALT_LENGTH);
	}
}
