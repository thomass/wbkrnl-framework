<?php
class UniqueValidator extends Validator
{
	/**
	 * @param Model  $model
	 * @param string $property
	 */
	protected function validateProperty(Model $model, $property)
	{
		$className = get_class($model);
		if ($className::count([$property => $model->$property]) > 0) {
			$model->addError($property, "Has to be unique");
		}
	}
}
