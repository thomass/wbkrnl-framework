<?php
class ClassValidator extends Validator
{
	public $class;
	public $nullable = false;

	public function validate()
	{
		foreach ($this->properties as $property) {
			if (get_class($this->model->$property) !== $this->class && (!$this->nullable && $this->model->$property === null)
			) {
				$this->model->addError($property, "Class does not match");
			}
		}
	}
}
