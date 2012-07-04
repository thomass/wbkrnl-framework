<?php
class SimpleRoute extends Route
{
	/**
	 * @var string
	 */
	public $begin;

	/**
	 * @var string
	 */
	public $contains;

	/**
	 * @var string
	 */
	public $end;

	/**
	 * @var string
	 */
	public $whole;

	/**
	 * @return bool True on success, otherwise false
	 */
	public function attempt($query)
	{
		$success = true;
		// check if the pattern matches the beginning of the query
		if ($this->begin !== null && strpos($query, $this->begin) !== 0) {
			$success = false;
		}
		// check if the pattern matches the ending of the query
		if ($this->end !== null && strpos($query, $this->end) !== (strlen($query) - strlen($this->end))) {
			$success = false;
		}
		// check if the whole pattern matches to the query
		if ($this->whole !== null && $query !== $this->whole) {
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
