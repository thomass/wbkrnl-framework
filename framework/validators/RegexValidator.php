<?php
class RegexValidator extends Validator
{
	public $pattern;

	/**
	 * @param Model  $model
	 * @param string $property
	 */
	protected function validateProperty(Model $model, $property)
	{
		if (!preg_match($this->pattern, $model->$property)) {
			$model->addError($property, "Regex doesn't match");
		}
	}
}
