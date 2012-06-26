<?php
class ClassValidator extends Validator
{
	public $class;
	public $nullable = false;

	/**
	 * @param Model  $model
	 * @param string $property
	 */
	protected function validateProperty(Model $model, $property)
	{
		if (get_class($model->$property) !== $this->class && (!$this->nullable && $model->$property === null)
		) {
			$model->addError($property, "Class does not match");
		}
	}
}
