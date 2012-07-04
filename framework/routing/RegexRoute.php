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
			$args = [];
			foreach ($this->actionArgs as $arg => $key) {
				$args[$arg] = $matches[$key][0];
			}
			$this->runAction($args);
			return true;
		}
		return false;
	}
}
