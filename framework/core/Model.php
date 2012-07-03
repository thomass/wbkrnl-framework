<?php
abstract class Model extends Component
{
	/**
	 * Contains aliases for internal validators
	 *
	 * @var array
	 */
	private static $validatorAliases = [
		'regex' => 'RegexValidator',
		'required' => 'RequiredValidator',
		'unique' => 'UniqueValidator',
		'class' => 'ClassValidator',
	];
	/**
	 * Contains the db
	 *
	 * @var array
	 */
	protected $_data = [];
	/**
	 * Contains the errors
	 *
	 * @var array
	 */
	protected $_errors = [];
	/**
	 * Contains the properties
	 *
	 * @var array
	 */
	private $_properties;
	/**
	 * Contains the validators
	 *
	 * @var array
	 */
	protected $_validators;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->_data = [];
		$this->_errors = [];
		$this->init();
	}

	/**
	 * Get the value of a property of this
	 *
	 * @param string $property
	 *
	 * @return mixed
	 */
	public function __get($property)
	{
		$getProperty = "get" . ucfirst($property);
		if (method_exists($this, $getProperty)) {
			return $this->$getProperty();
		} elseif (isset($this->_data[$property])) {
			return $this->_data[$property];
		} elseif (in_array($property, $this->properties())) {
			return null;
		} else {
			return parent::__get($property);
		}
	}

	/**
	 * Check whether a property of this is set
	 *
	 * @param string $property
	 *
	 * @return bool
	 */
	public function __isset($property)
	{
		$getProperty = "get" . ucfirst($property);
		if (in_array($property, $this->properties())) {
			return isset($this->_data[$property]);
		} elseif (method_exists($this, $getProperty)) {
			return $this->$getProperty() !== null;
		} else {
			return parent::__isset($property);
		}
	}

	/**
	 * Set a property of this
	 *
	 * @param string $property
	 * @param mixed  $v
	 */
	public function __set($property, $value)
	{
		$setProperty = "set" . ucfirst($property);
		if (method_exists($this, $setProperty)) {
			$this->$setProperty($value);
		} elseif (in_array($property, $this->properties())) {
			$this->_data[$property] = $value;
		} else {
			return parent::__set($property, $value);
		}
	}

	/**
	 * Unset a property of this
	 *
	 * @param string $property
	 */
	public function __unset($property)
	{
		if (property_exists($this, $property)) {
			unset($this->$property);
		} elseif (in_array($property, $this->properties())) {
			unset($this->_data[$property]);
		} else {
			return parent::__unset($property);
		}
	}

	/**
	 * Add an error to a specified property of this
	 *
	 * @param string $property
	 * @param string $message
	 */
	public function addError($property, $message)
	{
		if (!isset($this->_errors[$property])) {
			$this->_errors[$property] = [];
		}
		$this->_errors[$property][] = $message;
	}

	/**
	 * Clear the errors of this
	 *
	 * @param mixed $properties
	 */
	public function clearErrors($properties = [])
	{
		if ($properties === []) {
			$this->_errors = [];
		}
		foreach ($properties as $property) {
			unset($this->_errors[$property]);
		}
	}

	/**
	 * Get the errors of this
	 *
	 * @param string $property
	 *
	 * @return array
	 */
	public function errors($property = null)
	{
		return $property === null ? $this->_errors : (isset($this->_errors[$property]) ? $this->_errors[$property] : []);
	}

	/**
	 * Do some initial stuff
	 */
	public function init()
	{
	}

	/**
	 * Load the required validators for this
	 */
	protected function loadValidators()
	{
		$this->_validators = [];
		foreach ($this->rules() as $options) {
			$validatorClass = array_shift($options);
			$properties = array_shift($options);
			if (isset(self::$validatorAliases[$validatorClass])) {
				$validatorClass = self::$validatorAliases[$validatorClass];
			}
			$validator = new $validatorClass;
			foreach ($options as $option => $value) {
				$validator->$option = $value;
			}
			foreach ($properties as $property) {
				if (!isset($this->_validators[$property])) {
					$this->_validators[$property] = [];
				}
				$this->_validators[$property][] = $validator;
			}
		}
	}

	/**
	 * Get the properties of this
	 *
	 * @return array
	 */
	public function properties()
	{
		if (isset($this->_properties)) {
			return $this->_properties;
		}
		$this->_properties = [];
		foreach ($this->rules() as $rule) {
			$this->_properties = array_merge($this->_properties,$rule[1]);
		}
		$this->_properties = array_unique($this->_properties);
		return $this->_properties;
	}

	/**
	 * Get the rules of this
	 *
	 * @return array
	 */
	abstract public function rules();

	/**
	 * Set all data
	 *
	 * @param array $data
	 * @param bool  $validate
	 */
	public function setData(array $data, $validate = true)
	{
		if ($validate) {
			$this->loadValidators();
		}
		foreach ($this->properties() as $property) {
			if (!isset($data[$property])) {
				continue;
			} elseif (!$validate || isset($this->_validator[$property])) {
				$this->_data[$property] = $data[$property];
			}
		}
	}

	/**
	 * Validate this
	 *
	 * @return bool
	 */
	public function validate()
	{
		$this->clearErrors();
		if ($this->_validators === null) {
			$this->loadValidators();
		}
		foreach ($this->_validators as $property => $validators) {
			foreach ($validators as $validator) {
				$validator->validate($this, $property);
			}
		}
		return $this->_errors === [];
	}
}
