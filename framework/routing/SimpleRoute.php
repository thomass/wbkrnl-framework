<?php
class SimpleRoute extends Route
{
	/**
	 * @var string
	 */
	public $beginPattern;

	/**
	 * @var string
	 */
	public $contains;

	/**
	 * @var string
	 */
	public $endPattern;

	/**
	 * @var string
	 */
	public $wholePattern;

	/**
	 * @return bool True on success, otherwise false
	 */
	public function attempt($query)
	{
		$success = true;
		// check if the pattern matches the beginning of the query
		if ($this->beginPattern !== null && strpos($query, $this->beginPattern) !== 0) {
			$success = false;
		}
		// check if the pattern matches the ending of the query
		if ($this->endPattern !== null && strpos($query, $this->endPattern) !== (strlen($query) - strlen($this->endPattern))) {
			$success = false;
		}
		// check if the whole pattern matches to the query
		if ($this->wholePattern !== null && $query !== $this->wholePattern) {
			$success = false;
		}
		// check if the query contains the contains-pattern
		if ($this->contains !== null && strpos($query, $this->contains) !== false) {
			$success = false;
		}
		if ($success) {
			// TODO maybe add some support for args
			$this->runAction([]);
			return true;
		}
		return false;
	}
}
