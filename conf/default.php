<?php
return [
	'appName' => 'Wbkr.nl',
	'locale' => 'en_US',
	'charset' => 'utf-8',
	'db' => [
		'class' => 'Mongo',
		'args' => ['mongodb://www0.sangers.nu'],
	],
	'dbName' => 'live',
	'routeMap' => [
		'class' => 'RouteMap',
		'args' => [include dirname(__FILE__) . DIRECTORY_SEPARATOR . "routeMap.php"],
	],
];
