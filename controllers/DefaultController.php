<?php
class DefaultController extends Controller
{
	public function actionIndex($id = null, $name = null)
	{
		$this->render("index",["name"=>$name,"id"=>$id]);
	}
}
