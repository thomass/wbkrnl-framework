<?php
class UniqueValidator extends Validator
{
	public function validate()
	{
		$className = get_class($this->model);
		if (!($this->model instanceof NoSqlModel)){
			throw new Exception(getclass($this->model) . " is not instance of NoSqlModel");
		}
		/* @var $this->model NoSqlModel */
		$query = [];
		foreach ($this->properties as $property) {
			$query[$property] = $this->model->$property;
		}
		if ($this->model->isNew && $className::count($query) > 0) {
			foreach ($this->properties as $property) {
				$this->model->addError($property, "Has to be unique [" . implode(",",$this->properties) . "]");
			}
		}
	}
}
