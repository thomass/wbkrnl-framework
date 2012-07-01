<?php
abstract class Component
{
	public function __construct()
	{
		$this->init();
	}

	public function init()
	{

	}

	/**
	 * @param string $property
	 *
	 * @return mixed
	 */
	public function __get($property)
	{
		$getProperty = "get" . ucfirst($property);
		if (method_exists($this, $getProperty)) {
			return $this->$getProperty();
		} elseif (property_exists($this, $property)) {
			return $this->$property;
		} else {
			throw new Exception(get_called_class() . '.' . $property . ' is not defined');
		}
	}

	/**
	 * @param string $property
	 *
	 * @return bool
	 */
	public function __isset($property)
	{
		$getProperty = "get" . ucfirst($property);
		if (method_exists($this, $getProperty)) {
			return $this->$getProperty() !== null;
		} elseif (property_exists($this, $property)) {
			return $this->$property !== null;
		} else {
			return false;
		}
	}

	/**
	 * @param string $property
	 * @param mixed  $value
	 */
	public function __set($property, $value)
	{
		$setProperty = "set" . ucfirst($property);
		if (method_exists($this, $setProperty)) {
			$this->$setProperty($value);
		} elseif (property_exists($this, $property)) {
			$this->$property = $value;
		} elseif (method_exists($this, "get" . ucfirst($property))) {
			throw new Exception(get_called_class() . '.' . $property . ' cannot be set');
		} else {
			throw new Exception(get_called_class() . '.' . $property . ' is not defined');
		}
	}

	/**
	 * @param string $property
	 */
	public function __unset($property)
	{
		$setProperty = "set" . ucfirst($property);
		if (method_exists($this, $setProperty)) {
			$this->$setProperty(null);
		} elseif (property_exists($this, $property)) {
			$this->$property = null;
		} elseif (method_exists($this, "get" . ucfirst($property))) {
			throw new Exception(get_called_class() . '.' . $property . ' cannot be set');
		} else {
			throw new Exception(get_called_class() . '.' . $property . ' is not defined');
		}
	}
}
