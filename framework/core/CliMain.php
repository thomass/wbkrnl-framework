<?php
class CliMain extends Main
{
	/**
	 * @var array
	 */
	public $args;
	/**
	 * @var string
	 */
	public $scriptName;

	public function process()
	{
	}

	public function run()
	{
		$this->scriptName = $_SERVER['argv'][0];
		$this->args = $_SERVER['argv'];
		parent::run();
	}
}
