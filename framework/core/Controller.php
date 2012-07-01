<?php
class Controller extends Component
{
	/**
	 * @var string
	 */
	public $layout = "default";

	/**
	 * Render the page layout
	 */
	private function renderLayout($content)
	{
		Main::app()->fileRenderer->render(Main::path("views.layouts") . DIRECTORY_SEPARATOR . $this->layout . ".php", ["content" => $content]);
	}

	/**
	 * @param string $view
	 * @param array $variables
	 * @param null $viewPath
	 */
	public function render($view, array $variables = [], $viewPath = null)
	{
		if ($viewPath === null) {
			$viewPath = get_called_class();
		}
		$this->renderLayout(Main::app()->fileRenderer->render(Main::path("views." . $viewPath) . DIRECTORY_SEPARATOR . "$view.php", $variables, true));
	}

	public function renderHead()
	{

	}
}
