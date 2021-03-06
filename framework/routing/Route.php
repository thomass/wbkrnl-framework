<?php
/**
 * @property-read array actionArgs
 */
abstract class Route extends Component
{
	/**
	 * @var string
	 */
	private $_actionClass;
	/**
	 * @var string
	 */
	private $_actionMethod;
	/**
	 * @var array
	 */
	private $_actionArgs;

	/**
	 * @param array $route ['class', 'method', 'paramName' => 'value', ...]
	 */
	public function __construct(array $route)
	{
		$this->_actionClass = array_shift($route);
		$this->_actionMethod = array_shift($route);
		$this->_actionArgs = $route;
	}

	/**
	 * @abstract
	 * @return bool True on success, otherwise false
	 */
	abstract public function attempt($query);

	/**
	 * @return array
	 */
	public function getActionArgs() { return $this->_actionArgs; }

	/**
	 * @param array $args
	 */
	public function runAction(array $args)
	{
		$action = new ReflectionMethod($this->_actionClass, $this->_actionMethod);
		$orderedArgs = [];
		foreach ($action->getParameters() as $parameter) {
			try {
				$orderedArgs[] = isset($args[$parameter->getName()]) ? $args[$parameter->getName()] : $parameter->getDefaultValue();
			} catch (ReflectionException $e) {
				// TODO KS - update this to throw error?
				throw new ReflectionException("Parameter [{$parameter->getName()}] of {$this->_actionClass}.{$this->_actionMethod}() does not have a default value and must be set.");
				// or keep it like this?
				$orderedArgs[] = null;
			}
		}
		$action->invokeArgs(new $this->_actionClass, $orderedArgs);
	}

}
