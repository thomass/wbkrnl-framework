<?php
class RegexRoute extends Route
{
	/**
	 * @var string
	 */
	public $pattern;

	/**
	 * @return bool True on success, otherwise false
	 */
	public function attempt($query)
	{
		if (preg_match_all($this->pattern, $query, $matches)) {
			array_shift($matches);
			$args = [];
			foreach ($this->actionArgs as $arg) {
				$args[$arg] = array_shift($matches)[0];
			}
			$this->runAction($args);
			return true;
		}
		return false;
	}
}
