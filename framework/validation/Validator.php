<?php
abstract class Validator extends Component
{
	/**
	 * @var Model
	 */
	public $model;
	/**
	 * @var array
	 */
	public $properties;
	abstract public function validate();
}
