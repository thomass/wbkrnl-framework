<?php
class DefaultController //extends Controller
{
	public function actionIndex($id = null, $name = null)
	{
		var_dump($name);
		var_dump($id);
	}
}
