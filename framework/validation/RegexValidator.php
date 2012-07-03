<?php
class RegexValidator extends Validator
{
	public $pattern;

	public function validate()
	{
		foreach ($this->properties as $property) {
			if (!preg_match($this->pattern, $this->model->$property)) {
				$this->model->addError($property, "Regex doesn't match");
			}
		}
	}
}
