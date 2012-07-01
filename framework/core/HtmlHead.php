<?php
class HtmlHead extends Component
{
	/**
	 * Default script execution style
	 */
	const SCRIPT_EXECUTION_DEFAULT = 0;
	/**
	 * Asynchronous script execution style (external scripts only)
	 */
	const SCRIPT_EXECUTION_ASYNC = 1;
	/**
	 * Deferred script execution style (external scripts only)
	 */
	const SCRIPT_EXECUTION_DEFER = 2;

	/**
	 * @var array
	 */
	private $_links;
	/**
	 * @var array
	 */
	private $_metas;
	/**
	 * @var array
	 */
	private $_scripts;
	/**
	 * @var string
	 */
	public $title;

	/**
	 * @return string
	 */
	public function __toString()
	{
		ob_start();
		// insert the meta-tags
		foreach ($this->_metas as $meta) {
			$this->insertTag('meta', $meta);
		}
		// insert the title
		echo "<title>" . htmlspecialchars($this->title) . "</title>\n";
		// insert the link-tags
		foreach ($this->_links as $link) {
			$this->insertTag('link', $link);
		}
		// insert scripts
		foreach ($this->_scripts as $script) {
			$this->insertTag('script', $script);
		}
		return ob_get_clean();
	}

	/**
	 * initialize the object
	 */
	public function init() {
		$this->_links=[];
		$this->_metas=[];
		$this->_scripts=[];
	}

	/**
	 * @param string $file
	 * @param string $media
	 * @param string $sizes
	 */
	public function addCssFile($file, $media = null, $sizes = null)
	{
		$link = [
			"href" => $file,
			"rel" => "stylesheet",
			"type" => "text/css",
		];
		if ($media !== null) {
			$link["media"] = $media;
		}
		if ($sizes !== null) {
			$link["sizes"] = $sizes;
		}
		$this->addLink($link);
	}

	/**
	 * @param array $content containing keys like: charset, content, http-equiv, name
	 */
	public function addMeta(array $content)
	{
		$this->_metas[] = $content;
	}

	/**
	 * @param array $content containing keys like: href, media, rel, sizes, type
	 */
	public function addLink(array $content)
	{
		$this->_links[] = $content;
	}

	public function addScriptFile($file, $executionStyle = self::SCRIPT_EXECUTION_DEFAULT)
	{
		$script = [
			"src" => $file,
			"type" => "text/javascript",
		];
		if ($executionStyle === self::SCRIPT_EXECUTION_DEFAULT) {
			// default is nothing
		} elseif ($executionStyle === self::SCRIPT_EXECUTION_DEFER) {
			$script["defer"] = "defer";
		} elseif ($executionStyle === self::SCRIPT_EXECUTION_ASYNC) {
			$script["async"] = "async";
		} else {
			throw new Exception("Undefined execution style");
		}
		$this->_scripts[] = $script;
	}

	private function insertTag($tagName, $attributes)
	{
		echo "<$tagName";
		foreach ($attributes as $attribute => $value) {
			echo ' ' . $attribute . '="' . htmlentities($value, ENT_COMPAT) . '"';
		}
		echo ">\n";
	}
}
