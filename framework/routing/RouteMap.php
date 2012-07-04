<?php
class RouteMap extends Component
{
	private $_routeAliases = [
		'regex' => 'RegexRoute',
		'simple' => 'SimpleRoute',
	];
	/**
	 * @var Route[]
	 */
	private $_routes;

	/**
	 * @param Route[] $routeMap
	 */
	public function __construct(array $routes)
	{
		$this->_routes = [];
		foreach ($routes as $args) {
			$class = array_shift($args);
			$route = array_shift($args);
			// check if the class is an alias
			if (isset($this->_routeAliases[$class])) {
				$class = $this->_routeAliases[$class];
			}
			$obj = new $class($route);
			// set the properties of this object
			foreach ($args as $k => $v) {
				$obj->$k = $v;
			}
			// load the route
			$this->_routes[] = $obj;
		}
	}

	public function attemptRoutes()
	{
		foreach ($this->_routes as $route) {
			if ($route->attempt(Main::app()->route)) {
				return true;
			}
		}
		return false;
	}
}
