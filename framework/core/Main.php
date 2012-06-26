<?php
/**
 * @method static Main app()
 * @property Mongo $db
 * @property RouteMap $routeMap
 * @property-read string $route
 */
abstract class Main
{
	/**
	 * @staticvar Main
	 */
	private static $_app;
	/**
	 * @staticvar array
	 */
	private static $_aliases = [];
	/**
	 * @staticvar array
	 */
	private static $_imported = [];

	/**
	 * @return Main
	 */
	public static function getApp()
	{
		return self::$_app;
	}

	/**
	 * @param string $alias
	 * @param string $path
	 */
	public static function alias($alias, $path)
	{
		self::$_aliases[$alias] = $path;
	}

	/**
	 * @param string $class
	 */
	public static function autoload($class)
	{
		include "$class.php";
	}

	/**
	 * @param array $config
	 *
	 * @return Main
	 */
	public static function create(array $config)
	{
		$c = get_called_class();
		return new $c($config);
	}

	/**
	 * @param string $alias
	 */
	public static function import($alias)
	{
		if (substr($alias, -1) !== "*" && is_readable("$alias.php")) {
			return include "$alias.php";
		}
		$alias = substr($alias, 0, -1);
		set_include_path(get_include_path() . PATH_SEPARATOR . self::path($alias));
	}

	/**
	 * @param string $alias
	 */
	public static function path($alias)
	{
		$a = explode(".", $alias);
		if (!array_key_exists($a[0], self::$_aliases)) {
			return null;
		}
		$a[0] = self::$_aliases[$a[0]];
		return implode(DIRECTORY_SEPARATOR, $a);
	}

	/**
	 * @param Main $app
	 */
	protected static function setApp(Main $app)
	{
		self::$_app = $app;
	}

	/**
	 * @param string $name
	 * @param array  $arguments
	 */
	public static function __callStatic($name, array $arguments)
	{
		if ($name == 'app') {
			return self::$_app;
		} else {
			throw new Exception(get_called_class() . '.' . $name . '() is not defined');
		}
	}

	/**
	 * @var array
	 */
	private $_config;
	/**
	 * @var string
	 */
	private $_route;

	/**
	 * @param array $config
	 *
	 * @return Main
	 */
	private function __construct($config)
	{
		// load the configuration
		$this->_config = $config;
		// load default aliases
		self::$_aliases["root"] = realpath(dirname(__FILE__) . "/../..");
		self::alias("framework", dirname(__FILE__) . DIRECTORY_SEPARATOR . '..');
		// import main classes
		self::import("framework.core.*");
		self::import("framework.db.*");
		self::import("framework.interfaces.*");
		self::import("framework.routing.*");
		self::import("framework.validation.*");
		self::import("root.controllers.*");
		self::import("root.models.*");
		foreach ($this->_config as $k => $v) {
			if (isset($v['class'])) {
				$c = new ReflectionClass($v['class']);
				$this->_config[$k] = $c->newInstanceArgs(isset($v['args']) ? $v['args'] : []);
				unset($c);
			}
		}
		if (self::$_app === null) {
			self::setApp($this);
		}
	}

	/**
	 * @param string $n
	 *
	 * @return mixed
	 */
	public function __get($n)
	{
		$getMethod = "get" . ucfirst($n);
		if (method_exists($this, $getMethod)) {
			return $this->$getMethod();
		} elseif (isset($this->_config[$n])) {
			return $this->_config[$n];
		} else {
			throw new Exception(sprintf("Property %s is not defined", $n));
		}
	}

	/**
	 * @param string $n
	 * @param mixed  $v
	 */
	public function __set($n, $v)
	{
		if (isset($this->_config[$n])) {
			throw new Exception(sprintf("Cannot alter configuration '%s'", $n));
		} else {
			throw new Exception(sprintf("Property %s is not defined", $n));
		}
	}

	public function getRoute()
	{
		if ($this->_route === null) {
			$sitePathLength = strlen(pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME));
			$this->_route = $sitePathLength > 1 ? substr($_SERVER['REQUEST_URI'], $sitePathLength) : $_SERVER['REQUEST_URI'];
		}
		return $this->_route;
	}

	/**
	 * handle the given request
	 */
	abstract public function process();

	/**
	 * run the application
	 */
	public function run()
	{
		self::setApp($this);
		$this->process();
	}
}

// make sure php throws exceptions and not errors
//set_error_handler(function($errno,$errstr,$errline,$errcontext){throw new Exception($errstr." on line: ".$errline,$errno);},E_ALL);
// register the autoloader
spl_autoload_register(array("Main", "autoload"));
