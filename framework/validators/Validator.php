<?php
abstract class Validator extends Component
{
	/**
	 * @param Model  $model
	 * @param string $property
	 */
	public function validate(Model $model, $property)
	{
		$this->validateProperty($model, $property);
	}

	/**
	 * @param Model  $model
	 * @param string $property
	 */
	abstract protected function validateProperty(Model $model, $property);
}
