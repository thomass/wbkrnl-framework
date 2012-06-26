<?php
/**
 * @property-read MongoId id
 * @property bool         isNew
 */
abstract class NoSqlModel extends Model
{
	/**
	 * @var string
	 */
	protected static $_collections = [];

	/**
	 * @return string
	 */
	public static function collection()
	{
		if (!isset(self::$_collections[get_called_class()])) {
			self::$_collections[get_called_class()] = Main::app()->db->selectCollection(Main::app()->dbName, get_called_class());
		}
		return self::$_collections[get_called_class()];
	}

	/**
	 * @param array $arguments
	 *
	 * @return int
	 */
	public static function count($arguments)
	{
		return self::collection()->count($arguments);
	}

	/**
	 * @param array $query
	 *
	 * @return NoSqlCursor
	 */
	public static function find(array $query = [])
	{
		return new NoSqlCursor(self::collection()->find($query), get_called_class());
	}

	/**
	 * @var bool
	 */
	private $_new;

	public function __construct()
	{
		$this->_new = true;
		parent::__construct();
	}

	/**
	 * @return bool
	 */
	public function delete()
	{
		return self::collection()->remove(['_id' => $this->id]) !== null;
	}

	/**
	 * @return MongoId
	 */
	public function getId()
	{
		return isset($this->_data['_id']) ? $this->_data['_id'] : null;
	}

	/**
	 * @return bool
	 */
	public function getIsNew()
	{
		return $this->_new;
	}

	/**
	 * Execute some code before the object saves
	 */
	public function onAfterSave()
	{
	}

	/**
	 * Execute some code before the object saves
	 */
	public function onBeforeSave()
	{
	}

	/**
	 * @return array
	 */
	public function properties()
	{
		return array_merge(['_id'], parent::properties());
	}

	/**
	 * @param bool $validate
	 *
	 * @return bool
	 */
	public function save($validate = true)
	{
		if ($validate && !$this->validate()) {
			return false;
		}
		$this->onBeforeSave();
		if (empty($this->_data)) {
			return false;
		}
		$data = [];
		foreach ($this->_data as $k => $v) {
			if (in_array($k, $this->properties())) {
				$data[$k] = $v;
			}
		}
		$r = $this->isNew ? self::collection()->insert($data) : self::collection()->update(['_id' => $this->id], $data);
		if ($r) {
			$this->_new = false;
		}
		$this->onAfterSave();
		return $r;
	}
}
