<?php

class NoSqlCursor implements Iterator
{
	/**
	 * @var string
	 */
	private $_className;
	/**
	 * @var MongoCursor
	 */
	private $_cursor;

	/**
	 * @param MongoCursor $cursor
	 * @param string      $className
	 */
	public function __construct(MongoCursor $cursor, $className)
	{
		$this->_cursor = $cursor;
		$this->_className = $className;
	}

	/**
	 * Return the current element
	 *
	 * @return NoSqlModel Returns a child-class of NoSqlModel
	 */
	public function current()
	{
		/* @var $c NoSqlModel */
		$c = new $this->_className;
		$c->setData($this->_cursor->current(), false);
		return $c;
	}

	/**
	 * Move forward to next element
	 *
	 * @return void Any returned value is ignored.
	 */
	public function next()
	{
		$this->_cursor->next();
	}

	/**
	 * Return the key of the current element
	 *
	 * @return scalar scalar on success, or null on failure.
	 */
	public function key()
	{
		return $this->_cursor->key();
	}

	/**
	 * Checks if current position is valid
	 *
	 * @return boolean The return value will be casted to boolean and then evaluated.
	 *       Returns true on success or false on failure.
	 */
	public function valid()
	{
		return $this->_cursor->valid();
	}

	/**
	 * Rewind the Iterator to the first element
	 *
	 * @return void Any returned value is ignored.
	 */
	public function rewind()
	{
		$this->_cursor->rewind();
	}
}
