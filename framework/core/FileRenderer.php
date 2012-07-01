<?php
class FileRenderer extends Component
{
	/**
	 * @param string $file
	 * @param array $variables
	 * @param bool $returnOutput
	 * @return string
	 */
	public function render($file, array $variables = [], $returnOutput = false)
	{
		extract($variables, EXTR_PREFIX_SAME, 'var');
		if ($returnOutput) {
			ob_start();
			require $file;
			return ob_get_clean();
		} else {
			require $file;
		}
	}
}
