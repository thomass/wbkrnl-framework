<?php
class HtmlHead extends Component
{
	private $_metas;
	private $_links;
	private $_scripts;
	private $_rels;
	public $title;

	public function __toString()
	{
		ob_start();
		foreach ($this->_metas as $meta) {
			echo "<meta";
			foreach ($meta as $attribute => $value) {
				echo ' ' . $attribute . '="' . htmlentities($value, ENT_COMPAT) . '"';
			}
			echo ">\n";
		}
		echo "<title>" . htmlspecialchars($this->title) . "</title>\n";
		return ob_get_clean();
	}

	public function addMeta(array $content)
	{
		$this->_metas[] = $content;
	}

}
