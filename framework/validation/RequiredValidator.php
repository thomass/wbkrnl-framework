<?php
class RequiredValidator extends Validator
{
	public function validate()
	{
		foreach ($this->properties as $property) {
			$v = $this->model->$property;
			if ($v === null || $v === [] || $v === '' || is_scalar($v) && trim($v) === '') {
				$this->model->addError($property, "Cannot be empty");
			}
		}
	}
}
