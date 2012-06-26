<?php
/**
 */
class WebMain extends Main
{
	/**
	 * handle the given request
	 */
	public function process()
	{
		if (!$this->routeMap->attemptRoutes()) {
			throw new Exception("No route matched");
		}

	}
}
