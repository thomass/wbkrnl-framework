<?php
class RequiredValidator extends Validator
{
	/**
	 * @param Model  $model
	 * @param string $property
	 */
	protected function validateProperty(Model $model, $property)
	{
		$v = $model->$property;
		if ($v === null || $v === [] || $v === '' || is_scalar($v) && trim($v) === '') {
			$model->addError($property, "Cannot be empty");
		}
	}
}
